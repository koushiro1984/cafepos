<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property string $id
 * @property string $nama_menu
 * @property string $menu_category_id
 * @property string $menu_satuan_id
 * @property string $keterangan
 * @property integer $not_active
 * @property integer $can_edit_price
 * @property integer $not_ppn_sc
 * @property double $harga_pokok
 * @property double $biaya_lain
 * @property double $harga_jual
 * @property string $image
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 * @property MenuSatuan $menuSatuan
 * @property MenuCategory $menuCategory
 * @property MenuDiscount[] $menuDiscounts
 * @property MenuReceipt[] $menuReceipts
 * @property SaleInvoiceDetail[] $saleInvoiceDetails
 * @property SaleOrderDetail[] $saleOrderDetails
 */
class Menu extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama_menu', 'menu_category_id', 'menu_satuan_id'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['keterangan', 'image'], 'string'],
            [['not_active', 'can_edit_price', 'not_ppn_sc'], 'integer'],
            [['harga_jual'], 'number', 'min' => 0],
            [['harga_pokok', 'biaya_lain'], 'number', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['nama_menu'], 'string', 'max' => 128],
            [['id', 'menu_category_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['menu_satuan_id'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_menu' => 'Nama Menu',
            'menu_category_id' => 'Menu Category ID',
            'menu_satuan_id' => 'Menu Satuan ID',
            'keterangan' => 'Keterangan',
            'not_active' => 'Not Active',
            'can_edit_price' => 'Can Edit Price',
            'not_ppn_sc' => 'Not Ppn Sc',
            'harga_pokok' => 'Harga Pokok',
            'biaya_lain' => 'Biaya Lain',
            'harga_jual' => 'Harga Jual',
            'image' => 'Image',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuSatuan()
    {
        return $this->hasOne(MenuSatuan::className(), ['id' => 'menu_satuan_id']);
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
    public function getMenuDiscounts()
    {
        return $this->hasMany(MenuDiscount::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuReceipts()
    {
        return $this->hasMany(MenuReceipt::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoiceDetails()
    {
        return $this->hasMany(SaleInvoiceDetail::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrderDetails()
    {
        return $this->hasMany(SaleOrderDetail::className(), ['menu_id' => 'id']);
    }
}
