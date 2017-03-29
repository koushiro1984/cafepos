<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Absensi */

$this->title = 'Update Absensi: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="absensi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
