<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Adminuser;

/**
 * AdminuserSearch represents the model behind the search form of `app\models\Adminuser`.
 */
class AdminuserSearch extends Adminuser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_id', 'time'], 'integer'],
            [['first_name', 'last_name', 'user_name', 'email', 'password', 'profile_pic', 'contact_no', 'roles', 'is_status', 'auth_key', 'password_reset_token', 'created_at', 'updated_at'], 'safe'],
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
        $query = Adminuser::find();

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
            'admin_id' => $this->admin_id,
            'time' => $this->time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'profile_pic', $this->profile_pic])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'roles', $this->roles])
            ->andFilterWhere(['like', 'is_status', $this->is_status])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
