<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


<?php


/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Iniciar Sesion';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
        <div class="card card-container">    
    <div class="text-center">
        <img src="http://www.derecho.unlz.edu.ar/web_derecho_2016/images/logo.fw.png" >
    </div>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">

        <h1><?= Html::encode($this->title) ?></h1>
       
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?=  $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                
                <?= Html::submitButton('Iniciar Sesion', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                
            </div>
            <div>
                 <h2><a class="btn btn-lg btn-outline-warning" href="http://170.210.104.157/ian/requestPasswordResetToken">Olvide Contraseña</a></h2>
            </div>
        <?php ActiveForm::end(); ?>
        </div>
  </div>
 </div>
</div>