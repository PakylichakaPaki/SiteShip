<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $users */
/** @var array $messages */
/** @var int|null $targetUserId */

$this->title = 'Чат';
$this->registerMetaTag(['name' => 'current-user-id', 'content' => Yii::$app->user->id]);
$this->registerCssFile('@web/css/chat.css');
$this->registerJsFile('@web/js/chat.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs("var currentUserId = " . Yii::$app->user->id . ";", \yii\web\View::POS_HEAD);
?>

<div class="chat-container">
    <div class="chat-sidebar">
        <div class="search-container">
            <div class="search-input-wrapper">
                <input type="text" id="user-search" class="form-control" placeholder="Поиск пользователей...">
                <div id="search-results" class="search-results"></div>
            </div>
        </div>
        <div class="users-list">
            <?php foreach ($users as $user): ?>
                <div class="user-item" data-user-id="<?= $user->id ?>">
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
                    <span class="unread-badge" style="display: none;">0</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="chat-main" style="display: none;">
        <!-- Заголовок чата -->
        <div id="chat-header" class="chat-header"></div>

        <!-- Сообщения -->
        <div id="chat-messages" class="chat-messages"></div>

        <!-- Форма отправки сообщения -->
        <div class="chat-input">
            <form id="message-form" class="message-form">
                <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                <div class="input-group">
                    <input type="text" 
                           id="message-input" 
                           class="form-control" 
                           placeholder="Введите сообщение..." 
                           required 
                           autocomplete="off" 
                           spellcheck="false"
                           data-lpignore="true"
                           data-form-type="other">
                    <button type="submit" class="chat-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
