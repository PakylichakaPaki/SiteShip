<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */

$this->title = 'Create Candidate';
?>
<div class="candidate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
