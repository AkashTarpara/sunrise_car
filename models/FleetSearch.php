<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class FleetSearch extends Fleet
{
    public function rules()
    {
        return [
            [['id', 'laggage'], 'integer'],
            [['label', 'name', 'passenger', 'status', 'type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Fleet::find()->where(['deleted_at' => null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'laggage' => $this->laggage,
            'status' => $this->status,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'passenger', $this->passenger]);

        return $dataProvider;
    }
}
