<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ReturPurchaseTrx */

$this->title = 'Create Retur Purchase Trx';
$this->params['breadcrumbs'][] = ['label' => 'Retur Purchase Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="retur-purchase-trx-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
