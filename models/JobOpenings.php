<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "job_openings".
 *
 * @property int $id
 * @property string $title_of_the_position
 * @property float $salary
 * @property string $term_of_employment
 * @property string|null $company_name
 * @property string|null $link_to_the_questionnaire
 * @property string|null $contact_information
 * @property int $user_id
 * @property string $date_of_creation
 *
 * @property User $user
 */
class JobOpenings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_openings';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_of_creation',
                'updatedAtAttribute' => false,
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        return array_merge($attributes, ['date_of_creation']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_of_the_position', 'salary', 'term_of_employment', 'user_id'], 'required'],
            [['salary'], 'number'],
            [['user_id'], 'integer'],
            [['date_of_creation'], 'safe'],
            [['title_of_the_position', 'company_name', 'contact_information'], 'string', 'max' => 256],
            [['term_of_employment'], 'string', 'max' => 32],
            [['link_to_the_questionnaire'], 'string', 'max' => 512],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['default'] = ['title_of_the_position', 'salary', 'term_of_employment', 'company_name', 
            'link_to_the_questionnaire', 'contact_information', 'user_id', 'date_of_creation'];
        return $scenarios;
    }

    /**
     * Возвращает время до истечения вакансии в формате относительной даты
     */
    public function getExpirationDate()
    {
        $timestamp = strtotime($this->date_of_creation . ' +15 days');
        return $timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_of_the_position' => 'Название должности',
            'salary' => 'Зарплата',
            'term_of_employment' => 'Срок трудоустройства',
            'company_name' => 'Название компании',
            'link_to_the_questionnaire' => 'Ссылка на анкету',
            'contact_information' => 'Контактная информация',
            'user_id' => 'User ID',
            'date_of_creation' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
