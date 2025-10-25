<?php

// Рекуррентно определяем путь до начального каталога "REST-API-todo-list" (не более 10 итераций)
$path_ABSOLUTE = PATH(__DIR__);

require_once $path_ABSOLUTE . '/routes/check_access.php'; // Запрет непосредственного доступа

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// соединение с базой данных
require_once $path_ABSOLUTE. '/database/database.php';
// Подключаем класс для обработки запросов и передачи их в БД
require_once $path_ABSOLUTE. '/app/Models/CRUD_methods.php';

require_once $path_ABSOLUTE . '/app/Http/Controllers/check_validation/validate_request_DATA.php';
require_once $path_ABSOLUTE . '/app/Http/Controllers/check_validation/check_request_DATA.php';
require_once $path_ABSOLUTE . '/database/Controllers/check_MySQL_DATA_types.php';
require_once $path_ABSOLUTE . '/app/Models/Controllers/checking_DATA_CRUD_methods.php';



$database = new Database();
$db = $database->getConnection();

if(!$db){
    die('Не получилось установить соединение с базой данных.');
}

$num_CRUD = new CRUD_methods($db);



$mayBe_METHODS = array('POST', 'GET', 'PUT', 'GET', 'DELETE', 'CHECK_PROBLEMS'); // Возможны только такие запросы (белый список) - в целях надежности

// Принимаем присланные данные
if(!isset($_REQUEST['METHOD']) || !in_array($_REQUEST['METHOD'], $mayBe_METHODS) || strlen($_REQUEST['METHOD']) > MAX_TODO_SIZE){
    $METHOD = '';
    http_response_code(500);
    Exception_response('Method forbidden', 1); // 1- Прекратить работу
}else{
    $METHOD = $_REQUEST['METHOD'];
}

if(!isset($_REQUEST['todo']) || strlen($_REQUEST['todo'] > MAX_TODO_SIZE)){
    $todo = '';
    http_response_code(500);
    Exception_response('Request incorrect', 1); // 1- Прекратить работу
}else{
    $todo = $_REQUEST['todo'];
}


if($METHOD) {
    switch ($METHOD) {
        case 'POST':
            include_once $path_ABSOLUTE. '/app/Models/POST.php';
            break;
        case 'GET':
            include_once $path_ABSOLUTE. '/app/Models/GET.php';
            break;
        case 'PUT':
            include_once $path_ABSOLUTE. '/app/Models/PUT.php';
            break;
        case 'DELETE':
            include_once $path_ABSOLUTE. '/app/Models/DELETE.php';
            break;
// Для просмотра отметок + + + (без пробелов)  во всех файлах проекта и вывода их клиенту. Их я оставлял, если где требуется доработка
        case 'CHECK_PROBLEMS':
            include_once $path_ABSOLUTE. '/app/Models/Testing/CHECK_PROBLEMS.php';
            break;

        default:
            http_response_code(400);
            die("Ошибка: выбран неверный запрос на сервер");
    }
}


