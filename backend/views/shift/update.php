<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Shift */

$this->title = 'Update Shift: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shift-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
