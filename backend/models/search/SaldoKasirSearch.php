<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SaldoKasir;

/**
 * SaldoKasirSearch represents the model behind the search form about `backend\models\SaldoKasir`.
 */
class SaldoKasirSearch extends SaldoKasir
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_active', 'date', 'created_at', 'user_created', 'updated_at', 'user_updated'], 'safe'],
            [['shift_id'], 'integer'],
            [['saldo_awal', 'saldo_akhir'], 'number'],
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
        $query = SaldoKasir::find();

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
            'shift_id' => $this->shift_id,
            'saldo_awal' => $this->saldo_awal,
            'saldo_akhir' => $this->saldo_akhir,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'user_active', $this->user_active])
            ->andFilterWhere(['like', 'user_created', $this->user_created])
            ->andFilterWhere(['like', 'user_updated', $this->user_updated]);

        return $dataProvider;
    }
}
