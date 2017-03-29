<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\MtableOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mtable-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'mtable_session_id') ?>

    <?= $form->field($model, 'menu_id') ?>

    <?= $form->field($model, 'catatan') ?>

    <?= $form->field($model, 'discount_type') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'harga_satuan') ?>

    <?php // echo $form->field($model, 'jumlah') ?>

    <?php // echo $form->field($model, 'is_free_menu') ?>

    <?php // echo $form->field($model, 'is_void') ?>

    <?php // echo $form->field($model, 'void_at') ?>

    <?php // echo $form->field($model, 'user_void') ?>

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
