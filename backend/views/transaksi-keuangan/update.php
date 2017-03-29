<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TransaksiKeuangan */

$this->title = 'Transaksi Cash-In & Cash-Out: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Cash-In & Cash-Out', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaksi-keuangan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
