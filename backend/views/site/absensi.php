<?php
use yii\helpers\Html;
use backend\components\NotificationDialog;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$status = Yii::$app->session->getFlash('status');
$message1 = Yii::$app->session->getFlash('message1');
$message2 = Yii::$app->session->getFlash('message2');

if ($status !== null) : 
    $notif = new NotificationDialog([
        'status' => $status,
        'message1' => $message1,
        'message2' => $message2,
    ]);

    $notif->theScript();
    $notif->onHidden('
        $("input#idAbsen").focus();
    ');
    echo $notif->renderDialog();

endif;

$this->title = 'Login'; 

$settings_company_profile = Yii::$app->session->get('company_settings_profile'); ?>

<div class="logo" style="width: 240px; height: 180px">
     <img src="<?= Yii::$app->request->baseUrl . '/img/company-profile/' . $settings_company_profile['company_image_file'] ?>">
</div>
<div class="form-box" id="login-box">
    <div class="header"><b>ABSENSI</b></div>
    <?= Html::beginForm('', 'post', ['id' => 'absenForm']); ?>    
        <div class="body bg-white">
            <div class="form-group">
                <?= Html::passwordInput('idAbsen', null, ['id' => 'idAbsen', 'class' => 'form-control', 'placeholder' => 'Scan ID']) ?>
            </div>
        </div>
        <div class="footer">           
            <div class="row">
                <div class="col-lg-6">
                    <?= Html::submitButton('<i class="fa fa-arrow-circle-right"></i> Check In', ['class' => 'btn bg-success btn-block', 'name' => 'checkin', 'value' => 'checkin']) ?>                                          
                </div>
                <div class="col-lg-6">
                    <?= Html::submitButton('<i class="fa fa-arrow-circle-up"></i> Check Out', ['class' => 'btn bg-danger btn-block', 'name' => 'checkout', 'value' => 'checkout']) ?>                                          
                </div>
            </div>
        </div>
    <?= Html::endForm(); ?>
</div>

<?php
$jscript = '
    $("body").addClass("login-bg");
    $("input#idAbsen").focus();
    $("input#idAbsen").val("");
    
    $("input#idAbsen").keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    
';

$this->registerJs($jscript); ?>
