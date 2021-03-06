<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Printer */

$this->title = 'Update Printer: ' . ' ' . $model->printer;
$this->params['breadcrumbs'][] = ['label' => 'Printers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->printer, 'url' => ['view', 'id' => $model->printer]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="printer-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
