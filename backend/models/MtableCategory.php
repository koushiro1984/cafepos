<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mtable_category".
 *
 * @property string $id
 * @property string $nama_category
 * @property string $color
 * @property string $keterangan
 * @property string $image
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Mtable[] $mtables
 * @property User $userCreated
 * @property User $userUpdated
 */
class MtableCategory extends \backend\models\base\BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mtable_category';
    }
        

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_category'], 'required'],
            [['keterangan', 'image'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama_category', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['color'], 'string', 'max' => 7]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_category' => 'Nama Category',
            'color' => 'Color',
            'keterangan' => 'Keterangan',
            'image' => 'Image',
            'created_at' => 'Created At',
            'user_created' => 'User Created',
            'updated_at' => 'Updated At',
            'user_updated' => 'User Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtables()
    {
        return $this->hasMany(Mtable::className(), ['mtable_category_id' => 'id']);
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
