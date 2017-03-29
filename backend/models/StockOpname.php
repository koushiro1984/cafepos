<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stock_opname".
 *
 * @property string $id
 * @property string $item_id
 * @property string $item_sku_id
 * @property string $storage_id
 * @property string $storage_rack_id
 * @property double $jumlah
 * @property double $jumlah_awal
 * @property double $jumlah_adjustment
 * @property string $action
 * @property string $date_action
 * @property string $user_action
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Item $item
 * @property ItemSku $itemSku
 * @property Storage $storage
 * @property StorageRack $storageRack
 * @property User $userAction
 * @property User $userCreated
 * @property User $userUpdated
 */
class StockOpname extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_opname';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'item_sku_id', 'storage_id', 'jumlah'], 'required'],
            [['storage_rack_id'], 'integer'],
            [['jumlah', 'jumlah_awal', 'jumlah_adjustment'], 'number'],
            [['action'], 'string'],
            [['date_action', 'created_at', 'updated_at'], 'safe'],
            [['item_id', 'item_sku_id'], 'string', 'max' => 16],
            [['storage_id'], 'string', 'max' => 12],
            [['user_action', 'user_created', 'user_updated'], 'string', 'max' => 32]
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
            'storage_id' => 'Storage ID',
            'storage_rack_id' => 'Storage Rack ID',
            'jumlah' => 'Jumlah',
            'jumlah_awal' => 'Jumlah Awal',
            'jumlah_adjustment' => 'Jumlah Adjusment',
            'action' => 'Action',
            'date_action' => 'Date Action',
            'user_action' => 'User Action',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
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
    public function getUserAction()
    {
        return $this->hasOne(User::className(), ['id' => 'user_action']);
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
