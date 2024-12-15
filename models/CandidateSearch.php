<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Candidate;

/**
 * CandidateSearch represents the model behind the search form of `app\models\Candidate`.
 */
class CandidateSearch extends Candidate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status_id', 'job_opening_id'], 'integer'],
            [['surname', 'name', 'patronymic', 'desired_position', 'phone', 'email', 'medical_card', 'work_experience'], 'safe'],
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
    $query = Candidate::find();

    // Подключение связей (укажите связи в модели Candidate, если их ещё нет)
    $query->joinWith(['status', 'user', 'jobOpenings']);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'defaultOrder' => [
                'id' => SORT_ASC
            ]
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        // Если валидация не прошла, возвращаем пустой DataProvider
        $query->where('0=1');
        return $dataProvider;
    }

    // Условие фильтрации
    $query->andFilterWhere([
        'candidate.id' => $this->id,
        'candidate.user_id' => $this->user_id,
        'candidate.status_id' => $this->status_id,
        'candidate.job_opening_id' => $this->job_opening_id,
    ]);

    // Добавляем фильтры по текстовым полям, указывая таблицу
    $query->andFilterWhere(['like', 'candidate.surname', $this->surname])
        ->andFilterWhere(['like', 'candidate.name', $this->name]) // Указана таблица "candidate"
        ->andFilterWhere(['like', 'candidate.patronymic', $this->patronymic])
        ->andFilterWhere(['like', 'candidate.desired_position', $this->desired_position])
        ->andFilterWhere(['like', 'candidate.phone', $this->phone])
        ->andFilterWhere(['like', 'candidate.email', $this->email])
        ->andFilterWhere(['like', 'candidate.medical_card', $this->medical_card])
        ->andFilterWhere(['like', 'candidate.work_experience', $this->work_experience]);

    return $dataProvider;
    }
}
