<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryTrx */

$this->title = 'Update Supplier Delivery Trx: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Delivery Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplier-delivery-trx-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
