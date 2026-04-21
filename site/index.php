<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/config.php';

$dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset=utf8";

$db = new Database(
    $dsn,
    $config['db']['username'],
    $config['db']['password']
);

$page = new Page(__DIR__ . '/templates/index.tpl');

// Безопасно получаем id
$pageId = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Получаем данные
$data = $db->Read("page", $pageId);

// Если страницы нет
if (!$data) {
    $data = [
        'title' => 'Ошибка',
        'content' => 'Страница не найдена'
    ];
}

$page->Render($data);