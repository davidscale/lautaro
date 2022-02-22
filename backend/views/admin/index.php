<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Administrativo */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Administrativos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="administrativos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Administrativos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'Id',
            'Apellido',
            'Nombres',
            //'Tipo_Documento',
            'Nro_Documento',
            //'Fecha_nacimiento',
            //'Sexo',
            //'Nacionalidad',
            'Email:email',
            'Telefono',
            'Celular',
            //'Calle',
            //'Altura',
            //'Piso',
            //'Dpto.',
            //'Pais',
            //'Provincia',
            //'Localidad',
            //'Estado',
            //'Fecha_de_baja',
            //'Motivo_de_baja',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Administrativos $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'Id' => $model->Id]);
                 }
            ],
        ],
    ]); ?>


</div>
