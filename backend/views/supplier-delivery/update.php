<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDelivery */

$this->title = 'Update Supplier Delivery: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplier-delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelSupplierDeliveryTrx' => $modelSupplierDeliveryTrx,
        'modelSupplierDeliveryTrxs' => !empty($modelSupplierDeliveryTrxs) ? $modelSupplierDeliveryTrxs : NULL
    ]) ?>

</div>
