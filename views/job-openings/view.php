<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\JobOpenings $model */

$this->title = $model->title_of_the_position;
\yii\web\YiiAsset::register($this);

// Получаем текущего пользователя
$currentUser = Yii::$app->user->identity;
$isAdmin = $currentUser && $currentUser->roleMiddleware('admin');
$isOwner = $currentUser && $model->user_id == $currentUser->id;
?>
<div class="job-openings-view">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h4 class="m-0">Информация о вакансии: <?= Html::encode($model->title_of_the_position) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <?php if ($isAdmin || $isOwner): ?>
                            <div class="col-md-4">
                                <?= Html::a('<i class="fas fa-edit"></i> Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-lg w-100']) ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($isAdmin): ?>
                            <div class="col-md-4">
                                <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-outline-danger btn-lg w-100',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить эту вакансию?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!Yii::$app->user->isGuest && $currentUser->id != $model->user_id): ?>
                            <div class="col-md-4">
                                <?= Html::a('<i class="fas fa-comments"></i> Связаться', ['/chat/index', 'userId' => $model->user_id], [
                                    'class' => 'btn btn-outline-success btn-lg w-100',
                                    'title' => 'Связаться с работодателем'
                                ]) ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => [
                                'class' => 'table table-striped table-bordered detail-view',
                            ],
                            'attributes' => [
                                [
                                    'attribute' => 'id',
                                    'label' => 'ID вакансии',
                                ],
                                [
                                    'attribute' => 'title_of_the_position',
                                    'label' => 'Название должности',
                                ],
                                [
                                    'attribute' => 'salary',
                                    'label' => 'Зарплата',
                                    'value' => function($model) {
                                        return number_format($model->salary, 0, '.', ' ') . ' ₽';
                                    },
                                ],
                                [
                                    'attribute' => 'term_of_employment',
                                    'label' => 'Условия работы',
                                ],
                                [
                                    'attribute' => 'company_name',
                                    'label' => 'Название компании',
                                ],
                                [
                                    'attribute' => 'link_to_the_questionnaire',
                                    'label' => 'Ссылка на анкету',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return $model->link_to_the_questionnaire ? 
                                            Html::a($model->link_to_the_questionnaire, $model->link_to_the_questionnaire, ['target' => '_blank']) : 
                                            'Не указана';
                                    },
                                ],
                                [
                                    'attribute' => 'contact_information',
                                    'label' => 'Контактная информация',
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Публикатор',
                                    'value' => function($model) {
                                        return $model->user->surname . ' ' . $model->user->name;
                                    },
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .job-openings-view .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .job-openings-view .card-header {
        border-radius: 10px 10px 0 0;
    }
    
    .job-openings-view .detail-view {
        margin-top: 20px;
    }
    
    .job-openings-view .btn {
        margin-bottom: 10px;
    }
    
    @media (max-width: 768px) {
        .job-openings-view .btn {
            margin-bottom: 15px;
        }
    }
</style>
