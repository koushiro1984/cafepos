<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SupplierDeliveryInvoice */

$this->title = 'Registrasi Invoice Penerimaan Item';
$this->params['breadcrumbs'][] = ['label' => 'Invoice Penerimaan Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-delivery-invoice-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
