<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tradepropartner;

/**
 * TradepropartnerSearch represents the model behind the search form of `app\models\Tradepropartner`.
 */
class TradepropartnerSearch extends Tradepropartner
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['full_name', 'email', 'phone', 'job_title', 'years_experience', 'project_type', 'project_location', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Tradepropartner::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['id', 'created_at'], 'defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'years_experience', $this->years_experience])
            ->andFilterWhere(['like', 'project_type', $this->project_type])
            ->andFilterWhere(['like', 'project_location', $this->project_location]);

        return $dataProvider;
    }
}
