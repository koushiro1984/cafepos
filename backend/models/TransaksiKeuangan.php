<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaksi_keuangan".
 *
 * @property string $id
 * @property string $account_id
 * @property string $date
 * @property string $jumlah
 * @property string $reference_id
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property KodeTransaksi $account
 * @property User $userCreated
 * @property User $userUpdated
 */
class TransaksiKeuangan extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaksi_keuangan';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id'], 'required'],
            [['jumlah'], 'number', 'min' => 1],
            [['keterangan'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['account_id'], 'string', 'max' => 16],
            [['reference_id', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'date' => 'Date',
            'jumlah' => 'Jumlah',
            'reference_id' => 'Reference ID',
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
    public function getAccount()
    {
        return $this->hasOne(KodeTransaksi::className(), ['account_id' => 'account_id']);
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
