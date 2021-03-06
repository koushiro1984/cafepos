<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoice */

$this->title = 'Update Sale Invoice: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sale Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sale-invoice-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
