<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ReturPurchaseTrx */

$this->title = 'Update Retur Purchase Trx: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Retur Purchase Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="retur-purchase-trx-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
