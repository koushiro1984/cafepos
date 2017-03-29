<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu_discount".
 *
 * @property string $id
 * @property string $type
 * @property string $menu_id
 * @property string $menu_category_id
 * @property string $discount_type
 * @property double $jumlah_discount
 * @property string $start_date
 * @property string $end_date
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Menu $menu
 * @property MenuCategory $menuCategory
 * @property User $userCreated
 * @property User $userUpdated
 */
class MenuDiscount extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_discount';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'discount_type'], 'string'],
            [['jumlah_discount'], 'number', 'min' => 0],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['menu_id', 'menu_category_id', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'menu_id' => 'Menu ID',
            'menu_category_id' => 'Menu Category ID',
            'discount_type' => 'Discount Type',
            'jumlah_discount' => 'Jumlah Discount',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
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
