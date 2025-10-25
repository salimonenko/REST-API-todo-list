<?php

define('access', 'permit'); // Константа для возможности доступа к модулям

require_once '../config/determine_absolute_PATH.php'; // Определяем путь до основного каталога
// Рекуррентно определяем путь до начального каталога "REST-API-todo-list" (не более 10 итераций)
$path_ABSOLUTE = PATH(__DIR__);


require_once $path_ABSOLUTE . '/app/Exceptions/errors_exceptions.php';
if(!function_exists('http_response_code')) {require_once $path_ABSOLUTE . '/app/Http/http_response_code.php';}
require_once $path_ABSOLUTE . '/config/parameters.php';
require_once $path_ABSOLUTE . '/app/Http/Controllers/methods_manager.php';


//
