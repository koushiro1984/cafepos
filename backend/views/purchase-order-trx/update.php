<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PurchaseOrderTrx */

$this->title = 'Update Purchase Order Trx: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-order-trx-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
