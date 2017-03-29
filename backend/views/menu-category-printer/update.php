<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuCategoryPrinter */

$this->title = 'Update Menu Category Printer: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Category Printers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-category-printer-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
