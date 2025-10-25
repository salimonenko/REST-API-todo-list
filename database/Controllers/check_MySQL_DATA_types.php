<?php
// Проверка соответствия типов данных, исходя из имеющихся в БД MySQL. Потом нужно добавить проверки на дополнительные типы данных +++

function int_types_MySQL($type){
// Целочисленные типы данных, имеющиеся в MySQL
    $int_types_MySQL = array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'); // Возможные целые типы в базе данных

    return in_array($type, $int_types_MySQL);
}

