<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Administrativo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="administrativos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'Apellido') ?>

    <?= $form->field($model, 'Nombres') ?>

    <?= $form->field($model, 'Tipo_Documento') ?>

    <?= $form->field($model, 'Nro_Documento') ?>

    <?php // echo $form->field($model, 'Fecha_nacimiento') ?>

    <?php // echo $form->field($model, 'Sexo') ?>

    <?php // echo $form->field($model, 'Nacionalidad') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'Telefono') ?>

    <?php // echo $form->field($model, 'Celular') ?>

    <?php // echo $form->field($model, 'Calle') ?>

    <?php // echo $form->field($model, 'Altura') ?>

    <?php // echo $form->field($model, 'Piso') ?>

    <?php // echo $form->field($model, 'Dpto.') ?>

    <?php // echo $form->field($model, 'Pais') ?>

    <?php // echo $form->field($model, 'Provincia') ?>

    <?php // echo $form->field($model, 'Localidad') ?>

    <?php // echo $form->field($model, 'Estado') ?>

    <?php // echo $form->field($model, 'Fecha_de_baja') ?>

    <?php // echo $form->field($model, 'Motivo_de_baja') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
