<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;


class BaseModel extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
            'attribute' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_created', 'user_updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'user_updated',
                ],
                'value' => function($event) {
                    if (Yii::$app->user->isGuest) {
                        return 'G001';
                    } else {
                        return Yii::$app->user->identity->id;
                    }
                },
            ],
        ];
    }

}
