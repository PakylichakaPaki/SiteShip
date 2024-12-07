<?php
use yii\helpers\Html;

/** @var app\models\User $model */
?>

<!-- Кандидаты -->
<div class="card mb-4">
    <div class="card-header bg-transparent">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Мои кандидаты</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($model->candidates)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ФИО</th>
                            <th>Желаемая должность</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model->candidates as $candidate): ?>
                            <tr>
                                <td><?= Html::encode($candidate->surname . ' ' . $candidate->name) ?></td>
                                <td><?= Html::encode($candidate->desired_position) ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= $candidate->status ? Html::encode($candidate->status->name) : 'Не указан' ?>
                                    </span>
                                </td>
                                <td>
                                    <?= Html::a('<i class="fas fa-eye"></i>', ['/candidate/view', 'id' => $candidate->id], [
                                        'class' => 'btn btn-sm btn-info',
                                        'title' => 'Просмотр'
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">У вас пока нет добавленных кандидатов.</p>
        <?php endif; ?>
    </div>
</div>
