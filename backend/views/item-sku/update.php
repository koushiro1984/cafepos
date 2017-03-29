<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ItemSku */

$this->title = 'Update Item Sku: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Item Skus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-sku-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
