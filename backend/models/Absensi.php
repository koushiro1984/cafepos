<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "absensi".
 *
 * @property string $id
 * @property string $kd_karyawan
 * @property string $tanggal
 * @property string $check_in
 * @property string $check_out
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Employee $kdKaryawan
 * @property User $userCreated
 * @property User $userUpdated
 */
class Absensi extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'absensi';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['tanggal', 'check_in', 'check_out', 'created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 16],
            [['kd_karyawan'], 'string', 'max' => 7],
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
            'kd_karyawan' => 'Kd Karyawan',
            'tanggal' => 'Tanggal',
            'check_in' => 'Check In',
            'check_out' => 'Check Out',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKdKaryawan()
    {
        return $this->hasOne(Employee::className(), ['kd_karyawan' => 'kd_karyawan']);
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
