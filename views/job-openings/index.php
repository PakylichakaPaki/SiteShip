<?php

use app\models\JobOpenings;
use app\models\JobOpeningsSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var yii\web\View $this */
/* @var app\models\JobOpeningsSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

if (Yii::$app->user->isGuest): ?>
    <?php return Yii::$app->response->redirect(['/site/error-access']); ?>
<?php endif; ?>

<?php $this->title = 'Вакансии'; ?>
<div class="job-openings-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="m-0"><?= Html::encode($this->title) ?></h4>
                        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin|client')): ?>
                            <?= Html::a('<i class="fas fa-plus"></i> Создать вакансию', ['create'], ['class' => 'btn btn-success']) ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['class' => 'table-responsive'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'visible' => Yii::$app->user->identity->role_id == 1 || Yii::$app->user->identity->role_id == 2,
                                    'headerOptions' => ['class' => 'text-center'],
                                    'contentOptions' => ['class' => 'text-center', 'style' => 'width: 5%;'],
                                ],
                                [
                                    'attribute' => 'title_of_the_position',
                                    'label' => 'Название должности',
                                    'filter' => true,
                                    'headerOptions' => ['style' => 'width:20%'],
                                    'contentOptions' => ['style' => 'width: 20%;'],
                                ],
                                [
                                    'attribute' => 'salary',
                                    'label' => 'Зарплата',
                                    'filter' => true,
                                    'value' => function ($model) {
                                        return number_format($model->salary, 0, '.', ' ') . ' ₽';
                                    },
                                    'headerOptions' => ['style' => 'width:15%'],
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'term_of_employment',
                                    'label' => 'Условия работы',
                                    'filter' => JobOpeningsSearch::getTermsOfEmployment(),
                                    'headerOptions' => ['style' => 'width:15%'],
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'company_name',
                                    'label' => 'Название компании',
                                    'filter' => true,
                                    'headerOptions' => ['style' => 'width:15%'],
                                    'contentOptions' => ['style' => 'width: 20%;'],
                                ],
                                [
                                    'attribute' => 'contact_information',
                                    'label' => 'Контактная информация',
                                    'filter' => true,
                                    'headerOptions' => ['style' => 'width:20%'],
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Публикатор',
                                    'value' => function ($model) {
                                        return $model->user ? $model->user->surname . ' ' . $model->user->name : 'Не указан';
                                    },
                                    'headerOptions' => ['style' => 'width:15%'],
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'link_to_the_questionnaire',
                                    'label' => 'Ссылка на анкету',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->link_to_the_questionnaire ? Html::a($model->link_to_the_questionnaire, $model->link_to_the_questionnaire, ['target' => '_blank']) : 'Не указана';
                                    },
                                    'headerOptions' => ['style' => 'width:15%'],
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width: 10%;'],
                                    'template' => '{view} {update} {delete} {respond}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                                'title' => 'Просмотр',
                                                'class' => 'btn btn-primary btn-action'
                                            ]);
                                        },
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-edit"></i>', $url, [
                                                'title' => 'Редактировать',
                                                'class' => 'btn btn-info btn-action'
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                                                'title' => 'Удалить',
                                                'class' => 'btn btn-danger btn-action',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить эту вакансию?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                        'respond' => function ($url, $model) {
                                            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('executor')) {
                                                return Html::a('<i class="fas fa-comments"></i>', ['/chat/index', 'userId' => $model->user_id], [
                                                    'title' => 'Связаться с работодателем',
                                                    'class' => 'btn btn-success btn-action'
                                                ]);
                                            }
                                            return '';
                                        },
                                    ],
                                    'visibleButtons' => [
                                        'view' => true,
                                        'update' => function($model) {
                                            return Yii::$app->user->identity->roleMiddleware('admin|controller');
                                        },
                                        'delete' => function($model) {
                                            return Yii::$app->user->identity->roleMiddleware('admin|controller');
                                        },
                                        'respond' => function($model) {
                                            return Yii::$app->user->identity->roleMiddleware('executor');
                                        },
                                    ],
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-bordered'],
                            'filterRowOptions' => ['class' => 'filters'],
                            'pager' => [
                                'class' => LinkPager::class,
                                'options' => ['class' => 'pagination justify-content-center'],
                                'linkContainerOptions' => ['class' => 'page-item'],
                                'linkOptions' => ['class' => 'page-link'],
                                'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .job-openings-index .card {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .job-openings-index .card-header {
        border-radius: 10px 10px 0 0;
    }

    .job-openings-index .btn {
        border-radius: 5px;
    }

    .job-openings-index .grid-view {
        overflow-x: auto;
    }

    .job-openings-index .table th {
        background-color: #f8f9fa;
    }

    .job-openings-index .table td {
        vertical-align: middle;
    }

    .job-openings-index .action-column {
        white-space: nowrap;
    }

    .job-openings-index .btn-sm {
        padding: 0.25rem 0.5rem;
        margin-right: 0.25rem;
    }

    .job-openings-index .fas {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .job-openings-index .card-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>