<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_akses".
 *
 * @property string $id
 * @property string $user_level_id
 * @property string $user_app_module_id
 * @property integer $is_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property UserLevel $userLevel
 * @property UserAppModule $userAppModule
 * @property User $userCreated
 * @property User $userUpdated
 */
class UserAkses extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_akses';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_level_id'], 'required'],
            [['user_level_id', 'user_app_module_id', 'is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_level_id' => 'User Level ID',
            'user_app_module_id' => 'User App Module ID',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevel()
    {
        return $this->hasOne(UserLevel::className(), ['id' => 'user_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAppModule()
    {
        return $this->hasOne(UserAppModule::className(), ['id' => 'user_app_module_id']);
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
}
