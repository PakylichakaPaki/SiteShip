<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Role $model */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="role-view">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h4 class="m-0">Информация о роли: <?= Html::encode($model->name) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <?= Html::a('<i class="fas fa-edit"></i> Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-lg w-100']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-outline-danger btn-lg w-100',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить эту роль?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>

                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => [
                                'class' => 'table table-striped table-bordered detail-view',
                            ],
                            'attributes' => [
                                [
                                    'attribute' => 'id',
                                    'label' => 'ID роли',
                                ],
                                [
                                    'attribute' => 'name',
                                    'label' => 'Название роли',
                                ],
                                [
                                    'attribute' => 'code',
                                    'label' => 'Код роли',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .role-view .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .role-view .card-header {
        border-radius: 10px 10px 0 0;
    }
    
    .role-view .table {
        margin-bottom: 0;
    }
    
    .role-view .detail-view th {
        width: 30%;
        background-color: #f8f9fa;
    }
    
    .role-view .btn {
        border-radius: 5px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-view .btn i {
        margin-right: 8px;
    }
    
    @media (max-width: 767px) {
        .role-view .col-md-6:first-child {
            margin-bottom: 1rem;
        }
    }
</style>