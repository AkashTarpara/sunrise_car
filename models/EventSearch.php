<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class EventSearch extends Event
{
    public function rules()
    {
        return [
            [['event_id'], 'integer'],
            [['title', 'category', 'event_date', 'location', 'status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Event::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $provider;
        }

        $query->andFilterWhere([
            'event_id' => $this->event_id,
            'event_date' => $this->event_date,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $provider;
    }
}
