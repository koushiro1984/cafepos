<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = 'Update Menu: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelMenuReceipt' => $modelMenuReceipt,
        'modelMenuReceipts' => !empty($modelMenuReceipts) ? $modelMenuReceipts : NULL
    ]) ?>

</div>
