<?php

use yii\imagine\Image; ?>

<!-- **********************************************************************************************************************************************************
MAIN SIDEBAR MENU
*********************************************************************************************************************************************************** -->
<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <p class="centered"><img src="<?= Yii::getAlias('@backend-web') . '/img/employee/thumb100x100' . Yii::$app->session->get('user_data')['employee']['image'] ?>" class="img-circle" width="90" height="90"></p>
            <h5 class="centered"><?= Yii::$app->session->get('user_data')['employee']['nama'] ?></h5>
            <h5 class="centered">( <?= Yii::$app->session->get('user_data')['user_level']['nama_level'] ?> )</h5>
            <p class="centered"><a href="<?= Yii::$app->urlManager->createUrl('site/logout'); ?>" data-method="post" class="btn btn-theme04" type="button">Logout</a></p>   
            
            <li class="mt">
                <a href="<?= Yii::$app->urlManager->createUrl('page/index'); ?>">
                    <i class="fa fa-coffee"></i>
                    <span>View Table</span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('page/index2'); ?>">
                    <i class="fa fa-coffee"></i>
                    <span>View Table (Layout)</span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('page/list-open-table'); ?>">
                    <i class="fa fa-cutlery"></i>
                    <span>List Opened Table</span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('page/menu-queue'); ?>">
                    <i class="fa fa-tasks"></i>
                    <span>Antrian Menu</span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('page/menu-queue-finished'); ?>">
                    <i class="fa fa-tasks"></i>
                    <span>Antrian Menu (Finished)</span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('page/reprint-faktur'); ?>">
                    <i class="fa fa-print"></i>
                    <span>Reprint Faktur</span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('page/koreksi-faktur-input'); ?>">
                    <i class="fa fa-pencil"></i>
                    <span>Koreksi Faktur</span>
                </a>
            </li>

            <?php
            if (!empty($this->params['tableInfo'])): 
                echo '<li class="mt">' . $this->params['tableInfo'] . '</li>';
            else: ?>

                <!--
                <li class="sub-menu">
                    <a href="javascript:;" >
                        <i class="fa fa-desktop"></i>
                        <span>AAAS</span>
                    </a>
                    <ul class="sub">
                        <li><a  href="general.html">BBC</a></li>
                        <li><a  href="buttons.html">CACM</a></li>
                        <li><a  href="panels.html">DARPA</a></li>
                    </ul>
                </li>
                -->
                
            <?php
            endif; ?>

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->