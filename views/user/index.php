<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

if (Yii::$app->user->isGuest): ?>
    <?php return Yii::$app->response->redirect(['/site/error-access']); ?>
<?php endif; ?>

<?php $this->title = 'Пользователи'; ?>
<div class="user-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="m-0">Пользователи</h4>
                        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
                            <?= Html::a('<i class="fas fa-plus"></i> Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'attribute' => 'login',
                                    'label' => 'Логин',
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'phone',
                                    'label' => 'Телефон',
                                    'contentOptions' => ['style' => 'width: 15%;'],
                                ],
                                [
                                    'attribute' => 'role_id',
                                    'label' => 'Роль',
                                    'value' => function($model) {
                                        return $model->role ? $model->role->name : 'Не указана';
                                    },
                                    'contentOptions' => ['style' => 'width: 10%;'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width: 10%;'],
                                    'template' => '{view} {update} {delete}',
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
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],
                                    'visibleButtons' => [
                                        'view' => true,
                                        'update' => function($model) {
                                            return Yii::$app->user->identity->roleMiddleware('admin');
                                        },
                                        'delete' => function($model) {
                                            return Yii::$app->user->identity->roleMiddleware('admin');
                                        },
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
    .user-index .card {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .user-index .card-header {
        border-radius: 10px 10px 0 0;
    }

    .user-index .btn {
        border-radius: 5px;
    }

    .user-index .grid-view {
        overflow-x: auto;
    }

    .user-index .table th {
        background-color: #f8f9fa;
    }

    .user-index .table td {
        vertical-align: middle;
    }

    .user-index .action-column {
        white-space: nowrap;
    }

    .user-index .btn-sm {
        padding: 0.25rem 0.5rem;
        margin-right: 0.25rem;
    }

    .user-index .fas {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .user-index .card-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
