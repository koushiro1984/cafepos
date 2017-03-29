<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PurchaseOrderTrx */

$this->title = 'Create Purchase Order Trx';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-trx-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
