<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "printer".
 *
 * @property string $printer
 * @property string $type
 * @property integer $not_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property MenuCategory[] $menuCategories
 * @property User $userCreated
 * @property User $userUpdated
 */
class Printer extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'printer';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['printer'], 'required'],
            [['printer'], 'unique'],
            [['type'], 'string'],
            [['not_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['printer'], 'string', 'max' => 128],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'printer' => 'Printer',
            'type' => 'Type',
            'not_active' => 'Not Active',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategories()
    {
        return $this->hasMany(MenuCategory::className(), ['printer' => 'printer']);
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
