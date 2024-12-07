<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\JobOpenings $model */

$this->title = 'Обновление вакансии: ' . $model->title_of_the_position;
?>
<div class="job-openings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
