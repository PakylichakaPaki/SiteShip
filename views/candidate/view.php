<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */

\yii\web\YiiAsset::register($this);

// Проверяем роль пользователя
$currentUser = Yii::$app->user->identity;
$canManage = $currentUser && $currentUser->roleMiddleware('admin|controller');
?>
<div class="candidate-view">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h4 class="m-0">Данные кандидата</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($canManage): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-lg w-100']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger btn-lg w-100',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этого кандидата?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => 'ID кандидата',
                                    'value' => $model->id,
                                ],
                                [
                                    'label' => 'Фамилия',
                                    'value' => $model->surname,
                                ],
                                [
                                    'label' => 'Имя',
                                    'value' => $model->name,
                                ],
                                [
                                    'label' => 'Отчество',
                                    'value' => $model->patronymic,
                                ],
                                [
                                    'label' => 'Желаемая должность',
                                    'value' => $model->desired_position,
                                ],
                                [
                                    'label' => 'Телефон',
                                    'value' => $model->phone,
                                ],
                                [
                                    'label' => 'Email',
                                    'value' => $model->email,
                                ],
                                [
                                    'label' => 'Медицинская карта',
                                    'format' => 'raw',
                                    'value' => $model->medical_card ? Html::a('Открыть', $model->medical_card, ['target' => '_blank', 'class' => 'btn btn-primary btn-sm']) : 'Не указана',
                                ],
                                [
                                    'label' => 'Анкета',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        try {
                                            return $model->resume_link ? Html::a('Открыть', $model->resume_link, ['target' => '_blank', 'class' => 'btn btn-primary btn-sm']) : 'Не указана';
                                        } catch (\Exception $e) {
                                            return 'Не указана';
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Опыт работы',
                                    'value' => $model->work_experience,
                                ],
                                [
                                    'label' => 'Заявитель',
                                    'value' => function($model) {
                                        try {
                                            return $model->user ? $model->user->username : 'Не указан';
                                        } catch (\Exception $e) {
                                            return 'Не указан';
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Статус',
                                    'value' => $model->status->name,
                                ],
                            ],
                            'options' => [
                                'class' => 'table table-bordered table-striped',
                            ],
                        ]) ?>

                        <div class="mt-4 text-center">
                            <?= Html::a('Назад к кандидатам', ['index'], ['class' => 'btn btn-outline-dark btn-lg']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .candidate-view .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .candidate-view h1 {
        font-weight: bold;
        color: #333;
    }

    .candidate-view .card-header {
        font-size: 1.5rem;
        text-align: center;
    }

    .candidate-view .table th,
    .candidate-view .table td {
        font-size: 1.1rem;
        padding: 15px;
    }

    .candidate-view .table-bordered {
        border: 1px solid #ddd;
    }

    .candidate-view .table-striped tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .candidate-view .btn {
        font-size: 1.2rem;
        padding: 12px;
        border-radius: 5px;
    }

    .candidate-view .btn-lg {
        font-size: 1.25rem;
    }
</style>
