<?php
define('MAX_TODO_SIZE', 1000);

/********    БАЗА ДАННЫХ    **************/
define('SERVERNAME', "localhost");
define('USERNAME', "root");

if(PHP_MAJOR_VERSION >= 7){
    define('PASSWORD', "root");
}else{
    define('PASSWORD', "");
}

define('DATABASE', "REST_CRUD_test"); // Имя БД
define('TABLE_NAME', "REST_CRUD_test_table"); // Имя таблицы БД




/* Временно задаем типы и размеры полей БД. Потом можно бы справить, использовав метод fetch_field_direct() +++
 Впрочем, fetch_field_direct(1)->max_length - Максимальная ширина поля результирующего набора. Но, начиная с PHP 8.1, это значение всегда равно 0. Поэтому для РНР 8 это бесполезно (а в РНР 5.3 не работает). Т.е. лучше бы как-то доработать...
 То же касается метода fetch_field. Поэтому, с учетом глупых перемен в РНР, надежнее будет задавать эти данные жестко, НЕ определять их из запросов к БД.
 */
/****    ТИПЫ И МАКСИМАЛЬНЫЕ РАЗМЕРЫ ПОЛЕЙ БАЗЫ ДАННЫХ MySQL:   ****/
function field_MAX_TYPE_SIZE($field){

    $fields_TYPES = array('ID'          =>  array('int', 6),
                          'title'       =>  array('char', 50),
                          'description' =>  array('char', 200),
                          'status'      =>  array('int', 1)
                         );

    if($field === ''){ // Если требуется вернуть типы и макс. размеры для ВСЕХ полей, наверное, имеющихся в БД
        return $fields_TYPES;
    }

    if(!array_key_exists($field, $fields_TYPES)){
        http_response_code(500);
        throw new ErrorException('Неверный индекс массива при валидации данных запроса: '. $field);
    }


return array($field => $fields_TYPES[$field]);
}
