<?php

namespace app\models;

use Yii;

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
 *
 * @property User $user
 */
class JobOpenings extends \yii\db\ActiveRecord
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
    public function rules()
    {
        return [
            [['title_of_the_position', 'salary', 'term_of_employment', 'user_id'], 'required'],
            [['salary'], 'number'],
            [['user_id'], 'integer'],
            [['title_of_the_position', 'company_name', 'contact_information'], 'string', 'max' => 256],
            [['term_of_employment'], 'string', 'max' => 32],
            [['link_to_the_questionnaire'], 'string', 'max' => 512],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_of_the_position' => 'Title Of The Position',
            'salary' => 'Salary',
            'term_of_employment' => 'Term Of Employment',
            'company_name' => 'Company Name',
            'link_to_the_questionnaire' => 'Link To The Questionnaire',
            'contact_information' => 'Contact Information',
            'user_id' => 'User ID',
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
