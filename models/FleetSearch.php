<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class FleetSearch extends Fleet
{
    public function rules()
    {
        return [
            [['id', 'passenger', 'laggage'], 'integer'],
            [['label', 'name', 'status', 'type'], 'safe'],
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
            'passenger' => $this->passenger,
            'laggage' => $this->laggage,
            'status' => $this->status,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
