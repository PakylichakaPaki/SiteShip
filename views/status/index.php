<?php

use app\models\Status;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\StatusSearch $searchModel */

if (Yii::$app->user->isGuest): ?>
    <?php return Yii::$app->response->redirect(['/site/error-access']); ?>
<?php endif; ?>

<?php $this->title = 'Статусы'; ?>
<div class="status-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="m-0">Статусы</h4>
                        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
                            <?= Html::a('<i class="fas fa-plus"></i> Создать статус', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'class' => ActionColumn::className(),
                                    'urlCreator' => function ($action, Status $model, $key, $index, $column) {
                                        $user = Yii::$app->user->identity;
                                        if ($user && ($user->role->code === 'admin')) {
                                            return Url::toRoute([$action, 'id' => $model->id]);
                                        }
                                        // Если пользователь не имеет нужных ролей, возвращаем null или пустую строку
                                        return null;
                                    },
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
                                                    'confirm' => 'Вы уверены, что хотите удалить этот статус?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],
                                    'visible' => Yii::$app->user->identity->roleMiddleware('admin'),
                                ],
                            ],
                            'pager' => [
                                'class' => LinkPager::class,
                                'options' => ['class' => 'pagination'], // Основной класс для контейнера пагинации
                                'linkOptions' => ['class' => 'page-link'], // Класс для ссылок
                                'disabledPageCssClass' => 'disabled', // Класс для неактивных ссылок
                                'activePageCssClass' => 'active', // Класс для активной ссылки
                                'prevPageLabel' => '<i class="fas fa-angle-left"></i>', // Одинарная стрелка влево
                                'nextPageLabel' => '<i class="fas fa-angle-right"></i>', // Одинарная стрелка вправо
                                'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>', // Двойная стрелка влево
                                'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>', // Двойная стрелка вправо
                                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'], // Использовать <a> для неактивных кнопок
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'], // Таблица с полосами и курсором при наведении
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-index .card {
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .status-index .card-header {
        border-radius: 10px 10px 0 0;
    }

    .status-index .btn {
        border-radius: 5px;
    }

    .status-index .grid-view {
        overflow-x: auto;
    }

    .status-index .table th {
        background-color: #f8f9fa;
    }

    .status-index .table td {
        vertical-align: middle;
    }

    .status-index .action-column {
        white-space: nowrap;
    }

    .status-index .btn-sm {
        padding: 0.25rem 0.5rem;
        margin-right: 0.25rem;
    }

    .status-index .fas {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .status-index .card-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
