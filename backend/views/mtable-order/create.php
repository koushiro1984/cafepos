<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MtableOrder */

$this->title = 'Create Mtable Order';
$this->params['breadcrumbs'][] = ['label' => 'Mtable Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mtable-order-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
