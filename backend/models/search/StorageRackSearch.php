<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StorageRack;

/**
 * StorageRackSearch represents the model behind the search form about `backend\models\StorageRack`.
 */
class StorageRackSearch extends StorageRack {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['storage_id', 'nama_rak', 'keterangan', 'storage.nama_storage'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['storage.nama_storage']);
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = StorageRack::find();
        $query->joinWith(['storage']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => 15,
            ),
        ]);

        $dataProvider->sort->attributes['storage.nama_storage'] = [
            'asc' => ['storage.nama_storage' => SORT_ASC],
            'desc' => ['storage.nama_storage' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'storage_rack.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'storage_rack.storage_id', $this->storage_id])
            ->andFilterWhere(['like', 'storage_rack.nama_rak', $this->nama_rak])
            ->andFilterWhere(['like', 'storage_rack.keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'storage.nama_storage',  $this->getAttribute('storage.nama_storage')]);

        return $dataProvider;
    }
    
    /**
     * Find Sku based on storage_id     
     * 
     * @return ActiveQuery
     */
    
    public static function getData($storage_id) {
        $query = StorageRack::find();        
        $query->where(['storage_id' => $storage_id]);
        
        return $query;
    }

}
