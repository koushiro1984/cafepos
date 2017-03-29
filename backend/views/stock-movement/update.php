<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StockMovement */

$this->title = 'Update Stock Movement: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-movement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
