<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MenuCategory */

$this->title = 'Create Menu Category';
$this->params['breadcrumbs'][] = ['label' => 'Menu Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
