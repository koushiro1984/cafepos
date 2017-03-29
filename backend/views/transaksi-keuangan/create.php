<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TransaksiKeuangan */

$this->title = 'Transaksi Cash-In & Cash-Out';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Cash-In & Cash-Out', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-keuangan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
