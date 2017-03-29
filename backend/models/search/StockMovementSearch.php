<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StockMovement;

/**
 * StockMovementSearch represents the model behind the search form about `backend\models\StockMovement`.
 */
class StockMovementSearch extends StockMovement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'storage_rack_from', 'storage_rack_to'], 'integer'],
            [['type', 'item_id', 'item_sku_id', 'storage_from', 'storage_to', 'reference', 'tanggal', 'keterangan', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['jumlah'], 'number'],
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
        $query = StockMovement::find();

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
            'id' => $this->id,
            'storage_rack_from' => $this->storage_rack_from,
            'storage_rack_to' => $this->storage_rack_to,
            'jumlah' => $this->jumlah,
            'tanggal' => $this->tanggal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'item_id', $this->item_id])
            ->andFilterWhere(['like', 'item_sku_id', $this->item_sku_id])
            ->andFilterWhere(['like', 'storage_from', $this->storage_from])
            ->andFilterWhere(['like', 'storage_to', $this->storage_to])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
