<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Mtable;

/**
 * MtableSearch represents the model behind the search form about `backend\models\Mtable`.
 */
class MtableSearch extends Mtable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama_meja', 'status', 'keterangan', 'created_at', 'user_created', 'updated_at', 'user_updated', 'mtableCategory.nama_category'], 'safe'],
            [['mtable_category_id', 'kapasitas', 'not_ppn', 'not_service_charge'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['menu.nama_menu', 'mtableCategory.nama_category']);
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
        $query = Mtable::find();
        $query->joinWith(['mtableCategory']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);
        
        $dataProvider->sort->attributes['mtableCategory.nama_category'] = [
            'asc' => ['mtable_category.nama_category' => SORT_ASC],
            'desc' => ['mtable_category.nama_category' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mtable_category_id' => $this->mtable_category_id,
            'kapasitas' => $this->kapasitas,
            'not_ppn' => $this->not_ppn,
            'not_service_charge' => $this->not_service_charge,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mtable.id', $this->id])
            ->andFilterWhere(['like', 'mtable.nama_meja', $this->nama_meja])
            ->andFilterWhere(['like', 'mtable.status', $this->status])
            ->andFilterWhere(['like', 'mtable.keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'mtable.user_created', $this->user_created])
            ->andFilterWhere(['like', 'mtable.user_updated', $this->user_updated])
            ->andFilterWhere(['like', 'mtable_category.nama_category',  $this->getAttribute('mtableCategory.nama_category')]);

        return $dataProvider;
    }
}
