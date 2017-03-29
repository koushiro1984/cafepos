<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Mtable */

$this->title = 'Create Mtable';
$this->params['breadcrumbs'][] = ['label' => 'Table', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mtable-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
