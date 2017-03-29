<?php

use yii\helpers\Html; ?>


<!-- **********************************************************************************************************************************************************
TOP BAR CONTENT & NOTIFICATIONS
*********************************************************************************************************************************************************** -->
<!--header start-->
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <!--logo start-->
    <a href="#" class="logo">
        <b><?= Html::encode(Yii::$app->name) ?></b>        
    </a>
    <!--logo end-->        
	<div class="navbar-right">
        <ul class="nav navbar-nav">      
            <li>
                <a href="javascript:;" id="bardatetime">
                    Date Time
                </a>                    
            </li>
        </ul>
    </div>
</header>
<!--header end-->

<?php
$jscript = '    
    var datetimeStatus = function() {  
        var date = 0;
        var time = 0;
        $.when(
            $.ajax({
                type: "GET",
                url: "' . Yii::$app->urlManager->createUrl(['site/get-datetime']) . '",            
                success: function(data) {
                    date = data.date;
                    time = data.time
                }
            })
        ).done(function() {
            $("a#bardatetime").html("").append(date).append("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;").append(time);
        });
    };
    
    datetimeStatus();
    
    setInterval(function () {
        datetimeStatus();
    }, 1000 * 60);
';

$this->registerJs($jscript); ?>