<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MtableOrder */

$this->title = 'Update Mtable Order: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mtable Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mtable-order-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
