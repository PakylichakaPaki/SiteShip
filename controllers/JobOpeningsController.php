<?php

namespace app\controllers;

use app\models\JobOpenings;
use app\models\JobOpeningsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * JobOpeningsController implements the CRUD actions for JobOpenings model.
 */
class JobOpeningsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all JobOpenings models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JobOpeningsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JobOpenings model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new JobOpenings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JobOpenings();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->date_of_creation = date('Y-m-d H:i:s');
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing JobOpenings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JobOpenings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Обновляет дату создания вакансии
     * @param int $id ID вакансии
     * @return mixed
     */
    public function actionRenew($id)
    {
        $model = $this->findModel($id);
        
        // Проверяем, что текущий пользователь является владельцем вакансии
        if ($model->user_id !== Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException('У вас нет прав для выполнения этого действия.');
        }

        $model->date_of_creation = date('Y-m-d H:i:s');
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Вакансия успешно обновлена.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при обновлении вакансии.');
        }

        return $this->redirect(['profile/index']);
    }

    /**
     * Finds the JobOpenings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return JobOpenings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JobOpenings::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->user->isGuest && !in_array($action->id, ['login', 'signup', 'index'])) {
                return $this->redirect(['/site/error-access']);
            }
            return true;
        }
        return false;
    }
}
