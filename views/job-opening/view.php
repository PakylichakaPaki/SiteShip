<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JobOpening */

$this->title = $model->position;
$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-opening-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->needsVerification() && !$model->isArchived() && Yii::$app->user->id === $model->user_id): ?>
        <div class="alert alert-warning">
            <p>Требуется подтверждение актуальности вакансии. Если вакансия не будет подтверждена в течение 7 дней, она будет перемещена в архив.</p>
            <?= Html::a('Подтвердить актуальность', ['verify', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Вы уверены, что хотите подтвердить актуальность вакансии?',
                ],
            ]) ?>
        </div>
    <?php endif; ?>

    <?php if ($model->isArchived()): ?>
        <div class="alert alert-info">
            Вакансия находится в архиве.
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту вакансию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'position',
            'company_name',
            'description:ntext',
            'requirements:ntext',
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:d.m.Y H:i'],
            ],
            [
                'attribute' => 'last_verified_at',
                'format' => ['datetime', 'php:d.m.Y H:i'],
            ],
            [
                'attribute' => 'status.name',
                'label' => 'Статус',
            ],
            [
                'attribute' => 'user.username',
                'label' => 'Автор',
            ],
        ],
    ]) ?>

</div>
