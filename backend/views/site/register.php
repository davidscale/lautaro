<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\SignupForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'SignupForm';
?>
<div class="site-signup p-1 my-2">
    <div class="offset-lg-3 col-lg-6">

        <img src="https://www.unlz.edu.ar/wp-content/uploads/2019/09/rectorado.jpg" class="img-thumbnail img-fluid" alt="img-log">

        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 're_password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
        </div>

        <div>
            <a href="/ian/site/login">Do have an account?</a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>