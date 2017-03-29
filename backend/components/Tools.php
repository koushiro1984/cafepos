<?php

namespace backend\components;

use Yii;
use backend\models\Settings;


class Tools {

    public static function array_sort($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    
    public static function hitungServiceChargePajak($totalHarga, $persenServiceCharge, $persenPajak) {
        $modelSettingTaxIncludeServiceCharge = Settings::find()        
            ->andWhere(['like', 'setting_name', 'tax_include_service_charge'])
            ->one();
        
        $serviceCharge = round($totalHarga * $persenServiceCharge / 100);
        
        $pajak = 0;
        
        if ($modelSettingTaxIncludeServiceCharge->setting_value)
            $pajak = round(($totalHarga + $serviceCharge) * $persenPajak / 100);
        else
            $pajak = round($totalHarga * $persenPajak / 100);
        
        $arr['serviceCharge'] = $serviceCharge;
        $arr['pajak'] = $pajak;
        
        return $arr;
    }
    
    public static function getServiceChargePajak() {
        $modelSettingTaxServiceCharge = Settings::find()        
            ->orWhere(['like', 'setting_name', 'tax_amount'])
            ->orWhere(['like', 'setting_name', 'service_charge_amount'])
            ->asArray()->all();
        
        $arr = [];
        foreach ($modelSettingTaxServiceCharge as $value) {
            if ($value['setting_name'] == 'tax_amount')
                $arr['pajak'] = $value['setting_value'];
            else if ($value['setting_name'] == 'service_charge_amount')
                $arr['serviceCharge'] = $value['setting_value'];
        }
        
        return $arr;
    }
    
    public static function jsHitungServiceChargePajak() {
        $modelSettingTaxIncludeServiceCharge = Settings::find()        
            ->andWhere(['like', 'setting_name', 'tax_include_service_charge'])
            ->one();        
        
        $pajak = '';
        if ($modelSettingTaxIncludeServiceCharge->setting_value)
            $pajak = 'var pajakVal = Math.round((parseFloat(totalHarga) + serviceChargeVal) * parseFloat(persenPajak) / 100);';
        else
            $pajak = 'var pajakVal = Math.round(parseFloat(totalHarga) * parseFloat(persenPajak) / 100);';
        
        $jscript = '
            var hitungServiceChargePajak = function(totalHarga, persenCharge, persenPajak) {
                var serviceChargeVal = Math.round(parseFloat(totalHarga) * parseFloat(persenCharge) / 100);' .
                $pajak . '
                
                var arr = {serviceCharge: serviceChargeVal, pajak: pajakVal};
                
                return arr;
            };
        ';
        return $jscript;
    }
    
    public static function convertToCurrency($value, $isConvert = true) {
        if ($isConvert)
            return Yii::$app->formatter->asCurrency($value);
        else 
            return $value;
    }
}
