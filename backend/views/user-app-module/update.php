<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserAppModule */

$this->title = 'Update User App Module: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User App Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-app-module-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
