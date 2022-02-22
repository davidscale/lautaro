<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Administrativos */

$this->title = $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Administrativos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="administrativos-view">
<h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'Id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'Id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            'Apellido',
            'Nombres',
            'Tipo_Documento',
            'Nro_Documento',
            'Fecha_nacimiento',
            'Sexo',
            'Nacionalidad',
            'Email:email',
            'Telefono',
            'Celular',
            'Calle',
            'Altura',
            'Piso',
            'Dpto.',
            'Pais',
            'Provincia',
            'Localidad',
            'Estado',
            'Fecha_de_baja',
            'Motivo_de_baja',
        ],
    ]) ?>

</div>
