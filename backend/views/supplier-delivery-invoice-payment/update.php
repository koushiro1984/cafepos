<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoicePayment */

$this->title = 'Update Pembayaran Pembelian: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Pembelian', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplier-delivery-invoice-payment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
