<?php

namespace app\controllers;

use Yii;
use app\models\JobOpening;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class JobOpeningController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'verify' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Подтверждает актуальность вакансии
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionVerify($id)
    {
        $model = $this->findModel($id);
        
        // Проверяем, что текущий пользователь является владельцем вакансии
        if ($model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('У вас нет прав для выполнения этого действия.');
        }

        if ($model->verify()) {
            Yii::$app->session->setFlash('success', 'Актуальность вакансии подтверждена.');
        } else {
            Yii::$app->session->setFlash('error', 'Произошла ошибка при подтверждении актуальности.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the JobOpening model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JobOpening the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JobOpening::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
