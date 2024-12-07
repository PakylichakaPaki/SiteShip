<?php
use yii\helpers\Html;

$this->title = 'Доступ запрещен';
?>

<div class="site-error-access">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg mt-5">
                    <div class="card-body text-center">
                        <h1 class="card-title text-danger mb-4">
                            <i class="fas fa-exclamation-triangle"></i>
                        </h1>
                        <h2 class="mb-4">Доступ запрещен</h2>
                        <p class="lead mb-4">Для доступа к этой странице необходимо авторизоваться</p>
                        <div class="d-flex justify-content-center gap-3">
                            <?= Html::a('На главную', ['/site/index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Войти', ['/site/login'], ['class' => 'btn btn-success']) ?>
                            <?= Html::a('Регистрация', ['/user/create'], ['class' => 'btn btn-info text-white']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.site-error-access {
    min-height: 100vh;
    background-color: #f8f9fa;
    padding: 20px 0;
}
.site-error-access .card {
    border-radius: 15px;
    border: none;
}
.site-error-access .fa-exclamation-triangle {
    font-size: 4rem;
}
</style>
