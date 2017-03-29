<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\components\AppHeader;

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
        
        <title><?= Html::encode($this->title) ?></title>
        <?php 
        $this->head();
        if (isset($this->params['regCssFile'])) {
            foreach ($this->params['regCssFile'] as $value) {
                $value();
            }
        } ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="loading-screen">
            <div class="content animated fadeInDown">
                <i class="fa fa-circle-o-notch fa-spin"></i>
                <h2 class="section-heading">Grill the page</h2>
                <h3 class="section-subheading">Please wait...</h3>
            </div>
        </div>

        <?php 
        $header = new AppHeader(); 
        echo $header->header();
        echo $header->navigation(); ?>

        <?= $content ?>

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
