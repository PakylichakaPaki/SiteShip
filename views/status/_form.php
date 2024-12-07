<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Status $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="status-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->roleMiddleware('admin')): ?>
            <?= Html::a('Назад', ['status/index'], ['class' => 'btn btn-primary btn-block']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
