<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "job_opening".
 *
 * @property int $id
 * @property string $position
 * @property string $company_name
 * @property string $description
 * @property string $requirements
 * @property int $user_id
 * @property int $status_id
 * @property string $created_at
 * @property string $last_verified_at
 * @property boolean $needs_verification
 * @property boolean $is_archived
 *
 * @property Candidate[] $candidates
 * @property Status $status
 * @property User $user
 */
class JobOpening extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_opening';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position', 'company_name', 'description', 'requirements', 'user_id'], 'required'],
            [['description', 'requirements'], 'string'],
            [['user_id', 'status_id'], 'integer'],
            [['created_at', 'last_verified_at'], 'safe'],
            [['needs_verification', 'is_archived'], 'boolean'],
            [['position', 'company_name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'position' => 'Должность',
            'company_name' => 'Название компании',
            'description' => 'Описание',
            'requirements' => 'Требования',
            'user_id' => 'Пользователь',
            'status_id' => 'Статус',
            'created_at' => 'Дата создания',
            'last_verified_at' => 'Дата последней верификации',
            'needs_verification' => 'Требует верификации',
            'is_archived' => 'В архиве',
        ];
    }

    /**
     * Gets query for [[Candidates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCandidates()
    {
        return $this->hasMany(Candidate::class, ['job_opening_id' => 'id']);
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

    /**
     * Подтверждает актуальность вакансии
     */
    public function verify()
    {
        $this->last_verified_at = new Expression('NOW()');
        $this->needs_verification = false;
        return $this->save();
    }

    /**
     * Проверяет, требует ли вакансия подтверждения
     */
    public function needsVerification()
    {
        return $this->needs_verification;
    }

    /**
     * Проверяет, находится ли вакансия в архиве
     */
    public function isArchived()
    {
        return $this->is_archived;
    }
}
