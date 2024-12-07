<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\JobOpenings $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="job-openings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title_of_the_position')->textInput(['maxlength' => true])->label('Должность') ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => true])->label('Зарплата') ?>

    <?= $form->field($model, 'term_of_employment')->textInput(['maxlength' => true])->label('График работы') ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true])->label('Название компании') ?>

    <?= $form->field($model, 'link_to_the_questionnaire')->textInput(['maxlength' => true])->label('Ссылка на анкету') ?>

    <?= $form->field($model, 'contact_information')->textInput(['maxlength' => true])->label('Контактная информация') ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin|client')): ?>
            <?= Html::a('Назад', ['job-openings/index'], ['class' => 'btn btn-primary btn-block']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>