<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "voucher".
 *
 * @property string $id
 * @property string $voucher_type
 * @property double $jumlah_voucher
 * @property string $start_date
 * @property string $end_date
 * @property integer $not_active
 * @property string $keterangan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 */
class Voucher extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['voucher_type', 'keterangan'], 'string'],
            [['jumlah_voucher'], 'number', 'min' => 1],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['not_active'], 'integer'],
            [['id'], 'string', 'max' => 16],
            [['id'], 'unique'],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Kode Voucher',
            'voucher_type' => 'Voucher Type',
            'jumlah_voucher' => 'Nilai Voucher',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'not_active' => 'Not Active',
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
