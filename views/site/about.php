<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'О компании';
?>
<div class="site-about">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Наша миссия</h5>
                        <p class="card-text">
                            Мы стремимся обеспечить эффективное взаимодействие между морскими специалистами и судоходными компаниями, 
                            создавая надежную платформу для профессионального роста и развития морской отрасли.
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">Наши преимущества</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i> Прямой контакт с работодателями</li>
                                    <li><i class="fas fa-check text-success me-2"></i> Актуальные вакансии</li>
                                    <li><i class="fas fa-check text-success me-2"></i> Поддержка на всех этапах</li>
                                    <li><i class="fas fa-check text-success me-2"></i> Быстрое трудоустройство</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">Наши услуги</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-ship text-primary me-2"></i> Подбор экипажа</li>
                                    <li><i class="fas fa-users text-primary me-2"></i> Кадровый консалтинг</li>
                                    <li><i class="fas fa-file-contract text-primary me-2"></i> Оформление документов</li>
                                    <li><i class="fas fa-handshake text-primary me-2"></i> Сопровождение контрактов</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">О нас</h5>
                        <p class="card-text">
                            Наша компания специализируется на предоставлении услуг по трудоустройству морских специалистов. 
                            Мы работаем с ведущими судоходными компаниями и помогаем профессионалам найти достойную работу в море. 
                            Наш опыт и знание отрасли позволяют нам эффективно соединять квалифицированных специалистов с надежными работодателями.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
