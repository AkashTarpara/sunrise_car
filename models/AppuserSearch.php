<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Appuser;

/**
 * AppuserSearch represents the model behind the search form of `app\models\Appuser`.
 */
class AppuserSearch extends Appuser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appuser_id', 'phone_code', 'password_reset_token_time', 'email_verify_time'], 'integer'],
            [['first_name', 'last_name', 'full_name', 'phone_number', 'postal_code', 'email', 'updated_at', 'created_at'], 'safe'],
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
        $query = Appuser::find()->where(['role' => '3']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['appuser_id'], 'defaultOrder' => ['appuser_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'appuser_id' => $this->appuser_id,
            'phone_code' => $this->phone_code,
            'birth_date' => $this->birth_date,
            'password_reset_token_time' => $this->password_reset_token_time,
            'email_verify_time' => $this->email_verify_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    public function searchadmin($params)
    {
        $query = Appuser::find()->where(['role' => '1']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['appuser_id'], 'defaultOrder' => ['appuser_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'appuser_id' => $this->appuser_id,
            'phone_code' => $this->phone_code,
            'birth_date' => $this->birth_date,
            'password_reset_token_time' => $this->password_reset_token_time,
            'email_verify_time' => $this->email_verify_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'phone_verify', $this->phone_verify])
            ->andFilterWhere(['like', 'otp', $this->otp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'email_verify_code', $this->email_verify_code])
            ->andFilterWhere(['like', 'email_verify', $this->email_verify])
            ->andFilterWhere(['like', 'email_verify_token', $this->email_verify_token])
            ->andFilterWhere(['like', 'email_token', $this->email_token])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'login_type', $this->login_type])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'notification', $this->notification])
            ->andFilterWhere(['like', 'user_status', $this->user_status])
            ->andFilterWhere(['like', 'google_id', $this->google_id])
            ->andFilterWhere(['like', 'facebook_id', $this->facebook_id])
            ->andFilterWhere(['like', 'instagram_id', $this->instagram_id])
            ->andFilterWhere(['like', 'twitter_id', $this->twitter_id])
            ->andFilterWhere(['like', 'apple_id', $this->apple_id])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'lang_code', $this->lang_code])
            ->andFilterWhere(['like', 'signup_type', $this->signup_type])
            ->andFilterWhere(['like', 'status_of_user', $this->status_of_user])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted])
            ->andFilterWhere(['=', 'is_signup', $this->is_signup])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'site_logo', $this->site_logo])
            ->andFilterWhere(['like', 'user_type', $this->user_type])
            ->andFilterWhere(['like', 'stripe_live_customer_id', $this->stripe_live_customer_id])
            ->andFilterWhere(['like', 'stripe_test_customer_id', $this->stripe_test_customer_id]);

        return $dataProvider;
    }
}
