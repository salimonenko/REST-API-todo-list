<?php
// Обновляет строку в БД

// Рекуррентно определяем путь до начального каталога "REST-API-todo-list" (не более 10 итераций)
$path_ABSOLUTE = PATH(__DIR__);

require_once $path_ABSOLUTE . '/routes/check_access.php'; // Запрет непосредственного доступа к этому модулю


$mayBe_params = array('ID', 'title', 'description', 'status'); // Белый список разрешенных имен полей БД, в т.ч. ID

// Проводим проверку и валидацию данных из запроса перед тем, как делать SQL-запрос
$checkig_Arr = checking_DATA_CRUD_methods($mayBe_params, $db);
$mayBe_params_val = $checkig_Arr[0];
$params_types_Arr = $checkig_Arr[1];


// Если все хорошо, пытаемся (не факт, что получится) обновить запись
$public_request = $num_CRUD->PUT($mayBe_params_val);

// Анализируем результат выполнения публичного запроса и сообщаем пользователю
if(!$public_request){ // Анализ на ошибки здесь неполноценный! При запросе по несуществующему ID ошибка НЕ ВСЕГДА выдается! +++
    http_response_code(201);
    echo "Данные успешно обновлены";
} else{
    http_response_code(304);
    throw new ErrorException('Ошибка обновления строки в базе данных. Операция PUT НЕ выполнена. '. $public_request);
}


