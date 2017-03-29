<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DirectPurchase */

$this->title = 'Create Direct Purchase';
$this->params['breadcrumbs'][] = ['label' => 'Direct Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direct-purchase-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelDirectPurchaseTrx' => $modelDirectPurchaseTrx,
        'modelDirectPurchaseTrxs' => !empty($modelDirectPurchaseTrxs) ? $modelDirectPurchaseTrxs : NULL
    ]) ?>

</div>
