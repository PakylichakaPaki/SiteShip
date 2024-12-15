<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JobOpenings;
use yii\db\Expression;

/**
 * JobOpeningsSearch represents the model behind the search form of `app\models\JobOpenings`.
 */
class JobOpeningsSearch extends Model
{
    public $id;
    public $title_of_the_position;
    public $salary;
    public $term_of_employment;
    public $company_name;
    public $link_to_the_questionnaire;
    public $contact_information;
    public $user_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['title_of_the_position', 'term_of_employment', 'company_name', 'link_to_the_questionnaire', 'contact_information', 'date_of_creation'], 'safe'],
            [['salary'], 'safe'],
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
        $query = JobOpenings::find();

        // Добавляем условие для фильтрации истекших вакансий
        $query->andWhere(['>', new Expression('DATE_ADD(date_of_creation, INTERVAL 15 DAY)'), new Expression('NOW()')]);

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
            return $dataProvider;
        }

        // Фильтр по ID
        $query->andFilterWhere(['id' => $this->id]);
        
        // Фильтр по зарплате (диапазон)
        if (!empty($this->salary)) {
            if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $this->salary, $matches)) {
                $query->andWhere(['between', 'salary', $matches[1], $matches[2]]);
            } else {
                $query->andFilterWhere(['salary' => $this->salary]);
            }
        }

        // Фильтры по текстовым полям с частичным совпадением
        $query->andFilterWhere(['like', 'title_of_the_position', $this->title_of_the_position])
            ->andFilterWhere(['like', 'term_of_employment', $this->term_of_employment])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'link_to_the_questionnaire', $this->link_to_the_questionnaire])
            ->andFilterWhere(['like', 'contact_information', $this->contact_information]);

        // Фильтр по пользователю
        $query->andFilterWhere(['user_id' => $this->user_id]);

        return $dataProvider;
    }

    /**
     * Возвращает список вариантов условий трудоустройства
     */
    public static function getTermsOfEmployment()
    {
        return [
            'Полная занятость' => 'Полная занятость',
            'Частичная занятость' => 'Частичная занятость',
            'Удаленная работа' => 'Удаленная работа',
            'Проектная работа' => 'Проектная работа',
        ];
    }
}
