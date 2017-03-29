<?php

use yii\helpers\Html; ?>



<applet id="qz" archive="<?= Yii::getAlias('@common-web') . '/js/plugins/qzprint/qz-print.jar' ?>" name="QZ Print Plugin" code="qz.PrintApplet.class" width="1" height="1">
<param name="jnlp_href" value="<?= Yii::getAlias('@common-web') . '/js/plugins/qzprint/qz-print_jnlp.jnlp' ?>">
<param name="cache_option" value="plugin">
<param name="disable_logging" value="false">
<param name="initial_focus" value="false">
</applet><br />

<div class="modal fade" id="modalPrinter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Printer</h4>
            </div>
            <div class="modal-body">                
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> &nbsp; Close</button>
                <button id="submitPrint" type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i> &nbsp;Print</button>
            </div>
        </div>
    </div>
</div>

<?php
foreach ($modelPrinterKasir as $dataPrinterKasir) {
    echo Html::hiddenInput('printerKasir', $dataPrinterKasir['printer'], ['id' => 'printerKasir']);
} ?>