<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuSatuan */

$this->title = 'Update Menu Satuan: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Satuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-satuan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
