<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu_category".
 *
 * @property string $id
 * @property string $nama_category
 * @property string $parent_category_id
 * @property string $color
 * @property string $keterangan
 * @property integer $is_antrian
 * @property integer $not_active
 * @property integer $not_discount
 * @property string $printer
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Menu[] $menus
 * @property User $userCreated
 * @property User $userUpdated
 * @property MenuCategory $parentCategory
 * @property MenuCategory[] $menuCategories
 * @property Printer $printer0
 * @property MenuDiscount[] $menuDiscounts
 * @property MenuCategoryPrinter[] $menuCategoryPrinters
 */
class MenuCategory extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_category';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama_category'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['keterangan'], 'string'],
            [['is_antrian', 'not_active', 'not_discount'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'unique'],
            [['nama_category', 'printer'], 'string', 'max' => 128],
            [['id', 'parent_category_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['color'], 'string', 'max' => 7]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_category' => 'Nama Category',
            'parent_category_id' => 'Parent Category ID',
            'color' => 'Color',
            'keterangan' => 'Keterangan',
            'is_antrian' => 'Is Antrian',
            'not_active' => 'Not Active',
            'not_discount' => 'Not Discount',
            'printer' => 'Printer',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'parentCategory.nama_category' => 'Nama Parent Category'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['menu_category_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentCategory()
    {
        return $this->hasOne(MenuCategory::className(), ['id' => 'parent_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategories()
    {
        return $this->hasMany(MenuCategory::className(), ['parent_category_id' => 'id']);
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
    public function getMenuCategoryPrinters()
    {
        return $this->hasMany(MenuCategoryPrinter::className(), ['menu_category_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuDiscounts()
    {
        return $this->hasMany(MenuDiscount::className(), ['menu_category_id' => 'id']);
    }
}
