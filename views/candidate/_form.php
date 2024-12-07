<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Candidate;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="candidate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true])->label('Фамилия') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Имя') ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true])->label('Отчество') ?>

    <?= $form->field($model, 'desired_position')->textInput(['maxlength' => true])->label('Желаемая должность') ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label('Телефон') ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('Email') ?>

    <?= $form->field($model, 'medical_card')->textInput(['maxlength' => true, 'placeholder' => 'Вставьте ссылку, перед этим загрузив её в любой социальный сервис'])->label('Медицинская карта') ?>

    <?= $form->field($model, 'resume_link')->textInput(['maxlength' => true, 'placeholder' => 'Вставьте ссылку на анкету (Google Drive, Dropbox и т.д.)'])->label('Ссылка на анкету') ?>

    <?= $form->field($model, 'work_experience')->textInput(['maxlength' => true])->label('Опыт работы') ?>

    <?php if (Yii::$app->user->identity && (Yii::$app->user->identity->roleMiddleware('admin') || Yii::$app->user->identity->roleMiddleware('controller'))): ?>
        <?= $form->field($model, 'user_id')->dropDownList(Candidate::getUsers(), [
            'prompt' => 'Выберите заявителя',
            'options' => [
                Yii::$app->user->id => ['Selected' => true],
            ]
        ])->label('Заявитель') ?>
    <?php endif; ?>

    <?= $form->field($model, 'status_id')->dropDownList(Candidate::getStatuses(), ['prompt' => 'Выберите статус'])->label('Статус') ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
            <?= Html::a('Назад', ['candidate/index'], ['class' => 'btn btn-primary btn-block']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>