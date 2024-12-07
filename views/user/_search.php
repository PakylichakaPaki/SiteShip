<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['index'],
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Фамилия'])->label(false) ?>
    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя'])->label(false) ?>
    <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Телефон'])->label(false) ?>
    
    <?= Html::submitButton('Поиск', ['class' => 'btn btn-outline-dark']) ?>
    
    <?php ActiveForm::end(); ?>

</div>