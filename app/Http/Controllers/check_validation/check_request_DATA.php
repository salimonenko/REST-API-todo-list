<?php
// Проверка данных запроса (перед валидацией)

function check_request_DATA($mayBe_params){
/* $mayBe_params - массив вида:
  Array
(
    [0] => title
    [1] => description
    [2] => status
)
 */

    $mayBe_params_val = array();
/* 1. Присутствует ли в запросе параметр, имеющийся в массиве
 * 2. Не превышает ли его длина заданную
 */
    for($i = 0; $i < sizeof($mayBe_params); $i++){
        if(!isset($_REQUEST[$mayBe_params[$i]]) || strlen($_REQUEST[$mayBe_params[$i]]) > MAX_TODO_SIZE){
            http_response_code(500);
            Exception_response('Bad value "'. $mayBe_params[$i]. '"', 1); // Прекратить работу
        }else{
            $mayBe_params_val[$mayBe_params[$i]] = $_REQUEST[$mayBe_params[$i]];
        }
    }

    if(sizeof($mayBe_params) !== sizeof($mayBe_params_val)){ // Если в новый массив попало не 3 параметра, а больше или меньше
        http_response_code(500);
        Exception_response('Error: Array sizes are not equal', 1); // Прекратить работу
    }

/* Массив $mayBe_params_val вида:
  Array
(
    [title] => qwe
    [description] => ee5666йцушщ
    [status] => 3
)
 */
return array($mayBe_params, $mayBe_params_val);
 }


