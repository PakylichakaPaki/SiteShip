<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\CandidateSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

if (Yii::$app->user->isGuest): ?>
    <?php return Yii::$app->response->redirect(['/site/error-access']); ?>
<?php endif; ?>

<?php $this->title = 'Кандидаты'; ?>
<div class="candidate-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="m-0">Кандидаты</h4>
                        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin|executor')): ?>
                            <?= Html::a('<i class="fas fa-plus"></i> Создать кандидата', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'attribute' => 'surname',
                                    'label' => 'Фамилия',
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'name',
                                    'label' => 'Имя',
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'patronymic',
                                    'label' => 'Отчество',
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'phone',
                                    'label' => 'Телефон',
                                    'contentOptions' => ['style' => 'width: 10%;'],
                                ],
                                [
                                    'attribute' => 'email',
                                    'label' => 'Email',
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'status_id',
                                    'label' => 'Статус',
                                    'value' => function($model) {
                                        return $model->status ? $model->status->name : 'Не указан';
                                    },
                                    'contentOptions' => ['style' => 'width: 10%;'],
                                ],
                                [
                                    'attribute' => 'job_opening_id',
                                    'label' => 'Вакансия',
                                    'value' => function($model) {
                                        return $model->jobOpenings ? $model->jobOpenings->title_of_the_position : null;
                                    },
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width: 5%;'],
                                    'template' => '{view} {update} {delete} {respond}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-eye text-primary"></i>', $url, [
                                                'title' => 'Просмотр',
                                                'class' => 'btn btn-sm btn-outline-primary mr-1'
                                            ]);
                                        },
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-edit text-success"></i>', $url, [
                                                'title' => 'Редактировать',
                                                'class' => 'btn btn-sm btn-outline-success mr-1'
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-trash text-danger"></i>', $url, [
                                                'title' => 'Удалить',
                                                'class' => 'btn btn-sm btn-outline-danger mr-1',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить этого кандидата?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                        'respond' => function ($url, $model) {
                                            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('client')) {
                                                return Html::a('<i class="fas fa-comments"></i>', ['/chat/index', 'userId' => $model->user_id], [
                                                    'title' => 'Связаться с соискателем',
                                                    'class' => 'btn btn-sm btn-outline-primary'
                                                ]);
                                            }
                                            return '';
                                        },
                                    ],
                                    'visibleButtons' => [
                                        'view' => true,
                                        'update' => function($model) {
                                            return Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin|controller');
                                        },
                                        'delete' => function($model) {
                                            return Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin|controller');
                                        },
                                        'respond' => function($model) {
                                            return Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('client');
                                        }
                                    ],
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-bordered'],
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
    .candidate-index .card {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .candidate-index .card-header {
        border-radius: 10px 10px 0 0;
    }

    .candidate-index .btn {
        border-radius: 5px;
    }

    .candidate-index .grid-view {
        overflow-x: auto;
    }

    .candidate-index .table th {
        background-color: #f8f9fa;
    }

    .candidate-index .table td {
        vertical-align: middle;
    }

    .candidate-index .action-column {
        white-space: nowrap;
    }

    .candidate-index .btn-sm {
        padding: 0.25rem 0.5rem;
        margin-right: 0.25rem;
    }

    .candidate-index .fas {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .candidate-index .card-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>