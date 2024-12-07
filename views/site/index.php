<?php

/** @var yii\web\View $this */

$this->title = 'Кораблики - Ваш путь к успеху';
?>
<div class="site-index">
    <div class="jumbotron text-center py-5">
        <h1 class="display-4 mb-4">Кораблики</h1>
        <p class="lead mb-5">Платформа, где таланты встречаются с возможностями</p>
        
        <div class="d-grid gap-3 d-md-flex justify-content-center">
            <?php if (Yii::$app->user->isGuest): ?>
                <a class="btn btn-lg btn-primary" href="/site/login">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </a>
                <a class="btn btn-lg btn-success" href="user/create">
                    <i class="fas fa-user-plus"></i> Регистрация
                </a>
            <?php else: ?>
                <?php if (!Yii::$app->user->identity->roleMiddleware('executor|admin')): ?>
                    <a class="btn btn-lg btn-success" href="candidate">
                        <i class="fas fa-search"></i> Найти специалиста
                    </a>
                <?php endif; ?>
                
                <?php if (!Yii::$app->user->identity->roleMiddleware('client|admin')): ?>
                    <a class="btn btn-lg btn-primary" href="job-openings">
                        <i class="fas fa-briefcase"></i> Найти работу
                    </a>
                <?php endif; ?>
                
                <?php if (Yii::$app->user->identity->roleMiddleware('admin')): ?>
                    <a class="btn btn-lg btn-outline-success" href="user/index">
                        <i class="fas fa-users"></i> Управление пользователями
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>