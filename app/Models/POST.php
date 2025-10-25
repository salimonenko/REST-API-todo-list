<?php
// Добавляет строку данных в БД

// Рекуррентно определяем путь до начального каталога "REST-API-todo-list" (не более 10 итераций)
$path_ABSOLUTE = PATH(__DIR__);

require_once $path_ABSOLUTE . '/routes/check_access.php'; // Запрет непосредственного доступа к этому модулю

$mayBe_params = array('title', 'description', 'status'); // Белый список разрешенных имен полей БД, разрешенный для данного метода

// Проводим проверку и валидацию данных из запроса перед тем, как делать SQL-запрос
$checkig_Arr = checking_DATA_CRUD_methods($mayBe_params, $db);
$mayBe_params_val = $checkig_Arr[0];
$params_types_Arr = $checkig_Arr[1];

// Если все хорошо, создаем запись
$public_request = $num_CRUD->POST($mayBe_params_val);

    // Анализируем результат выполнения публичного запроса и сообщаем пользователю
if(!$public_request){
    http_response_code(202);
        echo "Данные успешно добавлены";
    } else{
    http_response_code(406);
        echo "Ошибка: " . $public_request;
    }


