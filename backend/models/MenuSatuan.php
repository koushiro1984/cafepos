<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu_satuan".
 *
 * @property string $id
 * @property string $nama_satuan
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 */
class MenuSatuan extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_satuan';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama_satuan'], 'required'],
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 16],
            [['id'], 'unique'],
            [['nama_satuan', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_satuan' => 'Nama Satuan',
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
