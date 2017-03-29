<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReturSale;

/**
 * ReturSaleSearch represents the model behind the search form about `backend\models\ReturSale`.
 */
class ReturSaleSearch extends ReturSale
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sale_invoice_detail_id'], 'integer'],
            [['menu_id', 'discount_type', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['jumlah', 'discount', 'harga'], 'number'],
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
        $query = ReturSale::find();

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
            'sale_invoice_detail_id' => $this->sale_invoice_detail_id,
            'jumlah' => $this->jumlah,
            'discount' => $this->discount,
            'harga' => $this->harga,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'menu_id', $this->menu_id])
            ->andFilterWhere(['like', 'discount_type', $this->discount_type])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
