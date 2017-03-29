<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DirectPurchaseTrx */

$this->title = 'Create Direct Purchase Trx';
$this->params['breadcrumbs'][] = ['label' => 'Direct Purchase Trxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direct-purchase-trx-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
