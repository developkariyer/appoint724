<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    public function rules(): array
    {
        return [
            [['id', 'gsmverified', 'emailverified'], 'integer'],
            [['status', 'status_message', 'last_active', 'created_at', 'updated_at', 'deleted_at', 'first_name', 'last_name', 'tcno', 'gsm', 'email'], 'safe'],
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

    public function search($params): ActiveDataProvider
    {
        $query = User::find();

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
            'id' => $this->id,
            'gsmverified' => $this->gsmverified,
            'emailverified' => $this->emailverified,
            'last_active' => $this->last_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'status_message', $this->status_message])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'tcno', $this->tcno])
            ->andFilterWhere(['like', 'gsm', $this->gsm])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
