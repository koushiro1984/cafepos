<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PurchaseOrder */

$this->title = 'Update Purchase Order: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-order-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelPurchaseOrderTrx' => $modelPurchaseOrderTrx,
        'modelPurchaseOrderTrxs' => !empty($modelPurchaseOrderTrxs) ? $modelPurchaseOrderTrxs : NULL
    ]) ?>

</div>
