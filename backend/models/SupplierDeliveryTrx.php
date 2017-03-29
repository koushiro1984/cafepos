<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "supplier_delivery_trx".
 *
 * @property string $id
 * @property string $supplier_delivery_id
 * @property string $purchase_order_id
 * @property string $purchase_order_trx_id
 * @property string $item_id
 * @property string $item_sku_id
 * @property string $storage_id
 * @property string $storage_rack_id
 * @property double $jumlah_order
 * @property double $jumlah_terima
 * @property string $harga_satuan
 * @property string $jumlah_harga
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property ReturPurchaseTrx[] $returPurchaseTrxes
 * @property Storage $storage
 * @property StorageRack $storageRack
 * @property Item $item
 * @property ItemSku $itemSku
 * @property User $userCreated
 * @property User $userUpdated
 * @property SupplierDelivery $supplierDelivery
 * @property PurchaseOrder $purchaseOrder
 * @property PurchaseOrderTrx $purchaseOrderTrx
 */
class SupplierDeliveryTrx extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_delivery_trx';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_delivery_id', 'purchase_order_id', 'purchase_order_trx_id', 'item_id', 'item_sku_id', 'storage_id', 'jumlah_terima'], 'required'],
            [['purchase_order_trx_id', 'storage_rack_id'], 'integer'],
            [['harga_satuan', 'jumlah_harga'], 'number', 'min' => 1],
            [['jumlah_order', 'jumlah_terima'], 'number', 'min' => 0.001],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['supplier_delivery_id', 'purchase_order_id'], 'string', 'max' => 13],
            [['item_id', 'item_sku_id'], 'string', 'max' => 16],
            [['storage_id'], 'string', 'max' => 12],
            [['user_created', 'user_updated'], 'string', 'max' => 32],
            
            [['purchaseOrderTrx_is_closed'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['purchaseOrderTrx_is_closed']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_delivery_id' => 'Supplier Delivery ID',
            'purchase_order_id' => 'Purchase Order ID',
            'purchase_order_trx_id' => 'Purchase Order Trx ID',
            'item_id' => 'Item ID',
            'item_sku_id' => 'Item Sku ID',
            'storage_id' => 'Storage ID',
            'storage_rack_id' => 'Storage Rack ID',
            'jumlah_order' => 'Jumlah Order',
            'jumlah_terima' => 'Jumlah Terima',
            'harga_satuan' => 'Harga Satuan',
            'jumlah_harga' => 'Jumlah Harga',
            'keterangan' => 'Keterangan',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'purchaseOrderTrx_is_closed' => 'Close Purchase Order'
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturPurchaseTrxes()
    {
        return $this->hasMany(ReturPurchaseTrx::className(), ['supplier_delivery_trx_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorage()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageRack()
    {
        return $this->hasOne(StorageRack::className(), ['id' => 'storage_rack_id']);
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
    public function getSupplierDelivery()
    {
        return $this->hasOne(SupplierDelivery::className(), ['id' => 'supplier_delivery_id']);
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
    public function getPurchaseOrderTrx()
    {
        return $this->hasOne(PurchaseOrderTrx::className(), ['id' => 'purchase_order_trx_id']);
    }
}
