<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "purchase_order_trx".
 *
 * @property string $id
 * @property string $purchase_order_id
 * @property string $item_id
 * @property string $item_sku_id
 * @property double $jumlah_order
 * @property double $jumlah_terima
 * @property string $harga_satuan
 * @property string $jumlah_harga
 * @property integer $is_closed
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property PurchaseOrder $purchaseOrder
 * @property Item $item
 * @property ItemSku $itemSku
 * @property User $userCreated
 * @property User $userUpdated
 * @property SupplierDeliveryTrx[] $supplierDeliveryTrxes
 */
class PurchaseOrderTrx extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_trx';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_id', 'item_id', 'item_sku_id', 'jumlah_order'], 'required'],
            [['harga_satuan', 'jumlah_harga'], 'number', 'min' => 1],
            [['jumlah_order', 'jumlah_terima'], 'number', 'min' => 0.001],
            [['is_closed'], 'integer'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['purchase_order_id'], 'string', 'max' => 13],
            [['item_id', 'item_sku_id'], 'string', 'max' => 16],
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
            'purchase_order_id' => 'Purchase Order ID',
            'item_id' => 'Item ID',
            'item_sku_id' => 'Item Sku ID',
            'jumlah_order' => 'Jumlah Order',
            'jumlah_terima' => 'Jumlah Terima',
            'harga_satuan' => 'Harga Satuan',
            'jumlah_harga' => 'Jumlah Harga',
            'is_closed' => 'Is Closed',
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
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(), ['id' => 'purchase_order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemSku()
    {
        return $this->hasOne(ItemSku::className(), ['id' => 'item_sku_id']);
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
    public function getSupplierDeliveryTrxes()
    {
        return $this->hasMany(SupplierDeliveryTrx::className(), ['purchase_order_trx_id' => 'id']);
    }
}
