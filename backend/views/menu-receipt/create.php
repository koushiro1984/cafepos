<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MenuReceipt */

$this->title = 'Create Menu Receipt';
$this->params['breadcrumbs'][] = ['label' => 'Menu Receipts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-receipt-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
