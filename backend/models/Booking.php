<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "booking".
 *
 * @property string $id
 * @property string $mtable_id
 * @property string $nama_pelanggan
 * @property string $date
 * @property string $time
 * @property string $keterangan
 * @property integer $is_closed
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Mtable $mtable
 * @property User $userCreated
 * @property User $userUpdated
 */
class Booking extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['mtable_id', 'nama_pelanggan', 'date', 'time'], 'required'],
            [['date', 'time', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['is_closed'], 'integer'],
            [['id'], 'string', 'max' => 16],
            [['id',], 'unique'],
            [['mtable_id'], 'string', 'max' => 24],
            [['nama_pelanggan'], 'string', 'max' => 64],
            [['user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mtable_id' => 'Table',
            'nama_pelanggan' => 'Nama Pelanggan',
            'date' => 'Date',
            'time' => 'Time',
            'keterangan' => 'Keterangan',
            'is_closed' => 'Is Closed',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtable()
    {
        return $this->hasOne(Mtable::className(), ['id' => 'mtable_id']);
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
