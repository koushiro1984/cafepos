<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KodeTransaksi */

$this->title = 'Create Kode Transaksi';
$this->params['breadcrumbs'][] = ['label' => 'Kode Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kode-transaksi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
