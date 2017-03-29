<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MtableCategory */

$this->title = 'Create Mtable Category';
$this->params['breadcrumbs'][] = ['label' => 'Mtable Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mtable-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
