<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SaleInvoicePayment */

$this->title = 'Create Sale Invoice Payment';
$this->params['breadcrumbs'][] = ['label' => 'Sale Invoice Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-invoice-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
