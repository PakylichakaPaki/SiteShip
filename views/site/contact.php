<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Контакты';
?>
<div class="site-contact">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

                <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                    <div class="alert alert-success">
                        Спасибо за ваше сообщение. Мы свяжемся с вами в ближайшее время.
                    </div>
                <?php else: ?>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                    <h5 class="card-title">Телефон</h5>
                                    <p class="card-text">
                                        <a href="tel:+79785556677" class="text-decoration-none">+7 (978) 555-66-77</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                    <h5 class="card-title">Email</h5>
                                    <p class="card-text">
                                        <a href="mailto:info@siteship.ru" class="text-decoration-none">info@siteship.ru</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                                    <h5 class="card-title">Адрес</h5>
                                    <p class="card-text">
                                        г. Севастополь,<br>
                                        ул. Морская, 15
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Форма обратной связи</h5>
                            <p class="card-text mb-4">
                                Если у вас есть вопросы или предложения, пожалуйста, заполните форму ниже.
                                Мы свяжемся с вами в ближайшее время.
                            </p>

                            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'name')->textInput([
                                        'autofocus' => true,
                                        'placeholder' => 'Введите ваше имя',
                                        'class' => 'form-control rounded-pill'
                                    ])->label('Ваше имя') ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'email')->textInput([
                                        'placeholder' => 'Введите ваш email',
                                        'class' => 'form-control rounded-pill'
                                    ])->label('Email') ?>
                                </div>
                            </div>

                            <?= $form->field($model, 'subject')->textInput([
                                'placeholder' => 'Тема сообщения',
                                'class' => 'form-control rounded-pill'
                            ])->label('Тема') ?>

                            <?= $form->field($model, 'body')->textarea([
                                'rows' => 6,
                                'placeholder' => 'Введите ваше сообщение',
                                'class' => 'form-control rounded'
                            ])->label('Сообщение') ?>

                            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                                'options' => ['class' => 'form-control rounded-pill', 'placeholder' => 'Введите код с картинки']
                            ])->label('Проверочный код') ?>

                            <div class="form-group text-center">
                                <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary rounded-pill px-4', 'name' => 'contact-button']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
