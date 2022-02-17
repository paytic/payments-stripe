<?php

require __DIR__ . '/constants.php';
require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(TEST_BASE_PATH . DIRECTORY_SEPARATOR . '.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(TEST_BASE_PATH);
    $dotenv->load();
}
