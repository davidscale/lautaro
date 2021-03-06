<?php

namespace backend\models;

use app\models\SgaPeriodo;
use app\models\SgaPeriodoLectivo;
use common\models\User;
use Yii;
use yii\base\Model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PHPOffice\PhpSpreadsheet\Style\Alignment;

require '../../vendor/autoload.php';

/**
 * Report form
 */
class ReportForm extends Model
{
    public $reports_name = [
        0 => 'Notas de Cursada',
        1 => 'Rendimiento Académico de Cátedras'
    ];

    public $report_name;
    public $propuesta;
    public $anio;
    public $ubicacion;
    public $periodo;

    // Will be used in the Xlsx generated
    private $title;
    private $subtitle;
    private $name_arc;

    private $_report;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['report_name', 'required'],
            [
                ['propuesta', 'anio', 'ubicacion', 'periodo'],
                'required',
                'when' => function ($model) {
                    return $model->report_name == 0;
                },
                'whenClient' => "function (attribute, value) {
                                    return $('#report_name').val() == 0;
                                }"
            ],
            [
                ['anio', 'periodo'],
                'required',
                'when' => function ($model) {
                    return $model->report_name == 1;
                },
                'whenClient' => "function (attribute, value) {
                                    return $('#report_name').val() == 1;
                                }"
            ],
        ];
    }

    /**
     * Valid if fields are OK and return report
     *
     * @return Report|null
     */
    public function generate()
    {
        if ($this->validate()) {

            $query = $this->getQuery();
            $data = Yii::$app->db_guarani->createCommand($query)->queryAll();

            if ($data) {
                $this->_report = (object) $data;

                $this->setTitles();
                return $this->_report;
            } else {
                $this->hasErrors('La búsqueda no encontró resultados');
            }
        }
        return null;
    }

    private function getQuery()
    {
        $rta = null;

        switch ($this->report_name) {
            case 0:
                $rta = "SELECT 
                    per.apellido,
                    per.nombres,
                    pd.nro_documento,
                    co.nombre AS comision,
                    ma.codigo AS materia,
                    ad.cond_regularidad,
                    
                    CASE
                        WHEN (ad.resultado = 'A') THEN 'P'
                        WHEN (ad.resultado = 'R') THEN 'N'
                        WHEN (ad.resultado = 'U') THEN 'U'
                        END AS resultado,
                    CASE 
                        WHEN (ad.nota = 'Ausente') THEN ''
                        ELSE ad.nota
                        END AS nota,
                    to_char(ad.fecha, 'DD/MM/YYYY') AS fecha,
                    libro.nro_libro,
                    acta.nro_acta,
                    ad.folio,
                    ad.renglon,
                    acta.renglones_folio,
                    acta.origen
                    FROM sga_comisiones AS co
                    JOIN sga_elementos AS ma ON co.elemento = ma.elemento
                    JOIN sga_actas AS acta ON co.comision = acta.comision
                    LEFT JOIN sga_actas_detalle AS ad ON acta.id_acta = ad.id_acta
                    JOIN sga_actas_folios AS af ON acta.id_acta = af.id_acta AND af.folio = ad.folio
                    JOIN sga_libros_tomos AS lt ON af.libro_tomo = lt.libro_tomo
                    JOIN sga_libros_actas AS libro ON lt.libro = libro.libro
                    LEFT JOIN sga_cond_regularidad AS cr ON ad.cond_regularidad = cr.cond_regularidad
                    LEFT JOIN sga_escalas_notas_resultado AS enr ON ad.resultado = enr.resultado
                    JOIN sga_alumnos AS alu ON ad.alumno = alu.alumno
                    JOIN mdp_personas AS per ON alu.persona = per.persona
                    JOIN mdp_personas_documentos AS pd ON alu.persona = pd.persona
                    JOIN sga_periodos_lectivos AS pl ON pl.periodo = " . $this->periodo . " AND co.periodo_lectivo = pl.periodo_lectivo
                    JOIN sga_periodos AS ps ON pl.periodo = ps.periodo
                    WHERE co.ubicacion = " . $this->ubicacion . " AND acta.origen = 'P'
                    ORDER BY 1,3";
                break;

            case 1:
                $rta = "SELECT 
                            e.codigo AS materia, 
                            ca.nombre AS catedra, 
                            COUNT (CASE WHEN d.resultado = 'U' THEN 1 ELSE NULL END) AS aprob, 
                            COUNT (CASE WHEN d.resultado = 'A' THEN 1 ELSE NULL END) AS repro, 
                            COUNT (CASE WHEN d.resultado != 'U' AND d.resultado != 'A' THEN 1 ELSE NULL END) AS ausen
                        FROM sga_actas a 
                        LEFT JOIN sga_actas_detalle d ON a.id_acta = d.id_acta
                        LEFT JOIN sga_comisiones co ON a.comision = co.comision
                        LEFT JOIN sga_catedras ca ON co.catedra = ca.catedra
                        LEFT JOIN sga_elementos e ON co.elemento = e.elemento
                        JOIN sga_periodos_lectivos AS pl ON co.periodo_lectivo = pl.periodo_lectivo AND pl.periodo = " . $this->periodo . "
                        JOIN sga_periodos AS ps ON pl.periodo = ps.periodo
                        WHERE a.origen = 'P'
                        
                        GROUP BY ca.nombre, co.elemento, materia
                        ORDER BY 1, 2";
                break;
        }
        return $rta;
    }

    private function setTitles(): void
    {
        switch ($this->report_name) {

            case 0:
                $this->title = 'NOTAS DE CURSADA';
                $this->subtitle = '';
                $this->name_arc = 'Notas de Cursada';
                break;

            case 1:
                $data = SgaPeriodo::find()
                    ->where(['periodo' => $this->periodo])
                    ->one();

                $periodo = strtoupper(utf8_encode($data->nombre));

                $this->title = 'RENDIMIENTO ACADÉMICO DE CÁTEDRAS - ' . $periodo;
                $this->subtitle = $periodo;
                $this->name_arc = 'Rendimiento de Cátedras';
                break;

            default:
                # code...
                break;
        }
    }


    public function generateExcel(): bool
    {
        // It will take unlimited memory usage of server, it's working fine.
        ini_set('memory_limit', '-1');

        if ($this->generate()) {
            switch ($this->report_name) {
                case 0:
                    $this->excelOfNotaCursada();
                    break;

                case 1:
                    $this->excelOfRendimiento();
                    break;

                default:
                    return false;
                    break;
            }
            return true;
        }
        return false;
    }

    private function excelOfNotaCursada(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headerstyle = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 12,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $company_name_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        $titlestyle = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 14,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $titledatestyle = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            )
        );

        $titleborder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $contentstyle = array(
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_TOP,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $counterdata = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            )
        );

        $min_word = 'A';
        $max_word = 'K';


        $sheet->mergeCells($min_word . '1:' . $max_word . '2');
        $sheet->mergeCells($min_word . '5:' . $max_word . '6');
        $sheet->mergeCells($min_word . '7:' . $max_word . '7');

        $sheet->setCellValue($min_word . '1', $this->title);
        $sheet->setCellValue($min_word . '5', $this->subtitle);
        $sheet->setCellValue($min_word . '7', date("d/m/Y H:i"));

        $sheet
            ->setCellValue($min_word . '9', 'Nro. Doc')
            ->setCellValue('B9', 'Cod. Materia')
            ->setCellValue('C9', 'Cond. Regularidad')
            ->setCellValue('D9', 'Resultado')
            ->setCellValue('E9', 'Nota')
            ->setCellValue('F9', 'Fecha')
            ->setCellValue('G9', 'Nro. Libro')
            ->setCellValue('H9', 'Nro. Acta')
            ->setCellValue('I9', 'Folio')
            ->setCellValue('J9', 'Renglón')
            ->setCellValue($max_word . '9', 'Renglones Folio');

        foreach (range($min_word, $max_word) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $counter = 10;
        foreach ($this->_report as $row) {
            $sheet
                ->setCellValue($min_word . $counter, '' . $row['nro_documento'])
                ->setCellValue('B' . $counter, '' . $row['materia'])
                ->setCellValue('C' . $counter, '' . $row['cond_regularidad'])
                ->setCellValue('D' . $counter, '' . $row['resultado'])
                ->setCellValue('E' . $counter, '' . $row['nota'])
                ->setCellValue('F' . $counter, '' . $row['fecha'])
                ->setCellValue('G' . $counter, '' . $row['nro_libro'])
                ->setCellValue('H' . $counter, '' . $row['nro_acta'])
                ->setCellValue('I' . $counter, '' . $row['folio'])
                ->setCellValue('J' . $counter, '' . $row['renglon'])
                ->setCellValue($max_word . $counter, '' . $row['renglones_folio']);

            $counter++;
        }

        $counter = $counter + 2;
        $sheet->mergeCells($min_word . $counter . ':' . $max_word . $counter);
        $sheet->setCellValue($min_word . $counter, '' . 'Total de Resultados: ' . ($counter - 12));

        $sheet->getColumnDimension($min_word)->setAutoSize(false);
        $sheet->getColumnDimension($max_word)->setAutoSize(true);

        $sheet->getStyle($min_word . '1')->applyFromArray($company_name_style);
        $sheet->getStyle($min_word . '9:' . $min_word . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle($min_word . '5')->applyFromArray($titlestyle);
        $sheet->getStyle($min_word . '7')->applyFromArray($titledatestyle);
        $sheet->getStyle($min_word . '5:' . $max_word . '7')->applyFromArray($titleborder);

        $sheet->getStyle('B5:' . $max_word . $sheet->getHighestRow())->applyFromArray($contentstyle);

        $sheet->getStyle($min_word . '9:' . $max_word . '9')->applyFromArray($headerstyle);
        $sheet->getStyle($min_word . '9:' . $max_word . '9')->getFont()->setBold(true);

        $sheet->getStyle($min_word . $counter)->applyFromArray($counterdata);

        $sheet->setShowGridLines(false);

        $spreadsheet->getActiveSheet()->setTitle($this->title);

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->name_arc . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    private function excelOfRendimiento(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $title_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 14,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $subtitle_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $header_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $header_border = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $table_border = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $table_style = array(
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $min_word = 'A';
        $max_word = 'I';

        $sheet->mergeCells($min_word . '1:' . $max_word . '1');
        $sheet->mergeCells($min_word . '3:' . $max_word . '3');

        $sheet->setCellValue($min_word . '1', $this->title);
        $sheet->setCellValue($min_word . '3', $this->subtitle);

        $sheet
            ->setCellValue($min_word . '4', 'Materia')
            ->setCellValue('B4', 'Catedra')
            ->setCellValue('C4', 'Aprobados')
            ->setCellValue('D4', '%')
            ->setCellValue('E4', 'Reprobados')
            ->setCellValue('F4', '%')
            ->setCellValue('G4', 'Ausentes')
            ->setCellValue('H4', '%')
            ->setCellValue($max_word . '4', 'Total');

        foreach (range($min_word, $max_word) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $i = 5;
        foreach ($this->_report as $row) {
            $sheet
                ->setCellValue($min_word . $i, $row['materia'])
                ->setCellValue('B' . $i, $row['catedra'])
                ->setCellValue('C' . $i, $row['aprob'])
                ->setCellValue('E' . $i, $row['repro'])
                ->setCellValue('G' . $i, $row['ausen'])

                ->setCellValue($max_word . $i, '=SUM(C' . $i . '+E' . $i . '+G' . $i . ')')    // First, need the acum

                ->setCellValue('D' . $i, '=(+C' . $i . '/I' . $i . ')')
                ->setCellValue('F' . $i, '=(+E' . $i . '/I' . $i . ')')
                ->setCellValue('H' . $i, '=(+G' . $i . '/I' . $i . ')');
            $i++;
        }

        $sheet->getColumnDimension($min_word)->setAutoSize(false);
        $sheet->getColumnDimension($max_word)->setAutoSize(true);

        $sheet->getStyle($min_word . '1')->applyFromArray($title_style);
        $sheet->getStyle($min_word . '3')->applyFromArray($subtitle_style);

        $sheet->getStyle($min_word . '4:' . $max_word . '4')->applyFromArray($header_style);
        $sheet->getStyle($min_word . '4:' . $max_word . '4')->applyFromArray($header_border);
        $sheet->getStyle($min_word . '4:' . $max_word . '4')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f8cbad');


        $sheet->getStyle('D4:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');
        $sheet->getStyle('F4:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');
        $sheet->getStyle('H4:H' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');


        $sheet->getStyle('A4:' . $max_word . $sheet->getHighestRow())->applyFromArray($table_style);
        $sheet->getStyle('A4:' . $max_word . $sheet->getHighestRow())->applyFromArray($table_border);

        $spreadsheet->getActiveSheet()->setTitle($this->name_arc);
        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->name_arc . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}