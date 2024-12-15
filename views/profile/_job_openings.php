<?php
use yii\helpers\Html;
use yii\helpers\Json;

/** @var app\models\User $model */

// Разделяем вакансии на активные и истекшие
$activeJobs = [];
$expiredJobs = [];
foreach ($model->jobOpenings as $jobOpening) {
    $expirationDate = $jobOpening->getExpirationDate();
    $now = time();
    $timeLeft = $expirationDate - $now;
    
    if ($timeLeft <= 0) {
        $expiredJobs[] = $jobOpening;
    } else {
        $activeJobs[] = $jobOpening;
    }
}
?>

<?php
$this->registerCss("
    .btn-action {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 4px !important;
        line-height: 1 !important;
    }
    
    .btn-action i {
        font-size: 14px !important;
    }
    
    .btn-group.gap-2 {
        display: inline-flex !important;
        gap: 8px !important;
    }
    
    .btn-group.gap-2 > .btn {
        float: none !important;
        position: static !important;
        border-radius: 4px !important;
    }
");
?>

<!-- Активные вакансии -->
<div class="card mb-4">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Мои вакансии</h5>
        <?= Html::a('<i class="fas fa-plus"></i> Добавить вакансию', ['/job-openings/create'], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
    <div class="card-body">
        <?php if (!empty($activeJobs)): ?>
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
                        <?php foreach ($activeJobs as $jobOpening): ?>
                            <?php 
                            $expirationDate = $jobOpening->getExpirationDate();
                            $now = time();
                            $timeLeft = $expirationDate - $now;
                            
                            $class = '';
                            if ($timeLeft < 3 * 24 * 3600) { // менее 3 дней
                                $class = 'text-warning';
                            }

                            // Данные для JavaScript
                            $jobData[] = [
                                'id' => $jobOpening->id,
                                'title' => $jobOpening->title_of_the_position,
                                'expirationDate' => $expirationDate * 1000, // конвертируем в миллисекунды для JS
                            ];
                            ?>
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
                                    <span class="ms-3 <?= $class ?>">
                                        <i class="far fa-clock"></i>
                                        <?php echo 'Истечет ' . Yii::$app->formatter->asRelativeTime($expirationDate); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">У вас нет активных вакансий.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Истекшие вакансии (скрыты по умолчанию) -->
<?php if (!empty($expiredJobs)): ?>
    <div class="card mb-4">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#expiredJobs" aria-expanded="false">
                <h5 class="mb-0">
                    <i class="fas fa-archive me-2"></i>
                    Истекшие вакансии
                    <small class="text-muted">(<?= count($expiredJobs) ?>)</small>
                    <i class="fas fa-chevron-down ms-2"></i>
                </h5>
            </button>
        </div>
        <div class="collapse" id="expiredJobs">
            <div class="card-body">
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
                            <?php foreach ($expiredJobs as $jobOpening): ?>
                                <?php $isExpired = true; ?>
                                <tr class="text-muted">
                                    <td><?= Html::encode($jobOpening->title_of_the_position) ?></td>
                                    <td><?= Html::encode($jobOpening->company_name) ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($jobOpening->salary, 'RUB') ?></td>
                                    <td>
                                        <div class="btn-group gap-2" role="group">
                                            <?= Html::a('<i class="fas fa-eye"></i>', ['/job-openings/view', 'id' => $jobOpening->id], [
                                                'class' => 'btn btn-sm btn-primary btn-action',
                                                'title' => 'Просмотр'
                                            ]) ?>
                                            <?= Html::a('<i class="fas fa-trash-alt"></i>', ['/job-openings/delete', 'id' => $jobOpening->id], [
                                                'class' => 'btn btn-sm btn-danger btn-action',
                                                'title' => 'Удалить',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить эту вакансию?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                            <?= Html::a('<i class="fas fa-redo"></i>', ['/job-openings/renew', 'id' => $jobOpening->id], [
                                                'class' => 'btn btn-sm btn-success btn-action',
                                                'title' => 'Обновить вакансию',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите обновить эту вакансию? Она снова станет активной.',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </div>
                                        <span class="ms-3 text-danger">
                                            <i class="far fa-clock"></i>
                                            Срок истек
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Скрытый div с данными о вакансиях -->
<div id="jobOpeningsData" data-jobs='<?= Json::encode($jobData ?? []) ?>' style="display: none;"></div>

<!-- Toast для уведомлений -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="jobExpirationToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<?php
$js = <<<'JS'
    // Функция для проверки истекших вакансий
    function checkExpiredJobs() {
        const jobsData = JSON.parse(document.getElementById('jobOpeningsData').dataset.jobs);
        const now = new Date().getTime();
        
        jobsData.forEach(function(job) {
            const timeLeft = job.expirationDate - now;
            
            // Показываем уведомление, если срок только что истек
            if (timeLeft <= 0 && timeLeft > -1000) { // Проверяем последнюю секунду
                const toast = new bootstrap.Toast(document.getElementById('jobExpirationToast'));
                document.getElementById('toastMessage').textContent = 
                    'Срок размещения вакансии "' + job.title + '" истек';
                toast.show();
                
                // Перезагружаем страницу через 2 секунды после показа уведомления
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        });
    }

    // Проверяем каждую секунду
    setInterval(checkExpiredJobs, 1000);
JS;
$this->registerJs($js);
?>
