<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Role $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Название') ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->label('Код') ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
            <?= Html::a('Назад', ['role/index'], ['class' => 'btn btn-primary btn-block']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>