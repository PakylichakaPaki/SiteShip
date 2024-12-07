<?php
use yii\helpers\Html;

/** @var app\models\User $model */
?>

<!-- Вакансии -->
<div class="card mb-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Мои вакансии</h5>
        <?= Html::a('<i class="fas fa-plus"></i> Добавить вакансию', ['/job-openings/create'], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
    <div class="card-body">
        <?php if (!empty($model->jobOpenings)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Должность</th>
                            <th>Компания</th>
                            <th>Зарплата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model->jobOpenings as $jobOpening): ?>
                            <tr>
                                <td><?= Html::encode($jobOpening->title_of_the_position) ?></td>
                                <td><?= Html::encode($jobOpening->company_name) ?></td>
                                <td><?= Yii::$app->formatter->asCurrency($jobOpening->salary, 'RUB') ?></td>
                                <td>
                                    <?= Html::a('<i class="fas fa-eye"></i>', ['/job-openings/view', 'id' => $jobOpening->id], [
                                        'class' => 'btn btn-sm btn-info btn-action',
                                        'title' => 'Просмотр'
                                    ]) ?>
                                    <?= Html::a('<i class="fas fa-edit"></i>', ['/job-openings/update', 'id' => $jobOpening->id], [
                                        'class' => 'btn btn-sm btn-warning btn-action',
                                        'title' => 'Редактировать'
                                    ]) ?>
                                    <?= Html::a('<i class="fas fa-trash"></i>', ['/job-openings/delete', 'id' => $jobOpening->id], [
                                        'class' => 'btn btn-sm btn-danger btn-action',
                                        'title' => 'Удалить',
                                        'data' => [
                                            'confirm' => 'Вы уверены, что хотите удалить эту вакансию?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">У вас пока нет созданных вакансий.</p>
        <?php endif; ?>
    </div>
</div>
