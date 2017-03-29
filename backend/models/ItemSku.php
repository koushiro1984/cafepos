<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "item_sku".
 *
 * @property string $id
 * @property string $no_urut
 * @property string $item_id
 * @property string $barcode
 * @property string $nama_sku
 * @property double $stok_minimal
 * @property double $per_stok
 * @property double $harga_satuan
 * @property double $harga_beli
 * @property string $storage_id
 * @property string $storage_rack_id
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Item $item
 * @property User $userCreated
 * @property User $userUpdated
 * @property Storage $storage
 * @property StorageRack $storageRack
 * @property MenuReceipt[] $menuReceipts
 * @property Stock[] $stocks
 * @property StockMovement[] $stockMovements
 * @property StockOpname[] $stockOpnames
 */
class ItemSku extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_sku';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'item_id'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['no_urut', 'storage_rack_id'], 'integer'],
            [['stok_minimal', 'per_stok', 'harga_satuan', 'harga_beli'], 'number', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'item_id'], 'string', 'max' => 16],
            [['id'], 'unique'],
            [['barcode', 'nama_sku', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['storage_id'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'No. SKU',
            'no_urut' => 'No Urut',
            'item_id' => 'Item ID',
            'barcode' => 'Barcode',
            'nama_sku' => 'Satuan',
            'stok_minimal' => 'Stok Minimal',
            'per_stok' => 'Per Stok',
            'harga_satuan' => 'Harga Satuan',
            'harga_beli' => 'Harga Beli',
            'storage_id' => 'Storage ID',
            'storage_rack_id' => 'Storage Rack ID',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'item.id' => 'Item ID',
        ];
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
    public function getMenuReceipts()
    {
        return $this->hasMany(MenuReceipt::className(), ['item_sku_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['item_sku_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockMovements()
    {
        return $this->hasMany(StockMovement::className(), ['item_sku_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpnames()
    {
        return $this->hasMany(StockOpname::className(), ['item_sku_id' => 'id']);
    }
}
