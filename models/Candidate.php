<?php

namespace app\models;

use Yii;
use app\models\JobOpenings;

/**
 * This is the model class for table "candidate".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $patronymic
 * @property string $desired_position
 * @property string $phone
 * @property string $email
 * @property string $medical_card
 * @property string $resume_link
 * @property string $work_experience
 * @property int $user_id
 * @property int $status_id
 * @property int|null $job_opening_id
 * @property string|null $created_at
 *
 * @property Status $status
 * @property User $user
 * @property JobOpening $jobOpening
 */
class Candidate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'candidate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'phone', 'email', 'status_id'], 'required'],
            [['work_experience', 'desired_position', 'medical_card'], 'string'],
            [['status_id', 'user_id', 'job_opening_id'], 'integer'],
            [['created_at'], 'safe'],
            [['surname', 'name', 'patronymic', 'phone', 'email', 'resume_link'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['job_opening_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobOpenings::class, 'targetAttribute' => ['job_opening_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'desired_position' => 'Желаемая должность',
            'phone' => 'Телефон',
            'email' => 'Email',
            'medical_card' => 'Медицинская карта',
            'resume_link' => 'Ссылка на анкету',
            'work_experience' => 'Опыт работы',
            'user_id' => 'Заявитель',
            'status_id' => 'Статус',
            'job_opening_id' => 'Вакансия',
            'created_at' => 'Дата создания'
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
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

    public static function getUsers()
    {
        return User::find()->select(['surname', 'id'])->indexBy('id')->column();
    }

    public function getJobOpening()
    {
        return $this->hasOne(JobOpening::class, ['id' => 'job_opening_id']);
    }

    public static function getStatuses()
    {
        return Status::find()->select(['name', 'id'])->indexBy('id')->column();
    }

    public function getJobOpenings()
    {
        return $this->hasOne(JobOpenings::class, ['id' => 'job_opening_id']);
    }

    /**
     * Возвращает время до истечения кандидата в формате относительной даты
     */
    public function getExpirationDate()
    {
        $timestamp = strtotime($this->created_at . ' +15 days');
        return $timestamp;
    }
}
