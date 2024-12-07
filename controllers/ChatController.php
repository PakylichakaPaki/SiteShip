<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Message;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Expression;

class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-message' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Message::ensureSchemaUpToDate();
        
        // Получаем ID пользователей, с которыми есть диалоги
        $userIds = Message::find()
            ->select(['user_id' => new Expression('CASE 
                WHEN sender_id = :currentUser THEN recipient_id 
                ELSE sender_id 
                END')])
            ->where(['or', 
                ['sender_id' => Yii::$app->user->id],
                ['recipient_id' => Yii::$app->user->id]
            ])
            ->params([':currentUser' => Yii::$app->user->id])
            ->distinct()
            ->column();

        // Получаем пользователей с диалогами
        $users = User::find()
            ->where(['id' => $userIds])
            ->all();

        return $this->render('index', [
            'users' => $users,
        ]);
    }

    public function actionLoadChatAjax($targetUserId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $targetUser = User::findOne($targetUserId);
            if (!$targetUser) {
                throw new \Exception('Пользователь не найден');
            }

            // Получаем сообщения
            $messages = Message::find()
                ->where([
                    'or',
                    [
                        'sender_id' => Yii::$app->user->id,
                        'recipient_id' => $targetUserId
                    ],
                    [
                        'sender_id' => $targetUserId,
                        'recipient_id' => Yii::$app->user->id
                    ]
                ])
                ->orderBy(['created_at' => SORT_ASC])
                ->all();

            // Отмечаем сообщения как прочитанные
            Message::updateAll(
                ['is_read' => 1],
                [
                    'sender_id' => $targetUserId,
                    'recipient_id' => Yii::$app->user->id,
                    'is_read' => 0
                ]
            );

            // Рендерим частичные представления
            $header = $this->renderPartial('_chat_header', ['user' => $targetUser]);
            $messagesHtml = '';
            foreach ($messages as $message) {
                $messagesHtml .= $this->renderPartial('_message', ['message' => $message]);
            }

            return [
                'success' => true,
                'header' => $header,
                'messages' => $messagesHtml,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionSendMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $recipientId = Yii::$app->request->post('recipientId');
            $messageText = Yii::$app->request->post('message');

            if (!$recipientId || !$messageText) {
                throw new \Exception('Не указан получатель или текст сообщения');
            }

            $message = new Message();
            $message->sender_id = Yii::$app->user->id;
            $message->recipient_id = $recipientId;
            $message->message = $messageText;
            $message->is_read = 0;

            if (!$message->save()) {
                throw new \Exception('Ошибка при сохранении сообщения');
            }

            return [
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'html' => $this->renderPartial('_message', ['message' => $message])
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionGetMessages($userId, $lastMessageId = 0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $messages = Message::find()
                ->where([
                    'and',
                    [
                        'or',
                        [
                            'sender_id' => Yii::$app->user->id,
                            'recipient_id' => $userId
                        ],
                        [
                            'sender_id' => $userId,
                            'recipient_id' => Yii::$app->user->id
                        ]
                    ],
                    ['>', 'id', $lastMessageId]
                ])
                ->orderBy(['created_at' => SORT_ASC])
                ->all();

            $result = [];
            foreach ($messages as $message) {
                $result[] = [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'message' => Html::encode($message->message),
                    'created_at' => Yii::$app->formatter->asDatetime($message->created_at),
                    'html' => $this->renderPartial('_message', ['message' => $message])
                ];
            }

            // Отмечаем сообщения как прочитанные
            Message::updateAll(
                ['is_read' => 1],
                [
                    'sender_id' => $userId,
                    'recipient_id' => Yii::$app->user->id,
                    'is_read' => 0
                ]
            );

            return ['messages' => $result];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionGetUnreadCount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $counts = Message::find()
                ->select(['sender_id', 'COUNT(*) as count'])
                ->where([
                    'recipient_id' => Yii::$app->user->id,
                    'is_read' => 0
                ])
                ->groupBy(['sender_id'])
                ->asArray()
                ->all();

            $result = [];
            foreach ($counts as $count) {
                $result[$count['sender_id']] = (int)$count['count'];
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionEditMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $messageId = Yii::$app->request->post('id');
            $newText = Yii::$app->request->post('message');

            if (!$messageId || !$newText) {
                throw new \Exception('Не указан ID сообщения или новый текст');
            }

            $message = Message::findOne($messageId);
            if (!$message) {
                throw new \Exception('Сообщение не найдено');
            }

            // Проверяем, что пользователь редактирует свое сообщение
            if ($message->sender_id !== Yii::$app->user->id) {
                throw new \Exception('Вы не можете редактировать чужие сообщения');
            }

            $message->message = $newText;
            if (!$message->save()) {
                throw new \Exception('Ошибка при сохранении сообщения');
            }

            return [
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'text' => Html::encode($message->message)
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionDeleteMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $messageId = Yii::$app->request->post('id');

            if (!$messageId) {
                throw new \Exception('Не указан ID сообщения');
            }

            $message = Message::findOne($messageId);
            if (!$message) {
                throw new \Exception('Сообщение не найдено');
            }

            // Проверяем, что пользователь удаляет свое сообщение
            if ($message->sender_id !== Yii::$app->user->id) {
                throw new \Exception('Вы не можете удалять чужие сообщения');
            }

            if (!$message->delete()) {
                throw new \Exception('Ошибка при удалении сообщения');
            }

            return [
                'success' => true
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionGetMessage($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $message = Message::findOne($id);
            if (!$message) {
                throw new \Exception('Сообщение не найдено');
            }

            // Проверяем, что пользователь имеет доступ к сообщению
            if ($message->sender_id !== Yii::$app->user->id && $message->recipient_id !== Yii::$app->user->id) {
                throw new \Exception('У вас нет доступа к этому сообщению');
            }

            return [
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'text' => Html::encode($message->message)
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function actionSearchUsers($query = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $users = User::find()
                ->where(['not', ['id' => Yii::$app->user->id]]) // Исключаем текущего пользователя
                ->andWhere(['or',
                    ['like', 'login', $query],
                    ['like', 'name', $query],
                    ['like', 'surname', $query],
                    ['like', 'patronymic', $query]
                ])
                ->limit(10)
                ->all();

            $results = [];
            foreach ($users as $user) {
                $results[] = [
                    'id' => $user->id,
                    'text' => $user->surname . ' ' . $user->name . ($user->patronymic ? ' ' . $user->patronymic : ''),
                    'login' => $user->login,
                    'phone' => $user->phone
                ];
            }

            return [
                'success' => true,
                'results' => $results
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}