<?php

use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <!-- Favicon -->
        <link rel="icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>" type="image/x-icon">
        <link rel="apple-touch-icon" href="<?= Yii::$app->request->baseUrl . '/favicon.png' ?>">
        
        <title><?= Html::encode(Yii::$app->name) . ' - ' . Html::encode($this->title) ?></title>
        <?php 
        $this->head();
        $this->registerCssFile(Yii::getAlias('@common-web') . '/css/font-awesome.min.css');
        $this->registerCssFile(Yii::getAlias('@common-web') . '/css/ionicons.min.css');
        
        if (isset($this->params['regCssFile'])) {
            foreach ($this->params['regCssFile'] as $value) {
                $value();
            }
        } ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        
        <div class="wrap">
            <div class="container">
                <?= $content ?>
            </div>
        </div>       
            
        <?php 
        $this->endBody(); 
        
        if (isset($this->params['regJsFile'])) {
            foreach ($this->params['regJsFile'] as $value) {
                $value();
            }
        } ?>
    </body>
</html>
<?php $this->endPage() ?>
