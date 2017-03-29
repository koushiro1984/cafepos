<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stock_movement".
 *
 * @property string $id
 * @property string $type
 * @property string $item_id
 * @property string $item_sku_id
 * @property string $storage_from
 * @property string $storage_rack_from
 * @property string $storage_to
 * @property string $storage_rack_to
 * @property string $branch_id
 * @property double $jumlah
 * @property string $tanggal
 * @property string $reference
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property StorageRack $storageRackFrom
 * @property Storage $storageTo
 * @property StorageRack $storageRackTo
 * @property User $userCreated
 * @property User $userUpdated
 * @property Item $item
 * @property ItemSku $itemSku
 * @property Storage $storageFrom
 * @property Branch $branch
 */
class StockMovement extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_movement';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'item_id', 'item_sku_id', 'tanggal'], 'required'],
            [['type', 'keterangan'], 'string'],
            [['storage_rack_from', 'storage_rack_to'], 'integer'],
            [['jumlah'], 'number', 'min' => 0.001],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['item_id', 'item_sku_id', 'branch_id'], 'string', 'max' => 16],
            [['storage_from', 'storage_to'], 'string', 'max' => 12],
            [['reference', 'user_created', 'user_updated'], 'string', 'max' => 32]
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
            'item_id' => 'Item ID',
            'item_sku_id' => 'No. SKU',
            'storage_from' => 'From Storage',
            'storage_rack_from' => 'From Storage Rack',
            'storage_to' => 'To Storage',
            'storage_rack_to' => 'To Storage Rack',
            'jumlah' => 'Jumlah',
            'branch_id' => 'Branch ID',
            'tanggal' => 'Tanggal',
            'reference' => 'Reference',
            'keterangan' => 'Keterangan',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }
    
    public static function setInflow($type, $itemId, $itemSkuId, $storageTo, $storageRackTo, $jumlah, $tanggal, $reference) {
        $model = new StockMovement();
        $model->type = $type;
        $model->item_id = $itemId;
        $model->item_sku_id = $itemSkuId;
        $model->storage_to = $storageTo;
        $model->storage_rack_to = $storageRackTo;
        $model->jumlah = $jumlah;
        $model->tanggal = $tanggal;
        $model->reference = $reference;
        
        return $model->save();
    }
    
    public static function setOutflow($type, $itemId, $itemSkuId, $storageFrom, $storageRackFrom, $jumlah, $tanggal, $reference) {
        $model = new StockMovement();
        $model->type = $type;
        $model->item_id = $itemId;
        $model->item_sku_id = $itemSkuId;
        $model->storage_from = $storageFrom;
        $model->storage_rack_from = $storageRackFrom;
        $model->jumlah = $jumlah;
        $model->tanggal = $tanggal;
        $model->reference = $reference;
        
        return $model->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageRackFrom()
    {
        return $this->hasOne(StorageRack::className(), ['id' => 'storage_rack_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageTo()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storage_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageRackTo()
    {
        return $this->hasOne(StorageRack::className(), ['id' => 'storage_rack_to']);
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
    public function getStorageFrom()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storage_from']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }
}
