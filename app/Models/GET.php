<?php

// Рекуррентно определяем путь до начального каталога "REST-API-todo-list" (не более 10 итераций)
$path_ABSOLUTE = PATH(__DIR__);

require_once $path_ABSOLUTE . '/routes/check_access.php'; // Запрет непосредственного доступа к этому модулю


$mayBe_params = array('ID'); // Белый список разрешенных имен полей БД, только ID

// Проводим проверку и валидацию данных из запроса перед тем, как делать SQL-запрос
$checkig_Arr = checking_DATA_CRUD_methods($mayBe_params, $db);
$mayBe_params_val = $checkig_Arr[0];

// Создаем запись
$public_request = $num_CRUD->GET($mayBe_params_val);

// Анализируем результат выполнения публичного запроса и сообщаем пользователю
if($public_request){
    http_response_code(200);
    echo "Данные успешно извлечены";
    echo '<pre>';
    print_r($public_request);
    echo '</pre>';

} else{
    http_response_code(303);
    echo "Нет данных для ID = " . $mayBe_params_val['ID'];
}




