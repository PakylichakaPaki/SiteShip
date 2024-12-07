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

    <?= $form->field($model, 'role_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Role::find()->where(['code' => 'client'])->orWhere(['code' => 'executor'])->orWhere(['code' => 'controller'])->all(), 'id', 'name'), ['prompt' => 'Выберите роль']) ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
            <?= Html::a('Назад', ['user/index'], ['class' => 'btn btn-primary btn-block']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>