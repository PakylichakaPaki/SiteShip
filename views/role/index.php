<?php

use app\models\Role;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\RoleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

if (Yii::$app->user->isGuest): ?>
    <?php return Yii::$app->response->redirect(['/site/error-access']); ?>
<?php endif; ?>

<?php $this->title = 'Роли'; ?>
<div class="role-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="m-0">Роли</h4>
                        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
                            <?= Html::a('<i class="fas fa-plus"></i> Создать роль', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'attribute' => 'name',
                                    'label' => 'Название',
                                    'contentOptions' => ['style' => 'width: 45%;'],
                                ],
                                [
                                    'attribute' => 'code',
                                    'label' => 'Код',
                                    'contentOptions' => ['style' => 'width: 35%;'],
                                ],
                                [
                                    'class' => ActionColumn::className(),
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
                                                    'confirm' => 'Вы уверены, что хотите удалить эту роль?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],
                                    'visible' => Yii::$app->user->identity->roleMiddleware('admin'),
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .role-index .card {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .role-index .card-header {
        border-radius: 10px 10px 0 0;
    }

    .role-index .btn {
        border-radius: 5px;
    }

    .role-index .grid-view {
        overflow-x: auto;
    }

    .role-index .table th {
        background-color: #f8f9fa;
    }

    .role-index .table td {
        vertical-align: middle;
    }

    .role-index .action-column {
        white-space: nowrap;
    }

    .role-index .btn-sm {
        padding: 0.25rem 0.5rem;
        margin-right: 0.25rem;
    }

    .role-index .fas {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .role-index .card-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
