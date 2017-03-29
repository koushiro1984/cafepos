<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Voting */

$this->title = 'Update Voting: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Votings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="voting-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
