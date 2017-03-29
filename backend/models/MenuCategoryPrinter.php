<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu_category_printer".
 *
 * @property string $id
 * @property string $menu_category_id
 * @property string $printer
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property MenuCategory $menuCategory
 * @property Printer $printer0
 * @property User $userCreated
 * @property User $userUpdated
 */
class MenuCategoryPrinter extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_category_printer';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_category_id', 'printer'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['menu_category_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['printer'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_category_id' => 'Menu Category ID',
            'printer' => 'Printer',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory()
    {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrinter0()
    {
        return $this->hasOne(Printer::className(), ['printer' => 'printer']);
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
