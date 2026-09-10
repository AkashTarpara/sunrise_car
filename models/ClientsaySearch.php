<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Clientsay;

/**
 * ClientsaySearch represents the model behind the search form of `app\models\Clientsay`.
 */
class ClientsaySearch extends Clientsay
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_say_id'], 'integer'],
            [['name', 'designation', 'description', 'image', 'status', 'created_at'], 'safe'],
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
        $query = Clientsay::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['display_order'], 'defaultOrder' => ['display_order' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'client_say_id' => $this->client_say_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'designation', $this->designation])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
