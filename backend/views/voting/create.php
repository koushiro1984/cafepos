<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Voting */

$this->title = 'Create Voting';
$this->params['breadcrumbs'][] = ['label' => 'Votings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voting-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
