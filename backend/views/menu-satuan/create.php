<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MenuSatuan */

$this->title = 'Create Menu Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Menu Satuans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-satuan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
