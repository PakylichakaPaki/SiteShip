<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->name . ' ' . $model->surname;
\yii\web\YiiAsset::register($this);

// Определяем, может ли текущий пользователь видеть пароль
$canViewPassword = Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin|controller');

// Проверяем, является ли текущий пользователь администратором
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('admin');

// Формируем массив атрибутов
$attributes = [
    [
        'attribute' => 'id',
        'label' => 'ID пользователя',
    ],
    [
        'attribute' => 'surname',
        'label' => 'Фамилия',
    ],
    [
        'attribute' => 'name',
        'label' => 'Имя',
    ],
    [
        'attribute' => 'patronymic',
        'label' => 'Отчество',
    ],
    [
        'attribute' => 'phone',
        'label' => 'Телефон',
    ],
    [
        'attribute' => 'role_id',
        'label' => 'Роль',
        'value' => function($model) {
            return $model->role ? $model->role->name : 'Не указана';
        },
    ],
    [
        'attribute' => 'login',
        'label' => 'Логин',
    ],
];

// Добавляем пароль сразу после логина, если у пользователя есть права
if ($canViewPassword) {
    $attributes[] = [
        'attribute' => 'password',
        'label' => 'Пароль',
    ];
}
?>
<div class="user-view">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h4 class="m-0">Информация о пользователе: <?= Html::encode($model->surname . ' ' . $model->name) ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if ($isAdmin): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <?= Html::a('<i class="fas fa-edit"></i> Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-lg w-100']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-outline-danger btn-lg w-100',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => [
                                'class' => 'table table-striped table-bordered detail-view',
                            ],
                            'attributes' => $attributes,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .user-view .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .user-view .card-header {
        border-radius: 10px 10px 0 0;
    }
    
    .user-view .table {
        margin-bottom: 0;
    }
    
    .user-view .detail-view th {
        width: 30%;
        background-color: #f8f9fa;
    }
    
    .user-view .btn {
        border-radius: 5px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .user-view .btn i {
        margin-right: 8px;
    }
    
    @media (max-width: 767px) {
        .user-view .col-md-6 {
            margin-bottom: 1rem;
        }
        .user-view .col-md-6:last-child {
            margin-bottom: 0;
        }
    }
</style>
