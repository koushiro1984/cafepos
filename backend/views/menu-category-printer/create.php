<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MenuCategoryPrinter */

$this->title = 'Create Menu Category Printer';
$this->params['breadcrumbs'][] = ['label' => 'Menu Category Printers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-category-printer-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
