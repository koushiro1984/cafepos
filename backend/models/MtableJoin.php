<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mtable_join".
 *
 * @property string $id
 * @property string $active_mtable_session_id
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property MtableSession $activeMtableSession
 * @property User $userCreated
 * @property User $userUpdated
 */
class MtableJoin extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mtable_join';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_mtable_session_id'], 'required'],
            [['active_mtable_session_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'active_mtable_session_id' => 'Active Mtable Session  ID',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveMtableSession()
    {
        return $this->hasOne(MtableSession::className(), ['id' => 'active_mtable_session_id']);
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
