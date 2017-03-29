<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryTrx */

$this->title = 'Create Supplier Delivery Trx';
$this->params['breadcrumbs'][] = ['label' => 'Supplier Delivery Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-delivery-trx-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
