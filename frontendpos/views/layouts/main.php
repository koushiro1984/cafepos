<?php
use yii\helpers\Html;
use frontendpos\assets\AppAsset;
use frontendpos\components\AppHeader;
use backend\models\Settings;

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
                
        if (isset($this->params['regCssFile'])) {
            foreach ($this->params['regCssFile'] as $value) {
                $value();
            }
        } ?>  
    </head>

    <body>
        <?php $this->beginBody() ?>

        <section id="container" >
            <?php 
            $header = new AppHeader(); 
            echo $header->header();
            echo $header->navigation(); ?>            

            <!-- **********************************************************************************************************************************************************
            MAIN CONTENT
            *********************************************************************************************************************************************************** -->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">                                        
                    <div class="row">
                        <?= $content ?>                       
                    </div><! --/row -->
                </section>
            </section>
            <!--main content end-->

        </section>                
        
        <?php 
        $this->endBody();
        
        $setting = Settings::findOne(['setting_name' => 'auto_fullscreen']);
        if ($setting['setting_value']) {
            $this->registerJs('
                var requestFullScreen = function(element) {
                    // Supports most browsers and their versions.
                    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;

                    if (requestMethod) { // Native full screen.
                        requestMethod.call(element);
                    } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                        var wscript = new ActiveXObject("WScript.Shell");
                        if (wscript !== null) {
                            wscript.SendKeys("{F11}");
                        }
                    }
                };
                
                var elem = document.body; // Make the body go full screen.
                requestFullScreen(elem);
            ');
        }
        
        if (isset($this->params['regJsFile'])) {
            foreach ($this->params['regJsFile'] as $value) {
                $value();
            }
        } ?>	
    </body>
</html>
<?php $this->endPage() ?>
