<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\JobOpeningsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="job-openings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title_of_the_position') ?>

    <?= $form->field($model, 'salary') ?>

    <?= $form->field($model, 'term_of_employment') ?>

    <?= $form->field($model, 'company_name') ?>

    <?php // echo $form->field($model, 'link_to_the_questionnaire') ?>

    <?php // echo $form->field($model, 'contact_information') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
