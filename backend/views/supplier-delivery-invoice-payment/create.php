<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoicePayment */

$this->title = 'Create Pembayaran Pembelian';
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Pembelian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-delivery-invoice-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
