<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sale_invoice_correction_detail".
 *
 * @property string $id
 * @property string $sale_invoice_correction_id
 * @property string $menu_id
 * @property string $catatan
 * @property double $jumlah
 * @property string $discount_type
 * @property string $discount
 * @property string $harga
 * @property integer $is_void
 * @property string $void_at
 * @property string $user_void
 * @property integer $is_free_menu
 * @property string $free_menu_at
 * @property string $user_free_menu
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Menu $menu
 * @property User $userCreated
 * @property User $userUpdated
 * @property SaleInvoiceCorrection $saleInvoiceCorrection
 * @property User $userVoid
 * @property User $userFreeMenu
 */
class SaleInvoiceCorrectionDetail extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_invoice_correction_detail';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_invoice_correction_id'], 'required'],
            [['sale_invoice_correction_id', 'is_void', 'is_free_menu'], 'integer'],
            [['jumlah', 'discount', 'harga'], 'number'],
            [['catatan', 'discount_type'], 'string'],
            [['free_menu_at', 'void_at', 'created_at', 'updated_at'], 'safe'],
            [['menu_id', 'user_free_menu', 'user_void', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_invoice_correction_id' => 'Sale Invoice Correction ID',
            'menu_id' => 'Menu ID',
            'catatan' => 'Catatan',
            'jumlah' => 'Jumlah',
            'discount_type' => 'Discount Type',
            'discount' => 'Discount',
            'harga' => 'Harga',
            'is_void' => 'Is Void',
            'is_void' => 'Is Void',
            'void_at' => 'Void At',
            'is_free_menu' => 'Is Free Menu',
            'free_menu_at' => 'Free Menu At',
            'user_free_menu' => 'User Free Menu',
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
    public function getSaleInvoiceCorrection()
    {
        return $this->hasOne(SaleInvoiceCorrection::className(), ['id' => 'sale_invoice_correction_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVoid()
    {
        return $this->hasOne(User::className(), ['id' => 'user_void']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFreeMenu()
    {
        return $this->hasOne(User::className(), ['id' => 'user_free_menu']);
    }
}
