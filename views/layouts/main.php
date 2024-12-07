<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap\Dropdown;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/icon.png')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => 'Кораблики',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav w-100'],
            'items' => [
                [
                    'label' => 'Пользователи',
                    'url' => ['/user/index'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('admin|controller')
                ],
                [
                    'label' => 'Роли',
                    'url' => ['/role/index'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('admin|controller')
                ],
                [
                    'label' => 'Статусы заявок',
                    'url' => ['/status/index'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('admin|controller')
                ],
                [
                    'label' => 'Кандидаты',
                    'url' => ['/candidate/index'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('admin|executor|controller|client')
                ],
                [
                    'label' => 'Вакансии',
                    'url' => ['/job-openings/index'],
                    'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->roleMiddleware('admin|client|executor|controller')
                ],
                [
                    'label' => 'О нас',
                    'url' => ['/site/about'],
                ],
                [
                    'label' => 'Контакты',
                    'url' => ['/site/contact'],
                ],
                [
                    'label' => 'Чат',
                    'url' => ['chat/'],
                    'visible' => !Yii::$app->user->isGuest
                ],
                Yii::$app->user->isGuest
                ? ['label' => 'Авторизироваться', 'url' => ['/site/login']]
                : [
                    'label' => '<i class="fas fa-user me-2"></i>Профиль',
                    'url' => '#',
                    'encode' => false,
                    'linkOptions' => [
                        'class' => 'nav-link dropdown-toggle',
                        'data-toggle' => 'dropdown',
                        'aria-haspopup' => 'true',
                        'aria-expanded' => 'false',
                    ],
                    'items' => [
                        [
                            'label' => 'Кабинет (' . Yii::$app->user->identity->surname . ' - ' . Yii::$app->user->identity->role->name . ')',
                            'url' => ['/profile'],
                        ],
                        [
                            'label' => 'Выход',
                            'url' => ['/site/logout'],
                            'linkOptions' => [
                                'data-method' => 'post',
                            ],
                        ],
                    ],
                    'options' => ['class' => 'ms-auto'],
                ]
            ]
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container-fluid px-2 mt-5">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <ul class="list-unstyled">
                        <p class="text-muted">&copy; Кораблики <?= date('Y') ?></p>
                        <li><a href="<?= Url::to(['/site/about']) ?>" class="text-muted">О нас</a></li>
                        <li><a href="<?= Url::to(['/site/contact']) ?>" class="text-muted">Контакты</a></li>
                    </ul>
                    
                </div>
            </div>
        </div>
    </footer>

    <div class="position-absolute bottom-0 p-4">
        <?php
        if (Yii::$app->session->hasFlash('changeStatus')) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-success',
                ],
                'body' => Yii::$app->session->getFlash('changeStatus'),
            ]);
        } ?>
    </div>


    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>