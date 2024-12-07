$(document).ready(function() {
    let currentChatId = null;
    let lastMessageId = 0;
    let updateInterval;
    let editingMessageId = null;
    let currentUserId = $('meta[name="current-user-id"]').attr('content');

    // Загрузка чата при клике на пользователя
    $('.user-item').click(function() {
        const userId = $(this).data('user-id');
        loadChat(userId);
    });

    // Обработка клика по кнопке редактирования
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const messageContainer = $(this).closest('.message');
        const messageId = messageContainer.data('message-id');
        const messageText = messageContainer.find('.message-text').text();
        
        // Переключаем режим редактирования
        if (editingMessageId === messageId) {
            cancelEditing();
        } else {
            startEditing(messageId, messageText);
        }
    });

    // Обработка клика по кнопке удаления
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const messageId = $(this).closest('.message').data('message-id');
        
        if (confirm('Вы уверены, что хотите удалить это сообщение?')) {
            deleteMessage(messageId);
        }
    });

    // Функция начала редактирования
    function startEditing(messageId, messageText) {
        // Если уже редактируем другое сообщение, отменяем его редактирование
        if (editingMessageId && editingMessageId !== messageId) {
            cancelEditing();
        }

        const messageContainer = $(`.message[data-message-id="${messageId}"]`);
        const messageTextElement = messageContainer.find('.message-text');
        
        // Создаем поле ввода
        const input = $('<textarea>')
            .addClass('edit-message-input')
            .val(messageText);
        
        // Создаем кнопки действий
        const actions = $('<div>')
            .addClass('edit-actions')
            .append(
                $('<button>')
                    .addClass('btn-save')
                    .text('Сохранить')
                    .click(() => saveEdit(messageId, input.val())),
                $('<button>')
                    .addClass('btn-cancel')
                    .text('Отмена')
                    .click(cancelEditing)
            );

        // Заменяем текст на поле ввода
        messageTextElement.html(input);
        messageTextElement.append(actions);
        
        // Фокусируемся на поле ввода
        input.focus();
        
        // Запоминаем ID редактируемого сообщения
        editingMessageId = messageId;
    }

    // Функция отмены редактирования
    function cancelEditing() {
        if (!editingMessageId) return;

        // Получаем оригинальный текст сообщения
        $.get(`/chat/get-message?id=${editingMessageId}`, function(response) {
            if (response.success) {
                const messageContainer = $(`.message[data-message-id="${editingMessageId}"]`);
                messageContainer.find('.message-text').html(response.message.text);
                editingMessageId = null;
            }
        });
    }

    // Функция сохранения изменений
    function saveEdit(messageId, newText) {
        if (!newText.trim()) {
            showError('Сообщение не может быть пустым');
            return;
        }

        $.ajax({
            url: '/chat/edit-message',
            type: 'POST',
            data: {
                id: messageId,
                message: newText,
                [yii.getCsrfParam()]: yii.getCsrfToken()
            },
            success: function(response) {
                if (response.success) {
                    const messageContainer = $(`.message[data-message-id="${messageId}"]`);
                    messageContainer.find('.message-text').html(response.message.text);
                    editingMessageId = null;
                } else {
                    showError('Не удалось сохранить изменения');
                }
            },
            error: function() {
                showError('Произошла ошибка при сохранении');
            }
        });
    }

    // Функция удаления сообщения
    function deleteMessage(messageId) {
        $.ajax({
            url: '/chat/delete-message',
            type: 'POST',
            data: {
                id: messageId,
                [yii.getCsrfParam()]: yii.getCsrfToken()
            },
            success: function(response) {
                if (response.success) {
                    $(`.message[data-message-id="${messageId}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    showError('Не удалось удалить сообщение');
                }
            },
            error: function() {
                showError('Произошла ошибка при удалении');
            }
        });
    }

    // Отправка сообщения
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        
        // Проверяем, выбран ли чат
        if (!currentChatId) {
            showError('Пожалуйста, выберите собеседника');
            return;
        }

        // Получаем значение из поля ввода
        let messageInput = document.getElementById('message-input');
        let messageText = messageInput.value.trim();
        
        // Проверяем, не пустое ли сообщение
        if (!messageText) {
            return;
        }

        // Отправляем сообщение
        $.ajax({
            url: '/chat/send-message',
            type: 'POST',
            dataType: 'json',
            data: {
                recipientId: currentChatId,
                message: messageText,
                [yii.getCsrfParam()]: yii.getCsrfToken()
            },
            beforeSend: function() {
                // Блокируем кнопку отправки
                $('#message-form button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // Очищаем поле ввода
                    messageInput.value = '';
                    
                    // Добавляем сообщение в чат
                    $('#chat-messages').append(response.message.html);
                    
                    // Обновляем lastMessageId
                    lastMessageId = Math.max(lastMessageId, response.message.id);
                    
                    // Прокручиваем чат вниз
                    scrollToBottom();
                } else {
                    showError('Не удалось отправить сообщение');
                    console.error('Ошибка при отправке сообщения:', response.error);
                }
            },
            error: function(xhr, status, error) {
                showError('Произошла ошибка при отправке');
                console.error('Ошибка при отправке сообщения:', error);
            },
            complete: function() {
                // Разблокируем кнопку отправки
                $('#message-form button[type="submit"]').prop('disabled', false);
                // Возвращаем фокус на поле ввода
                messageInput.focus();
            }
        });
    });

    // Функция загрузки чата
    function loadChat(userId) {
        if (currentChatId === userId) return;
        
        // Сбрасываем состояние
        currentChatId = userId;
        lastMessageId = 0;
        
        // Очищаем предыдущий интервал обновления
        if (updateInterval) {
            clearInterval(updateInterval);
        }

        // Очищаем поле ввода при смене чата
        document.getElementById('message-input').value = '';

        $.ajax({
            url: '/chat/load-chat-ajax',
            type: 'GET',
            dataType: 'json',
            data: { targetUserId: userId },
            success: function(response) {
                if (response.success) {
                    $('#chat-header').html(response.header);
                    $('#chat-messages').html(response.messages);
                    $('.chat-main').show();
                    
                    // Разблокируем и фокусируемся на поле ввода
                    let messageInput = document.getElementById('message-input');
                    messageInput.disabled = false;
                    messageInput.focus();
                    
                    // Находим ID последнего сообщения
                    const lastMessage = $('#chat-messages .message').last();
                    if (lastMessage.length > 0) {
                        lastMessageId = parseInt(lastMessage.data('message-id')) || 0;
                    }
                    
                    // Устанавливаем новый интервал обновления
                    updateInterval = setInterval(function() {
                        checkNewMessages(userId);
                    }, 5000);

                    scrollToBottom();
                } else {
                    showError('Не удалось загрузить чат');
                    console.error('Ошибка при загрузке чата:', response.error);
                }
            },
            error: function(xhr, status, error) {
                showError('Произошла ошибка при загрузке чата');
                console.error('Ошибка при загрузке чата:', error);
            }
        });
    }

    // Функция проверки новых сообщений
    function checkNewMessages(userId) {
        if (!currentChatId) return;
        
        $.ajax({
            url: '/chat/get-messages',
            type: 'GET',
            dataType: 'json',
            data: {
                userId: userId,
                lastMessageId: lastMessageId
            },
            success: function(response) {
                if (response.messages && response.messages.length > 0) {
                    response.messages.forEach(function(message) {
                        $('#chat-messages').append(message.html);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    scrollToBottom();
                }
            }
        });
    }

    // Функция прокрутки чата вниз
    function scrollToBottom() {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    // Функция показа ошибки
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'chat-error';
        errorDiv.textContent = message;
        
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.appendChild(errorDiv);
            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        }
    }

    // Обновление счетчика непрочитанных сообщений
    function updateUnreadCount() {
        $.ajax({
            url: '/chat/get-unread-count',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                Object.keys(response).forEach(function(userId) {
                    const count = response[userId];
                    const badge = $(`.user-item[data-user-id="${userId}"] .unread-badge`);
                    
                    if (count > 0) {
                        badge.text(count).show();
                    } else {
                        badge.hide();
                    }
                });
            }
        });
    }

    // Поиск пользователей
    let searchTimeout;
    const searchInput = $('#user-search');
    const searchResults = $('#search-results');

    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (!query) {
            searchResults.removeClass('active').empty();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            $.get('/chat/search-users', { query: query }, function(response) {
                if (response.success && response.results.length > 0) {
                    searchResults.empty().addClass('active');
                    
                    response.results.forEach(user => {
                        const resultItem = $(`
                            <div class="search-result-item" data-user-id="${user.id}">
                                <div class="user-info">
                                    <div class="user-name">${user.text}</div>
                                    <div class="user-details">
                                        <span class="user-login">@${user.login}</span>
                                        <span class="user-phone">${user.phone}</span>
                                    </div>
                                </div>
                            </div>
                        `);
                        
                        resultItem.click(function() {
                            const userId = $(this).data('user-id');
                            loadChat(userId);
                            searchInput.val('');
                            searchResults.removeClass('active').empty();
                        });
                        
                        searchResults.append(resultItem);
                    });
                } else {
                    searchResults.empty().addClass('active').append(
                        '<div class="search-result-item">Пользователи не найдены</div>'
                    );
                }
            });
        }, 300);
    });

    // Скрываем результаты при клике вне поля поиска
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-input-wrapper').length) {
            searchResults.removeClass('active').empty();
        }
    });

    // Поиск по диалогам
    $('#user-search').on('input', function() {
        const searchText = this.value.toLowerCase();
        $('.user-item').each(function() {
            const userName = $(this).find('.user-name').text().toLowerCase();
            $(this).toggle(userName.includes(searchText));
        });
    });

    // Запускаем обновление счетчика непрочитанных сообщений
    setInterval(updateUnreadCount, 10000);
    updateUnreadCount();

    // Вспомогательная функция для экранирования HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
