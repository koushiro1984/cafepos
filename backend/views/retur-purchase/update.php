<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ReturPurchase */

$this->title = 'Update Retur Purchase: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Retur Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="retur-purchase-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelReturPurchaseTrx' => $modelReturPurchaseTrx,
        'modelReturPurchaseTrxs' => !empty($modelReturPurchaseTrxs) ? $modelReturPurchaseTrxs : NULL
    ]) ?>

</div>
