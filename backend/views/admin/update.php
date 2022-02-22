<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Administrativos */

$this->title = 'Update Administrativos: ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Administrativos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'Id' => $model->Id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="administrativos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'toUpdate' => true,
    ]) ?>

</div>
