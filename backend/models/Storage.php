<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "storage".
 *
 * @property string $id
 * @property string $nama_storage
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Stock[] $stocks
 * @property StockMovement[] $stockMovements
 * @property StockOpname[] $stockOpnames
 * @property User $userCreated
 * @property User $userUpdated
 * @property StorageRack[] $storageRacks
 */
class Storage extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'unique'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['id', 'nama_storage'], 'required'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 12],
            [['nama_storage', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_storage' => 'Nama Storage',
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
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['storage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockMovements()
    {
        return $this->hasMany(StockMovement::className(), ['storage_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpnames()
    {
        return $this->hasMany(StockOpname::className(), ['storage_id' => 'id']);
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
    public function getStorageRacks()
    {
        return $this->hasMany(StorageRack::className(), ['storage_id' => 'id']);
    }
}
