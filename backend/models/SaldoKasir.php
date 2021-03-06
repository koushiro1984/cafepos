<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "saldo_kasir".
 *
 * @property string $id
 * @property string $shift_id
 * @property string $date
 * @property string $user_active
 * @property string $saldo_awal
 * @property string $saldo_akhir
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Shift $shift
 * @property User $userActive
 * @property User $userCreated
 * @property User $userUpdated
 */
class SaldoKasir extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'saldo_kasir';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shift_id', 'user_active', 'saldo_awal'], 'required'],
            [['shift_id'], 'integer'],
            [['saldo_awal', 'saldo_akhir'], 'number', 'min' => 1],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 10],
            [['id'], 'unique'],
            [['user_active', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shift_id' => 'Shift ID',
            'date' => 'Date',
            'user_active' => 'User Active',
            'saldo_awal' => 'Saldo Awal',
            'saldo_akhir' => 'Saldo Akhir',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(Shift::className(), ['id' => 'shift_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserActive()
    {
        return $this->hasOne(User::className(), ['id' => 'user_active']);
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
