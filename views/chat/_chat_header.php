<?php
use yii\helpers\Html;
?>
<div class="chat-user-info">
    <div class="user-avatar">
        <?= mb_strtoupper(mb_substr($user->name, 0, 1, 'UTF-8'), 'UTF-8') ?>
    </div>
    <div class="user-info">
        <div class="user-name"><?= Html::encode($user->surname . ' ' . $user->name) ?></div>
        <div class="user-status">
            <span class="status-dot"></span>
            <span class="status-text">Online</span>
        </div>
    </div>
</div>
