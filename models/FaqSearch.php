<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class FaqSearch extends Faq
{
    public function rules()
    {
        return [
            [['faq_id', 'display_order'], 'integer'],
            [['type', 'title', 'status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Faq::find()->orderBy(['type' => SORT_ASC, 'display_order' => SORT_ASC, 'faq_id' => SORT_ASC]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $provider;
        }

        $query->andFilterWhere([
            'faq_id' => $this->faq_id,
            'type' => $this->type,
            'display_order' => $this->display_order,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $provider;
    }
}
