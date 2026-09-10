<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Warranty;

/**
 * WarrantySearch represents the model behind the search form of `app\models\Warranty`.
 */
class WarrantySearch extends Warranty
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warranty_id'], 'integer'],
            [['label', 'title', 'sub_title', 'created_at', 'updated_at'], 'safe'],
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
        $query = Warranty::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'warranty_id' => $this->warranty_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sub_title', $this->sub_title]);

        return $dataProvider;
    }
}
