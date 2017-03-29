<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ReturSale */

$this->title = 'Update Retur Sale: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Retur Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="retur-sale-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
