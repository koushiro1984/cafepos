<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "direct_purchase".
 *
 * @property string $id
 * @property string $date
 * @property string $reference
 * @property double $jumlah_item
 * @property string $jumlah_harga
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userCreated
 * @property User $userUpdated
 * @property DirectPurchaseTrx[] $directPurchaseTrxes
 */
class DirectPurchase extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'direct_purchase';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Can only contain alphanumeric characters, underscores and dashes.'],
            [['date'], 'required'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['jumlah_harga'], 'number', 'min' => 1],
            [['jumlah_item'], 'number', 'min' => 0.001],
            [['id'], 'string', 'max' => 13],
            [['reference', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'reference' => 'Reference',
            'jumlah_item' => 'Jumlah Item',
            'jumlah_harga' => 'Jumlah Harga',
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
     * @return \yii\db\ActiveQuery
     */
    public function getDirectPurchaseTrxes()
    {
        return $this->hasMany(DirectPurchaseTrx::className(), ['direct_purchase_id' => 'id']);
    }
}
