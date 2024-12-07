<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $patronymic
 * @property string $phone
 * @property string $login
 * @property string $password
 * @property int|null $role_id
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'login', 'phone'], 'required'],
            [['role_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['surname', 'name', 'patronymic', 'password'], 'string', 'max' => 255],
            [['login'], 'string'],
            [['phone'], 'string', 'max' => 20],
            [['password'], 'string', 'min' => 6],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            // Делаем поле password необязательным при обновлении
            [['password'], 'default', 'value' => null],
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
            'phone' => 'Телефон',
            'login' => 'Логин',
            'password' => 'Пароль',
            'role_id' => 'Роль',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * Gets role code
     *
     * @return string
     */
    public function getRoleCode()
    {
        return $this->role ? $this->role->code : null;
    }

    /**
     * Gets query for job openings created by this user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJobOpenings()
    {
        return $this->hasMany(JobOpenings::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for candidates created by this user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCandidates()
    {
        return $this->hasMany(Candidate::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ServiceOrders]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[ServiceRequests]].
     *
     * @return \yii\db\ActiveQuery
     */


    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function getAuthKey()
    {
        return null;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($login)
    {
        return User::findOne(['login' => $login]);
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    function roleMiddleware($roles): bool
    {
        return in_array(Yii::$app->user->identity->role->code, explode('|', $roles), true);
    }

    /**
     * Устанавливает пароль
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function getAvatarUrl()
    {
        // Возвращает URL аватара пользователя
        return 'https://media.discordapp.net/attachments/775787939304177704/1267474926621692005/Default_avatar_profile.png?ex=66a8eb83&is=66a79a03&hm=60c8edacfa5d2ba7a3e7d3bfe622dc572bb4ea9ee28b01da4f3edc6ba39bb670&=&format=webp&quality=lossless&width=662&height=662'; // Пример URL
    }

    /**
     * Gets user's email (alias for login)
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->login;
    }

    /**
     * Sets user's email (alias for login)
     *
     * @param string $value
     */
    public function setEmail($value)
    {
        $this->login = $value;
    }

    /**
     * Gets username (alias for login)
     * @return string
     */
    public function getUsername()
    {
        return $this->login;
    }

    public function __get($name)
    {
        if ($name === 'username') {
            return $this->getUsername();
        }
        return parent::__get($name);
    }

}
