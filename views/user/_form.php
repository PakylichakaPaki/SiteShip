<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true])->label('Фамилия') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Имя') ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true])->label('Отчество') ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label('Номер телефона') ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true])->label('Логин') ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label('Пароль') ?>

    <?= $form->field($model, 'role_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Role::find()->where(['code' => 'client'])->orWhere(['code' => 'executor'])->all(), 'id', function($model) { return $model->code === 'client' ? 'Найти сотрудника' : 'Найти работу'; }), ['prompt' => '...'])->label('Цель') ?>

    <div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>