<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StockMovement */

$this->title = 'Create Stock Movement';
$this->params['breadcrumbs'][] = ['label' => 'Stock Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-movement-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
