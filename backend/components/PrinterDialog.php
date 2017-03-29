<?php

namespace backend\components;

use yii\base\Widget;
use yii\web\View;
use backend\models\Printer;

class PrinterDialog extends Widget {
    
    public $status = '';
    public $message1 = '';
    public $message2 = '';
    
    public function theScript() {
        $jscript = '            
                        
            var isLoaded = function() {
                if (!qz) {
                    alert("Error:\n\n\tPrint plugin is NOT loaded!");
                    return false;
                } else {
                    try {
                        if (!qz.isActive()) {
                            alert("Error:\n\n\tPrint plugin is loaded but NOT active!");
                            return false;
                        }
                    } catch (err) {
                        alert("Error:\n\n\tPrint plugin is NOT loaded properly!");
                        return false;
                    }
                }
                return true;
            };

            var showModalPrinter = function(text, openCashdrawer, otherFunction) {
                //alert(text); return;
                
                $("#modalPrinter").modal();        

                $("#modalPrinter #submitPrint").on("click", function(event) {
                    if (isLoaded()) {
                        $("#modalPrinter .printerName").each(function() {
                            if ($(this).is(":checked")) {
                                if ($(this).val() != "") {
                                    qz.findPrinter($(this).val());

                                    while(!qz.isDoneFinding()){}

                                    qz.append(text);

                                    if (openCashdrawer !== undefined && openCashdrawer) {
                                        //qz.append(chr(27) + "\x70" + "\x30" + chr(25) + chr(25));
                                        qz.append(chr(27) + chr(112) + chr(48) + chr(25) + chr(250));
                                    }

                                    qz.print();     

                                    while(!qz.isDonePrinting()){}                                
                                }
                            }
                        });
                    }
                    
                    if (otherFunction !== undefined) {
                        otherFunction();
                    }

                    $("#modalPrinter .printerName").each(function() {
                        $(this).iCheck("uncheck");
                    });

                    $("#modalPrinter #submitPrint").off("click");
                });
            };
            
            var printContent = function(header, footer, content, openCashdrawer, otherFunction) {
                var printer;
                
                /*
                for (printer in content) {                    
                    alert(header + content[printer] + footer); 
                }
                return;     
                */                
                
                if (isLoaded()) {
                    
                    for (printer in content) {
                        if (printer != "") {
                            qz.findPrinter(printer);        
                            
                            while(!qz.isDoneFinding()){}

                            qz.append(header + content[printer] + footer);

                            if (openCashdrawer !== undefined && openCashdrawer) {                                
                                //qz.append(chr(27) + "\x70" + "\x30" + chr(25) + chr(25));
                                qz.append(chr(27) + chr(112) + chr(48) + chr(25) + chr(250));
                            }

                            qz.print();

                            while(!qz.isDonePrinting()){}
                        }
                    }                                             
                }
                
                if (otherFunction !== undefined) {
                    otherFunction();
                }
            };
            
            var chr = function(i) {
                return String.fromCharCode(i);
            };

            var separatorPrint = function(length, char) {
                var separator = "";   
                for (i = 0; i < length; i++) {
                    if (char) {
                        separator += char;
                    } else {
                        separator += " ";
                    }
                }

                return separator;
            };
        ';
        
        $this->getView()->registerJs($jscript);  
        
        /*
        $this->getView()->registerJs('
            var qzReady = function() {
                //qz.allowMultipleInstances(true);
            };
            
            var qzDonePrinting = function() {
                //qz.findPrinter("Generic / Text Only");
                //alert("asdasd");
            };
        ', View::POS_HEAD);
         * 
         */
        
    }
    
    public function onHidden($script) {
        $jscript = '
            $("#modalPrinter").on("hidden.bs.modal", function(event) {
                ' . $script . '
            });
        ';
        
        $this->getView()->registerJs($jscript);
    }
    
    public function renderDialog($type) {
        
        $modelPrinter = Printer::find()
            ->andWhere(['not_active' => false])
            ->asArray()->all();
        
        $modelPrinterKasir = Printer::find()
            ->andWhere(['type' => 'cashier'])
            ->andWhere(['not_active' => false])
            ->asArray()->all();
        
        return $this->render('printerDialog' . $type, [
            'modelPrinter' => $modelPrinter,
            'modelPrinterKasir' => $modelPrinterKasir,
        ]);
    }
}
