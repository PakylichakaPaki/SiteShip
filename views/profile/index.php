<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Мой профиль';
?>

<div class="profile-page">
    <div class="container py-5">
        <div class="row">
            <!-- Левая колонка с аватаром и основной информацией -->
            <div class="col-lg-4">
                <div class="card mb-4 profile-card">
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            <div class="avatar-circle">
                                <?= mb_strtoupper(mb_substr($model->name, 0, 1, 'UTF-8'), 'UTF-8') ?>
                            </div>
                        </div>
                        <h5 class="profile-name mb-1"><?= Html::encode($model->surname . ' ' . $model->name) ?></h5>
                        <p class="text-muted mb-3">
                            <?php
                            $roles = [
                                'admin' => 'Администратор',
                                'client' => 'Работодатель',
                                'executor' => 'Соискатель',
                                'controller' => 'Контролер'
                            ];
                            $roleCode = $model->getRoleCode();
                            echo isset($roles[$roleCode]) ? $roles[$roleCode] : 'Пользователь';
                            ?>
                        </p>
                        <div class="profile-status">
                            <span class="badge bg-success">Онлайн</span>
                        </div>
                    </div>
                </div>

                <!-- Контактная информация -->
                <div class="card mb-4 profile-contact">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><i class="fas fa-address-card me-2"></i>Контактная информация</h6>
                        <div class="contact-item mb-3">
                            <p class="contact-label mb-1"><i class="fas fa-envelope me-2"></i>Login</p>
                            <p class="text-muted mb-0"><?= Html::encode($model->login) ?></p>
                        </div>
                        <div class="contact-item mb-3">
                            <p class="contact-label mb-1"><i class="fas fa-phone me-2"></i>Телефон</p>
                            <p class="text-muted mb-0"><?= Html::encode($model->phone) ?></p>
                        </div>
                        <?php if ($model->role): ?>
                        <div class="contact-item">
                            <p class="contact-label mb-1"><i class="fas fa-user-tag me-2"></i>Роль</p>
                            <p class="text-muted mb-0"><?= Html::encode($model->role->name) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Правая колонка с формой редактирования -->
            <div class="col-lg-8">
                <?= $this->render('_job_openings', ['model' => $model]) ?>
                <?= $this->render('_candidates', ['model' => $model]) ?>
                
                <div class="card profile-edit-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Редактировать профиль</h5>
                    </div>
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['options' => ['class' => 'profile-form']]); ?>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <?= $form->field($model, 'surname', [
                                    'options' => ['class' => 'form-group'],
                                    'template' => '{label}<div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>{input}</div>{error}'
                                ])->textInput(['class' => 'form-control']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'name', [
                                    'options' => ['class' => 'form-group'],
                                    'template' => '{label}<div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>{input}</div>{error}'
                                ])->textInput(['class' => 'form-control']) ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone', [
                                    'options' => ['class' => 'form-group'],
                                    'template' => '{label}<div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>{input}</div>{error}'
                                ])->textInput(['class' => 'form-control']) ?>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <?= $form->field($model, 'patronymic', [
                                        'options' => ['class' => 'form-group'],
                                        'template' => '{label}<div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>{input}</div>{error}'
                                    ])->textInput(['class' => 'form-control']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <?= $form->field($model, 'login', [
                                    'options' => ['class' => 'form-group'],
                                    'template' => '{label}<div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>{input}</div>{error}'
                                ])->textInput(['class' => 'form-control']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'password', [
                                    'options' => ['class' => 'form-group'],
                                    'template' => '{label}<div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>{input}</div>{error}'
                                ])->passwordInput(['class' => 'form-control', 'placeholder' => 'Оставьте пустым, если не хотите менять']) ?>
                            </div>
                        </div>

                        <div class="form-group text-end">
                            <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-primary btn-lg px-5']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Основные стили */
.profile-page {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Карточка профиля */
.profile-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

/* Аватар */
.avatar-circle {
    width: 150px;
    height: 150px;
    background: linear-gradient(45deg, #2196F3, #00BCD4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 64px;
    font-weight: 500;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
}

.avatar-circle:hover {
    transform: scale(1.05);
}

/* Имя профиля */
.profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

/* Карточка контактов */
.profile-contact {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.contact-label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

/* Карточка редактирования */
.profile-edit-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.profile-edit-card .card-header {
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding: 1.5rem;
}

.profile-edit-card .card-body {
    padding: 2rem;
}

/* Форма */
.form-group {
    margin-bottom: 1.5rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    color: #6c757d;
}

.form-control {
    border: 1px solid #ced4da;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 0.375rem;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

/* Кнопка */
.btn-primary {
    background: linear-gradient(45deg, #2196F3, #00BCD4);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
}

/* Бейдж статуса */
.profile-status .badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
    border-radius: 30px;
}

/* Анимации */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* Адаптивность */
@media (max-width: 992px) {
    .profile-card, .profile-contact {
        margin-bottom: 2rem;
    }
}

@media (max-width: 768px) {
    .avatar-circle {
        width: 120px;
        height: 120px;
        font-size: 48px;
    }
    
    .profile-edit-card .card-body {
        padding: 1.5rem;
    }
}

/* Иконки в инпутах */
.input-group-text i {
    width: 16px;
    text-align: center;
    color: #6c757d;
}

/* Тени для текста */
.card-title {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}
</style>