<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DirectPurchase */

$this->title = 'Update Direct Purchase: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Direct Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="direct-purchase-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelDirectPurchaseTrx' => $modelDirectPurchaseTrx,
        'modelDirectPurchaseTrxs' => !empty($modelDirectPurchaseTrxs) ? $modelDirectPurchaseTrxs : NULL
    ]) ?>

</div>
