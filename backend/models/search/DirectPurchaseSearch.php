<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DirectPurchase;

/**
 * DirectPurchaseSearch represents the model behind the search form about `backend\models\DirectPurchase`.
 */
class DirectPurchaseSearch extends DirectPurchase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date', 'reference', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['jumlah_item', 'jumlah_harga'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DirectPurchase::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'date' => $this->date,
            'jumlah_item' => $this->jumlah_item,
            'jumlah_harga' => $this->jumlah_harga,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
