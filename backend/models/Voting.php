<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "voting".
 *
 * @property string $id
 * @property string $nama
 * @property string $kota
 * @property string $email
 * @property integer $rate
 * @property string $message
 * @property integer $is_publish
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 */
class Voting extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voting';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'kota', 'rate'], 'required'],
            [['rate', 'is_publish'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama', 'email', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['email'], 'email'],
            [['kota'], 'string', 'max' => 24]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'kota' => 'Kota',
            'email' => 'Email',
            'rate' => 'Rate',
            'message' => 'Message',
            'is_publish' => 'Is Publish',
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
    
    /**
     * Get rating point.
     * @return $point
     */
    public static function getPoint() {
        $models = Voting::find()->where(['is_publish' => true])->all();
        
        $totalRate = 0;
        foreach ($models as $value) {
            $totalRate += $value->rate;
        }        
        
        $point = number_format($totalRate / count($models), 1);
        
        return $point;
    }
}
