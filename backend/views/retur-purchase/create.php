<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ReturPurchase */

$this->title = 'Create Retur Purchase';
$this->params['breadcrumbs'][] = ['label' => 'Retur Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="retur-purchase-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelReturPurchaseTrx' => $modelReturPurchaseTrx,
        'modelReturPurchaseTrxs' => !empty($modelReturPurchaseTrxs) ? $modelReturPurchaseTrxs : NULL
    ]) ?>

</div>
