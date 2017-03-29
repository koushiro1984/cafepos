<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name; 

$strH1 = '';

switch ($exception->statusCode) {
    case 403:
        $strH1 = 'Maaf Anda tidak berkewenangan untuk mengakses data ini.';
        break;
    case 404:
        $strH1 = 'Data yang Anda cari tidak ada.';
        break;
    case 405:
        $strH1 = 'Terdapat kesalahan dalam proses penginputan data.';
        break;
} ?>


<div class="site-error">

    <div class="alert alert-danger">
        <h1><?= $strH1 ?></h1>   
    </div>
    
    <p>
        <?= Html::a('Home.', (!empty(Yii::$app->session->get('user_data')['user_level']['default_action']) ? Yii::$app->session->get('user_data')['user_level']['default_action'] : Yii::$app->getHomeUrl())); ?>
    </p>

</div>
