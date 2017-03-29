<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StorageRack */

$this->title = 'Create Storage Rack';
$this->params['breadcrumbs'][] = ['label' => 'Storage Racks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="storage-rack-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
