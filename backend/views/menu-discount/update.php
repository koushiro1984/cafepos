<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuDiscount */

$this->title = 'Update Menu Discount: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-discount-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
