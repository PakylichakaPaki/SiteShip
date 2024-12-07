<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */

$this->title = 'Обновление кандидата: ' . $model->name;
?>
<div class="candidate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
