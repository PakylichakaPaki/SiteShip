<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=siteshiper',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'tablePrefix' => '',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
    'on afterOpen' => function($event) {
        $event->sender->createCommand("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci")->execute();
    },
];
