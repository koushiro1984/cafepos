<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property string $id
 * @property string $item_id
 * @property string $item_sku_id
 * @property double $jumlah_stok
 * @property string $storage_id
 * @property string $storage_rack_id
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Item $item
 * @property ItemSku $itemSku
 * @property Storage $storage
 * @property StorageRack $storageRack
 * @property User $userCreated
 * @property User $userUpdated
 */
class Stock extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'item_sku_id', 'jumlah_stok', 'storage_id'], 'required'],
            [['jumlah_stok'], 'number'],
            [['storage_rack_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_id', 'item_sku_id'], 'string', 'max' => 16],
            [['storage_id'], 'string', 'max' => 12],
            [['id', 'user_created', 'user_updated'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'item_sku_id' => 'No. SKU',
            'jumlah_stok' => 'Jumlah Stok',
            'storage_id' => 'Storage ID',
            'storage_rack_id' => 'Storage Rack ID',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
            
            'item.id' => 'Item ID'
        ];
    }
    
    public static function setStock($itemId, $itemSkuId, $storageId, $storageRackId, $jumlah) {
        $idStock = $itemId . $itemSkuId;
        
        if (!empty($storageId))
            $idStock .= $storageId;
        
        if (!empty($storageRackId))
            $idStock .= $storageRackId;        
        
        if (($modelStock = Stock::findOne($idStock)) !== null) {

            $modelStock->jumlah_stok = $modelStock->jumlah_stok + $jumlah;

            return $modelStock->save();            
        } else {
            $modelStock = new Stock();
            $modelStock->id = $idStock;
            $modelStock->item_id = $itemId;
            $modelStock->item_sku_id = $itemSkuId;
            $modelStock->storage_id = $storageId;
            $modelStock->storage_rack_id = $storageRackId;
            $modelStock->jumlah_stok = $jumlah;
            
            return $modelStock->save();
        }
        
        return false;
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
}
