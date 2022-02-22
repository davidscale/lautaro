<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\PasswordResetRequestForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Request Password';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-request-password-reset d-flex flex-column justify-content-center min-vh-100">
    <div class="offset-lg-3 col-lg-6 bg-color">

        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        

        <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>

        <div class="form-group">
            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email..'])->label(false) ?>
        </div>

        <div class="my-2 d-flex flex-row justify-content-end">
            <a href="/agustin/site/login">Go back?</a>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-block', 'name' => 'send-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>