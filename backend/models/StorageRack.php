<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "storage_rack".
 *
 * @property string $id
 * @property string $storage_id
 * @property string $nama_rak
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Storage $storage
 * @property User $userCreated
 * @property User $userUpdated
 */
class StorageRack extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage_rack';
    }    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'unique'],
            [['nama_rak', 'storage_id'], 'required'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['storage_id'], 'string', 'max' => 12],
            [['nama_rak'], 'string', 'max' => 32],
            [['nama_rak', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['nama_rak'], 'checkNamaRak'],
        ];
    }
    
    public function checkNamaRak($attribute, $params)
    {
        $dataStorageRack = StorageRack::findAll(['storage_id' => $this->storage_id]);
        
        foreach ($dataStorageRack as $data) {
            if ($data->nama_rak == $this->nama_rak) {
                $this->addError('nama_rak', 'Nama Rak "' . $this->nama_rak . '" has already been taken.');            
                break;
            }                
        }               
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'storage_id' => 'Storage ID',
            'nama_rak' => 'Nama Rak',
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
    public function getStorage()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storage_id']);
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
