<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mtable_session".
 *
 * @property string $id
 * @property string $mtable_id
 * @property integer $is_closed
 * @property string $nama_tamu
 * @property string $jumlah_guest
 * @property string $opened_at
 * @property string $user_opened
 * @property string $closed_at
 * @property string $user_closed
 * @property integer $is_join_mtable
 * @property string $mtable_join_id
 * @property integer $bill_printed
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property MtableJoin[] $mtableJoins
 * @property MtableOrder[] $mtableOrders
 * @property Mtable $mtable
 * @property User $userOpened
 * @property User $userClosed
 * @property User $userCreated
 * @property User $userUpdated
 * @property MtableJoin $mtableJoin
 * @property SaleInvoice[] $saleInvoices
 */
class MtableSession extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mtable_session';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mtable_id'], 'required'],
            [['is_closed', 'is_join_mtable', 'mtable_join_id', 'bill_printed'], 'integer'],
            [['jumlah_guest'], 'number', 'min' => 1],
            [['opened_at', 'closed_at', 'created_at', 'updated_at'], 'safe'],
            [['mtable_id'], 'string', 'max' => 24],
            [['nama_tamu'], 'string', 'max' => 64],
            [['user_opened', 'user_closed', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mtable_id' => 'Meja',
            'is_closed' => 'Is Closed',
            'nama_tamu' => 'Nama Tamu',
            'jumlah_guest' => 'Jumlah Guest',
            'opened_at' => 'Opened At',
            'user_opened' => 'User Opened',
            'closed_at' => 'Closed At',
            'user_closed' => 'User Closed',
            'is_join_mtable' => 'Is Join Mtable',
            'mtable_join_id' => 'Mtable Join ID',
            'bill_printed' => 'Bill Printed',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    
 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableJoins()
    {
        return $this->hasMany(MtableJoin::className(), ['active_mtable_session_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtableOrders()
    {
        return $this->hasMany(MtableOrder::className(), ['mtable_session_id' => 'id']);
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
    public function getUserOpened()
    {
        return $this->hasOne(User::className(), ['id' => 'user_opened']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserClosed()
    {
        return $this->hasOne(User::className(), ['id' => 'user_closed']);
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
    public function getMtableJoin()
    {
        return $this->hasOne(MtableJoin::className(), ['id' => 'mtable_join_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleInvoices()
    {
        return $this->hasMany(SaleInvoice::className(), ['mtable_session_id' => 'id']);
    }
}
