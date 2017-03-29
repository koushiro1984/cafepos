<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "retur_sale".
 *
 * @property string $sale_invoice_detail_id
 * @property string $date
 * @property string $menu_id
 * @property double $jumlah
 * @property string $discount_type
 * @property string $discount
 * @property string $harga
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property SaleInvoiceDetail $saleInvoiceDetail
 * @property Menu $menu
 * @property User $userCreated
 * @property User $userUpdated
 */
class ReturSale extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'retur_sale';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_invoice_detail_id', 'date'], 'required'],
            [['sale_invoice_detail_id'], 'integer'],
            [['harga'], 'number', 'min' => 1],
            [['jumlah'], 'number', 'min' => 0.001],
            [['discount'], 'number', 'min' => 0],
            [['discount_type', 'keterangan'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['menu_id', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sale_invoice_detail_id' => 'Sale Invoice Detail ID',
            'date' => 'Date',
            'menu_id' => 'Menu ID',
            'jumlah' => 'Jumlah',
            'discount_type' => 'Discount Type',
            'discount' => 'Discount',
            'harga' => 'Harga',
            'keterangan' => 'Keterangan',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoiceDetail()
    {
        return $this->hasOne(SaleInvoiceDetail::className(), ['id' => 'sale_invoice_detail_id']);
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
}
