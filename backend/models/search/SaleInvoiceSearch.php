<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SaleInvoice;

/**
 * SaleInvoiceSearch represents the model behind the search form about `backend\models\SaleInvoice`.
 */
class SaleInvoiceSearch extends SaleInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date', 'user_operator', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['mtable_session_id'], 'integer'],
            [['jumlah_harga', 'jumlah_bayar', 'jumlah_kembali'], 'number'],
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
        $query = SaleInvoice::find();

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
            'mtable_session_id' => $this->mtable_session_id,
            'jumlah_harga' => $this->jumlah_harga,
            'jumlah_bayar' => $this->jumlah_bayar,
            'jumlah_kembali' => $this->jumlah_kembali,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'user_operator', $this->user_operator])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
