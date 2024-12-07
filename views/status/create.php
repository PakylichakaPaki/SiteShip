<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Status $model */

$this->title = 'Создание статуса';
?>
<div class="status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
