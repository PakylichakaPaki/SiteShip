# Руководство по установке

## Локальная разработка

### Требования
- PHP 7.4 или выше
- Composer
- MySQL 5.7 или выше
- Git

### Быстрый старт

1. Клонируйте репозиторий:
```bash
git clone https://github.com/PakylichakaPaki/SiteShip.git
cd SiteShip
```

2. Установите зависимости:
```bash
composer install
```

3. Создайте базу данных и настройте подключение:
- Создайте новую БД MySQL
- Скопируйте `config/db.example.php` в `config/db.php`
- Отредактируйте параметры подключения

4. Примените миграции:
```bash
php yii migrate
```

5. Запустите встроенный сервер:
```bash
php yii serve
```

Приложение будет доступно по адресу: http://localhost:8080

### Альтернативные способы установки

#### OpenServer
1. Установите [OpenServer](https://ospanel.io/)
2. Поместите файлы в `domains/your-project`
3. Создайте виртуальный хост через панель управления
4. Следуйте шагам 2-4 из раздела "Быстрый старт"

#### Docker
```bash
# Запуск контейнеров
docker-compose up -d

# Установка зависимостей
docker-compose exec php composer install

# Миграции
docker-compose exec php yii migrate
```

## Разработка

### Структура проекта
```
config/         Конфигурационные файлы
controllers/    Контроллеры
models/         Модели
views/          Представления
web/           Публичная директория
```

### Команды для разработки
```bash
# Создание миграции
php yii migrate/create <name>

# Создание CRUD
php yii gii/crud

# Запуск тестов
php vendor/bin/codecept run
```

## Развертывание на хостинге

### Shared Hosting (Обычный хостинг)

1. Подготовка файлов:
```bash
# Установите зависимости для продакшена
composer install --no-dev

# Очистите кэш
php yii cache/flush-all
```

2. Загрузка на хостинг:
   - Загрузите все файлы через FTP/SFTP
   - Корневая директория сайта должна указывать на папку `web/`
   - Установите права 755 для директорий и 644 для файлов
   ```bash
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   ```

3. Настройка окружения:
   - Создайте файл `.htaccess` в корне сайта:
   ```apache
   RewriteEngine on
   RewriteRule ^(.+)?$ web/$1
   ```
   
   - Настройте `web/.htaccess`:
   ```apache
   RewriteEngine on
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule . index.php
   ```

4. Настройка базы данных:
   - Создайте БД через панель управления хостингом
   - Обновите `config/db.php` с новыми параметрами подключения
   - Примените миграции:
   ```bash
   php yii migrate
   ```

### VPS/Dedicated Server (Выделенный сервер)

1. Подготовка сервера:
```bash
# Установка необходимых пакетов
apt-get update
apt-get install nginx php-fpm mysql-server php-mysql

# Настройка PHP-FPM
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/7.4/fpm/php.ini
systemctl restart php7.4-fpm
```

2. Настройка Nginx:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/your-project/web;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
}
```

3. Развертывание:
```bash
# Клонирование репозитория
cd /var/www
git clone https://github.com/your-username/your-project.git

# Установка зависимостей
composer install --no-dev

# Настройка прав доступа
chown -R www-data:www-data /var/www/your-project
chmod -R 755 /var/www/your-project/runtime
chmod -R 755 /var/www/your-project/web/assets
```

### SSL-сертификат (HTTPS)

1. Установка Certbot:
```bash
apt-get install certbot python3-certbot-nginx
```

2. Получение сертификата:
```bash
certbot --nginx -d your-domain.com
```

### Оптимизация производительности

1. Включите кэширование:
```php
// config/web.php
'components' => [
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
]
```

2. Включите сжатие Gzip в Nginx:
```nginx
gzip on;
gzip_types text/plain text/css application/json application/javascript text/xml application/xml text/javascript;
```

3. Настройте PHP-FPM:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

### Мониторинг

1. Настройте логирование:
```php
// config/web.php
'components' => [
    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
]
```

2. Установите инструменты мониторинга:
```bash
# Установка Monit
apt-get install monit

# Установка NewRelic (опционально)
curl -L https://download.newrelic.com/548C16BF.gpg | apt-key add -
echo "deb http://apt.newrelic.com/debian/ newrelic non-free" > /etc/apt/sources.list.d/newrelic.list
apt-get update
apt-get install newrelic-php5
```