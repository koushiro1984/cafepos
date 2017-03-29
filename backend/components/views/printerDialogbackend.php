<?php

use yii\helpers\Html; ?>



<applet id="qz" archive="<?= Yii::getAlias('@common-web') . '/js/plugins/qzprint/qz-print.jar' ?>" name="QZ Print Plugin" code="qz.PrintApplet.class" width="1" height="1">
<param name="jnlp_href" value="<?= Yii::getAlias('@common-web') . '/js/plugins/qzprint/qz-print_jnlp.jnlp' ?>">
<param name="cache_option" value="plugin">
<param name="disable_logging" value="false">
<param name="initial_focus" value="false">
</applet><br />

<div class="modal fade" id="modalPrinter" tabindex="-1" role="dialog">
    <div class="modal-dialog">                        
        <div class="box box-solid box-success">
            <div class="box-header">
                <h3 class="box-title">Printer</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-success btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    
                    <?php
                    foreach ($modelPrinter as $dataPrinter): ?>
                        
                        <div class="col-sm-4">
                            <?= Html::checkbox($dataPrinter['printer'], false, ['id' => $dataPrinter['printer'], 'class' => 'printerName', 'value' => $dataPrinter['printer']]) ?>
                            &nbsp;&nbsp;
                            <label class="control-label" for="<?= $dataPrinter['printer'] ?>"><?= $dataPrinter['printer'] ?></label>
                        </div>

                    <?php
                    endforeach; ?>
                    
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer" style="text-align: right">
                <button id="submitPrint" type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i> &nbsp;Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
            </div
        </div><!-- /.box -->
    </div>    
</div>

<?php
foreach ($modelPrinterKasir as $dataPrinterKasir) {
    echo Html::hiddenInput('printerKasir', $dataPrinterKasir['printer'], ['id' => 'printerKasir']);
} ?>

