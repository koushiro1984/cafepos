<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ItemSku */

$this->title = 'Create Item Sku';
$this->params['breadcrumbs'][] = ['label' => 'Item Skus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-sku-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
