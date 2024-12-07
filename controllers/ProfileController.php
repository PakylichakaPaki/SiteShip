<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;

class ProfileController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = User::findOne(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post())) {
            // Проверяем, был ли введен новый пароль
            $newPassword = Yii::$app->request->post('User')['password'] ?? '';
            if (!empty($newPassword)) {
                $model->setPassword($newPassword);
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Профиль успешно обновлен.');
                return $this->refresh();
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}