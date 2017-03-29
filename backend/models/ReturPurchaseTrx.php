<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "retur_purchase_trx".
 *
 * @property string $id
 * @property string $retur_purchase_id
 * @property string $supplier_delivery_id
 * @property string $supplier_delivery_trx_id
 * @property string $item_id
 * @property string $item_sku_id
 * @property string $storage_id
 * @property string $storage_rack_id
 * @property double $jumlah_item
 * @property string $harga_satuan
 * @property string $jumlah_harga
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property ReturPurchase $returPurchase
 * @property Item $item
 * @property ItemSku $itemSku
 * @property Storage $storage
 * @property StorageRack $storageRack
 * @property User $userCreated
 * @property User $userUpdated
 * @property SupplierDelivery $supplierDelivery
 * @property SupplierDeliveryTrx $supplierDeliveryTrx
 */
class ReturPurchaseTrx extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'retur_purchase_trx';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['retur_purchase_id', 'supplier_delivery_id', 'supplier_delivery_trx_id', 'item_id', 'item_sku_id', 'storage_id'], 'required'],
            [['supplier_delivery_trx_id', 'storage_rack_id'], 'integer'],
            [['harga_satuan', 'jumlah_harga'], 'number', 'min' => 1],
            [['jumlah_item'], 'number', 'min' => 0.001],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['retur_purchase_id', 'supplier_delivery_id'], 'string', 'max' => 13],
            [['item_id', 'item_sku_id'], 'string', 'max' => 16],
            [['storage_id'], 'string', 'max' => 12],
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
            'retur_purchase_id' => 'Retur Purchase ID',
            'supplier_delivery_id' => 'Supplier Delivery ID',
            'supplier_delivery_trx_id' => 'Supplier Delivery Trx ID',
            'item_id' => 'Item ID',
            'item_sku_id' => 'Item Sku ID',
            'storage_id' => 'Storage ID',
            'storage_rack_id' => 'Storage Rack ID',
            'jumlah_item' => 'Jumlah Item',
            'harga_satuan' => 'Harga Satuan',
            'jumlah_harga' => 'Jumlah Harga',
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
    public function getReturPurchase()
    {
        return $this->hasOne(ReturPurchase::className(), ['id' => 'retur_purchase_id']);
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
    public function getSupplierDeliveryTrx()
    {
        return $this->hasOne(SupplierDeliveryTrx::className(), ['id' => 'supplier_delivery_trx_id']);
    }
}
