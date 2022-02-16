<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verificacion', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Hola <?= Html::encode($user->username) ?>,</p>

    <p>Accede al siguiente link para verificar tu mail:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
   
</div>
