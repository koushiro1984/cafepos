<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MenuDiscount */

$this->title = 'Create Menu Discount';
$this->params['breadcrumbs'][] = ['label' => 'Menu Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-discount-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
