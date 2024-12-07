<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Status $model */

$this->title = 'Обновление статуса: ' . $model->name;
?>
<div class="status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
