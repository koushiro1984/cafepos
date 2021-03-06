<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property string $setting_id
 * @property string $setting_name
 * @property string $setting_value
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 */
class Settings extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }        
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_name'], 'required'],
            [['setting_value'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['setting_name'], 'unique'],
            [['setting_name'], 'string', 'max' => 96],
            [['setting_name'], 'backend\components\String2Validator'],
            [['user_created', 'user_updated'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'setting_name' => 'Setting Name',
            'setting_value' => 'Setting Value',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreated()
    {
        return $this->hasOne(User::className(), ['id' => 'user_created']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdated()
    {
        return $this->hasOne(User::className(), ['id' => 'user_updated']);
    }
    
    public static function getTransNumber($trans) {
        $models = Settings::find()->where(['setting_name' => $trans])->orWhere(['setting_name' => $trans . '_format'])->all();
        
        $number = '';
        $format = '';
        foreach ($models as $model) {
            if (stripos($model->setting_name, '_format') !== false)
                $format = explode(':', $model->setting_value);
            else
                $number = $model;            
        }
        
        $index = '';
        $zero = '';        
        for ($i = 1; $i <= $format[1]; $i++) {
            $zero .= '0';
        }
        
        $index = substr($zero, 0, ($format[1] - strlen($number->setting_value))) . $number->setting_value;
        
        $noTrans = $format[0];
        $noTrans = str_replace('{date}', date('ym'), $noTrans);
        $noTrans = str_replace('{inc}', $index, $noTrans);
        
        $number->setting_value = $number->setting_value + 1;          
        if ($number->save()) {        
            return $noTrans;
        } else {
            return false;
        }
    }
    
    public static function getSettings($params) {
        $settingName = '';
        foreach ($params as $value) {
            $settingName .= '"' . $value . '",';
        }
        $settingName = trim($settingName, ',');
        
        $query = Settings::find()
                ->andWhere('setting_name IN (' . $settingName . ')')
                ->asArray()->all();
        
        return $query;
    }
}
