<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login'; 

$settings_company_profile = Yii::$app->session->get('company_settings_profile'); ?>

<div class="logo" style="width: 240px; height: 180px">
     <img src="<?= Yii::$app->request->baseUrl . '/img/company-profile/' . $settings_company_profile['company_image_file'] ?>">
</div>
<div class="form-box" id="login-box">
    <div class="header">Login To <b><?= Html::encode(Yii::$app->name) ?></b></div>
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>    
        <div class="body bg-white">
            <?= $form->field($model, 'username')->textInput(['id' => 'username']) ?>
            <?= $form->field($model, 'password')->passwordInput(['id' => 'password']) ?>
        </div>
        <div class="footer">                                                               
            <?= Html::submitButton('Login', ['class' => 'btn bg-red btn-block', 'name' => 'login-button']) ?>                                          
        </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$this->params['regCssFile'][] = function() {
    $this->registerCssFile(Yii::getAlias('@common-web') . '/css/keyboard/keyboard.min.css');
}; 

$this->params['regJsFile'][] = function() {
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/keyboard/js/jquery.keyboard.min.js');
    $this->registerJsFile(Yii::getAlias('@common-web') . '/js/plugins/keyboard/js/jquery.keyboard.extension-typing.min.js');
};

$css = '
    .btn-xl {
        padding: 10px 16px;
        font-size: 20px;
        line-height: 1.33;
        border-radius: 6px;
    }

    .keyboard-os {
        position: absolute;
        top: 100%;
        left: 0px;
        z-index: 1999;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0px;
        margin: 2px 0px 0px;
        font-size: 14px;
        text-align: left;
        list-style: outside none none;
        background-color: #FFF;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.176);
    }
';

$this->registerCss($css);

$jscript = '
    $("body").addClass("login-bg");
    
    $("#username").keyboard({
        openOn   : null,                        
        layout   : "qwerty",
        css: {
            input: "form-control input-sm",
            container: "center-block keyboard-os",
            buttonDefault: "btn btn-default btn-xl",
            buttonHover: "btn-primary",
            buttonAction: "active",
            buttonDisabled: "disabled"
        }
    }).focusin(function(event){
        var kb = $(this).getkeyboard();
        if (kb.isOpen) {
            kb.close();
        } else {
            kb.reveal();
        }
    });
    
    $("#password").keyboard({
        openOn   : null,                        
        layout   : "qwerty",
        css: {
            input: "form-control input-sm",
            container: "center-block keyboard-os",
            buttonDefault: "btn btn-default btn-xl",
            buttonHover: "btn-primary",
            buttonAction: "active",
            buttonDisabled: "disabled"
        }
    }).focusin(function(event){
        var kb = $(this).getkeyboard();
        if (kb.isOpen) {
            kb.close();
        } else {
            kb.reveal();
        }
    });';
   
$this->registerJs($jscript); ?>
