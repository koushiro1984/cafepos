<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\SupplierDeliveryTrxSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-delivery-trx-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplier_delivery_id') ?>

    <?= $form->field($model, 'purchase_order_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_sku_id') ?>

    <?php // echo $form->field($model, 'jumlah_order') ?>

    <?php // echo $form->field($model, 'jumlah_terima') ?>

    <?php // echo $form->field($model, 'harga_satuan') ?>

    <?php // echo $form->field($model, 'jumlah_harga') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'user_created') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'user_updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
