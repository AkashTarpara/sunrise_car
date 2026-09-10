<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contactus;

/**
 * ContactusSearch represents the model behind the search form of `app\models\Contactus`.
 */
class ContactusSearch extends Contactus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contact_us_id'], 'integer'],
            [['first_name', 'last_name', 'email', 'number', 'address', 'message', 'created_at'], 'safe'],
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
        $query = Contactus::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['contact_us_id'], 'defaultOrder' => ['contact_us_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'contact_us_id' => $this->contact_us_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
