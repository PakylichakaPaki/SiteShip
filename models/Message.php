<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $recipient_id
 * @property string $message
 * @property string $created_at
 * @property int $is_read
 *
 * @property User $sender
 * @property User $recipient
 */
class Message extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => function() { 
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'recipient_id', 'message'], 'required'],
            [['sender_id', 'recipient_id', 'is_read'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['is_read'], 'default', 'value' => 0],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['sender_id' => 'id']],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'sender_id' => 'Отправитель',
            'recipient_id' => 'Получатель',
            'message' => 'Сообщение',
            'created_at' => 'Дата создания',
            'is_read' => 'Прочитано',
        ];
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Recipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::class, ['id' => 'recipient_id']);
    }

    /**
     * Ensure the message table schema is up to date
     */
    public static function ensureSchemaUpToDate()
    {
        $tableName = self::tableName();
        $db = Yii::$app->db;
        
        // Проверяем существование таблицы
        if (!$db->schema->getTableSchema($tableName)) {
            $db->createCommand()->createTable($tableName, [
                'id' => 'pk',
                'sender_id' => 'integer NOT NULL',
                'recipient_id' => 'integer NOT NULL',
                'message' => 'text NOT NULL',
                'created_at' => 'datetime NOT NULL',
                'is_read' => 'tinyint(1) NOT NULL DEFAULT 0',
            ])->execute();

            // Создаем индексы
            $db->createCommand()->createIndex(
                'idx-message-sender_id',
                $tableName,
                'sender_id'
            )->execute();

            $db->createCommand()->createIndex(
                'idx-message-recipient_id',
                $tableName,
                'recipient_id'
            )->execute();

            $db->createCommand()->createIndex(
                'idx-message-is_read',
                $tableName,
                'is_read'
            )->execute();

            // Добавляем внешние ключи
            $db->createCommand()->addForeignKey(
                'fk-message-sender_id',
                $tableName,
                'sender_id',
                'user',
                'id',
                'CASCADE'
            )->execute();

            $db->createCommand()->addForeignKey(
                'fk-message-recipient_id',
                $tableName,
                'recipient_id',
                'user',
                'id',
                'CASCADE'
            )->execute();
        }
    }
}
