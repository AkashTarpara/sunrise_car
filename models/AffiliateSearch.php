<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Affiliate;

/**
 * AffiliateSearch represents the model behind the search form of `app\models\Affiliate`.
 */
class AffiliateSearch extends Affiliate
{
    public function rules()
    {
        return [
            [['affiliate_id', 'status'], 'integer'],
            [['full_name', 'email', 'phone', 'make_model', 'vehicle_class', 'legal_business_name', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Affiliate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['affiliate_id', 'created_at'], 'defaultOrder' => ['affiliate_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'affiliate_id' => $this->affiliate_id,
            'status' => $this->status,
            'vehicle_class' => $this->vehicle_class,
        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'make_model', $this->make_model])
            ->andFilterWhere(['like', 'legal_business_name', $this->legal_business_name]);

        return $dataProvider;
    }
}
