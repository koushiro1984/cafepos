<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Mtable */

$this->title = 'Update Table: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Table', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mtable-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
