<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mtable_order".
 *
 * @property string $id
 * @property string $mtable_session_id
 * @property string $menu_id
 * @property string $catatan
 * @property string $harga_satuan
 * @property string $discount_type
 * @property string $discount
 * @property double $jumlah
 * @property integer $is_free_menu
 * @property string $free_menu_at
 * @property string $user_free_menu
 * @property integer $is_void
 * @property string $void_at
 * @property string $user_void
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property MenuQueue $menuQueue
 * @property Menu $menu
 * @property User $userCreated
 * @property User $userUpdated
 * @property MtableSession $mtableSession
 * @property User $userVoid
 * @property User $userFreeMenu
 */
class MtableOrder extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mtable_order';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mtable_session_id', 'menu_id'], 'required'],
            [['mtable_session_id', 'is_free_menu', 'is_void'], 'integer'],
            [['catatan', 'discount_type'], 'string'],
            [['harga_satuan'], 'number', 'min' => 0],
            [['jumlah'], 'number', 'min' => 1],
            [['discount'], 'number', 'min' => 0],
            [['free_menu_at', 'void_at', 'created_at', 'updated_at'], 'safe'],
            [['menu_id', 'user_free_menu', 'user_void', 'user_created', 'user_updated'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mtable_session_id' => 'Mtable Session ID',
            'menu_id' => 'Menu ID',
            'catatan' => 'Catatan',
            'harga_satuan' => 'Harga Satuan',
            'discount_type' => 'Discount Type',
            'discount' => 'Discount',
            'jumlah' => 'Jumlah',
            'is_free_menu' => 'Is Free Menu',
            'free_menu_at' => 'Free Menu At',
            'user_free_menu' => 'User Free Menu',
            'is_void' => 'Is Void',
            'void_at' => 'Void At',
            'user_void' => 'User Void',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuQueue()
    {
        return $this->hasOne(MenuQueue::className(), ['mtable_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
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
    public function getMtableSession()
    {
        return $this->hasOne(MtableSession::className(), ['id' => 'mtable_session_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVoid()
    {
        return $this->hasOne(User::className(), ['id' => 'user_void']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFreeMenu()
    {
        return $this->hasOne(User::className(), ['id' => 'user_free_menu']);
    }
}
