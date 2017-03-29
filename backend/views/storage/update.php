<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Storage */

$this->title = 'Update Storage: ' . ' (' . $model->id . ') ' . $model->nama_storage;
$this->params['breadcrumbs'][] = ['label' => 'Storages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ' (' . $model->id . ') ' . $model->nama_storage, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="storage-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
