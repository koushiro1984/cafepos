<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kode_transaksi".
 *
 * @property string $account_id
 * @property string $nama_account
 * @property string $account_type
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 */
class KodeTransaksi extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kode_transaksi';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'nama_account', 'account_type'], 'required'],
            [['account_id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['account_type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['account_id'], 'string', 'max' => 16],
            [['nama_account', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'nama_account' => 'Nama Account',
            'account_type' => 'Account Type',
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
