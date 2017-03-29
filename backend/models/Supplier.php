<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property string $kd_supplier
 * @property string $nama
 * @property string $alamat
 * @property string $telp
 * @property string $fax
 * @property string $keterangan
 * @property string $kontak1
 * @property string $kontak1_telp
 * @property string $kontak2
 * @property string $kontak2_telp
 * @property string $kontak3
 * @property string $kontak3_telp
 * @property string $kontak4
 * @property string $kontak4_telp
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 */
class Supplier extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kd_supplier', 'nama'], 'required'], 
            [['kd_supplier'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['alamat', 'keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kd_supplier'], 'string', 'max' => 7],
            [['kd_supplier'], 'unique'],
            [['nama', 'kontak1', 'kontak2', 'kontak3', 'kontak4'], 'string', 'max' => 48],
            [['telp', 'fax', 'kontak1_telp', 'kontak2_telp', 'kontak3_telp', 'kontak4_telp'], 'string', 'max' => 15],
            [['telp', 'fax', 'kontak1_telp', 'kontak2_telp', 'kontak3_telp', 'kontak4_telp'], 'match', 'pattern' => '/^[0-9_-]+$/', 'message' => 'Can only contain numeric characters, underscores and dashes.'],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kd_supplier' => 'Kd Supplier',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'telp' => 'Telp',
            'fax' => 'Fax',
            'keterangan' => 'Keterangan',
            'kontak1' => 'Kontak1',
            'kontak1_telp' => 'Kontak1 Telp',
            'kontak2' => 'Kontak2',
            'kontak2_telp' => 'Kontak2 Telp',
            'kontak3' => 'Kontak3',
            'kontak3_telp' => 'Kontak3 Telp',
            'kontak4' => 'Kontak4',
            'kontak4_telp' => 'Kontak4 Telp',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
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
