<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Administrativos".
 *
 * @property int $Id
 * @property string $Apellido
 * @property string $Nombres
 * @property int $Tipo_Documento
 * @property string $Nro_Documento
 * @property int $Fecha_nacimiento
 * @property string $Sexo
 * @property string $Nacionalidad
 * @property string $Email
 * @property string|null $Telefono
 * @property string|null $Celular
 * @property string|null $Calle
 * @property int|null $Altura
 * @property string|null $Piso
 * @property string|null $Dpto.
 * @property int|null $Pais
 * @property int|null $Provincia
 * @property int|null $Localidad
 * @property string $Estado
 * @property int|null $Fecha_de_baja
 * @property string|null $Motivo_de_baja
 */
class Administrativos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Administrativos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'Apellido', 'Nombres', 'Tipo_Documento', 'Nro_Documento', 'Fecha_nacimiento', 'Sexo', 'Nacionalidad', 'Email', 'Estado'], 'required'],
            [['Id', 'Tipo_Documento', 'Fecha_nacimiento', 'Altura', 'Pais', 'Provincia', 'Localidad', 'Fecha_de_baja'], 'default', 'value' => null],
            [['Id', 'Tipo_Documento', 'Fecha_nacimiento', 'Altura', 'Pais', 'Provincia', 'Localidad', 'Fecha_de_baja'], 'integer'],
            [['Apellido', 'Nombres', 'Nro_Documento', 'Sexo', 'Nacionalidad', 'Email', 'Telefono', 'Celular', 'Calle', 'Piso', 'Dpto.', 'Estado', 'Motivo_de_baja'], 'string'],
            [['Id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'Apellido' => Yii::t('app', 'Apellido'),
            'Nombres' => Yii::t('app', 'Nombres'),
            'Tipo_Documento' => Yii::t('app', 'Tipo Documento'),
            'Nro_Documento' => Yii::t('app', 'Nro Documento'),
            'Fecha_nacimiento' => Yii::t('app', 'Fecha Nacimiento'),
            'Sexo' => Yii::t('app', 'Sexo'),
            'Nacionalidad' => Yii::t('app', 'Nacionalidad'),
            'Email' => Yii::t('app', 'Email'),
            'Telefono' => Yii::t('app', 'Telefono'),
            'Celular' => Yii::t('app', 'Celular'),
            'Calle' => Yii::t('app', 'Calle'),
            'Altura' => Yii::t('app', 'Altura'),
            'Piso' => Yii::t('app', 'Piso'),
            'Dpto.' => Yii::t('app', 'Dpto'),
            'Pais' => Yii::t('app', 'Pais'),
            'Provincia' => Yii::t('app', 'Provincia'),
            'Localidad' => Yii::t('app', 'Localidad'),
            'Estado' => Yii::t('app', 'Estado'),
            'Fecha_de_baja' => Yii::t('app', 'Fecha De Baja'),
            'Motivo_de_baja' => Yii::t('app', 'Motivo De Baja'),
        ];
    }
}
