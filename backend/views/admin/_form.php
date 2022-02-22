<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\jui\DatePicker;
use yii\Model\Administrativo;


  $tipoDocumento = [ 0 => 'DNI', 1 => 'Pasaporte',  2 => 'Otros'];
  $sexo = [0 => 'Masculino', 1 => 'Femenino'];

?>


<div class="administrativos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Id')->textInput() ?>

    <?= $form->field($model, 'Apellido')->textInput() ?>

    <?= $form->field($model, 'Nombres')->textInput() ?>
 
    <?= $form->field($model, 'Tipo_Documento')->dropDownList($tipoDocumento, ['' => 'Seleccione Uno' ])?>

    <?= $form->field($model, 'Nro_Documento')->textInput()?>

    <?= $form->field($model,'Fecha_nacimiento')->widget(yii\jui\DatePicker::className(),['clientOptions' => ['dateFormat' => 'yy-mm-dd']])?>

    <?= $form->field($model, 'Nacionalidad')->textInput() ?>

    <?= $form->field($model, 'Email')->textInput() ?>

    <?= $form->field($model, 'Telefono')->textInput() ?>

    <?= $form->field($model, 'Celular')->textInput() ?>

    <?= $form->field($model, 'Calle')->textInput() ?>

    <?= $form->field($model, 'Altura')->textInput() ?>

    <?= $form->field($model, 'Piso')->textInput() ?>

    <?= $form->field($model, 'Dpto.')->textInput() ?>

    <?= $form->field($model, 'Pais')->textInput() ?>

    <?= $form->field($model, 'Provincia')->textInput() ?>

    <?= $form->field($model, 'Localidad')->textInput() ?>

    <?= $form->field($model, 'Estado')->textInput() ?>

    <?= $form->field($model, 'Fecha_de_baja')->textInput() ?>

    <?= $form->field($model, 'Motivo_de_baja')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
