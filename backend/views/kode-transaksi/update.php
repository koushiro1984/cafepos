<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KodeTransaksi */

$this->title = 'Update Kode Transaksi: ' . ' ' . $model->account_id;
$this->params['breadcrumbs'][] = ['label' => 'Kode Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->account_id, 'url' => ['view', 'id' => $model->account_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kode-transaksi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
