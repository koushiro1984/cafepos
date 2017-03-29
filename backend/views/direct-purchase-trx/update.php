<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DirectPurchaseTrx */

$this->title = 'Update Direct Purchase Trx: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Direct Purchase Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="direct-purchase-trx-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
