<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDelivery */

$this->title = 'Create Supplier Delivery';
$this->params['breadcrumbs'][] = ['label' => 'Supplier Delivery', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelSupplierDeliveryTrx' => $modelSupplierDeliveryTrx,
        'modelSupplierDeliveryTrxs' => !empty($modelSupplierDeliveryTrxs) ? $modelSupplierDeliveryTrxs : NULL
    ]) ?>

</div>
