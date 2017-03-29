<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MtableCategory */

$this->title = 'Update Mtable Category: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mtable Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mtable-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
