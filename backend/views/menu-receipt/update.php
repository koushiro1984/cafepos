<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuReceipt */

$this->title = 'Update Menu Receipt: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Receipts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-receipt-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
