<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoicePayment */

$this->title = 'Update Sale Invoice Payment: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sale Invoice Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sale-invoice-payment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
