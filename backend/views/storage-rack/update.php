<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StorageRack */

$this->title = 'Update Storage Rack: ' . ' (' . $model->storage->nama_storage . ') ' . $model->nama_rak;
$this->params['breadcrumbs'][] = ['label' => 'Storage Racks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ' (' . $model->storage->nama_storage . ') ' . $model->nama_rak, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update'; ?>

<div class="storage-rack-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
