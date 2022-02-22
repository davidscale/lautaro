<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Administrativos */

$this->title = 'Create Administrativos';
$this->params['breadcrumbs'][] = ['label' => 'Administrativos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="administrativos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'toUpdate' => false,
    ]) ?>

</div>
