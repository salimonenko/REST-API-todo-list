<?php

class MyException extends ErrorException {

    public function __construct($message, $code = 0, ErrorException $previous = null) {

        parent::__construct($message, $code, $previous);
    }
}

// Целесообразно сделать разные исключения для разных случаев  +++

function myExceptionHandler ($e) {
//    error_log($e);
//    http_response_code(500); // Целесообразно, если только не было вывода в браузер, иначе будет ошибка
    if (filter_var(ini_get('display_errors'),FILTER_VALIDATE_BOOLEAN)) { // Для разработчиков, более подробная информация
        if(is_array($e)){
            $e_str = implode('\n\\', $e);
        }else{
            $e_str = $e;
        }

        $e_str = preg_replace('/(\r\n)|(\n)/', '<br/>', $e_str);
        $mess = 'Function '. __FUNCTION__. ' says: '. $e_str;
    } else { // Для конечных пользователей
        $mess = "<h1>500 Internal Server Error</h1>An internal server error has been occurred.<br>Please try again later.";
    }

//    print_r($e); // Полная информация об исключении
//    echo get_class($e); // ErrorException
    Exception_response($mess, 0);

    exit;
}

// задает пользовательскую функцию для обработки всех необработанных исключений
set_exception_handler('myExceptionHandler'); // Обработчик для перехвата исключений (в т.ч. ошибок, превращенных в исключения)

set_error_handler(function ($level, $message, $file = '', $line = 0){
// Превращаем ошибки в исключения. А они обрабатываются при помощи set_exception_handler()
    throw new ErrorException($message, 0, $level, $file, $line);
});

register_shutdown_function(function (){
    $error = error_get_last(); // Если ошибки были перехвачены, то будет пустой массив
    if ($error !== null) { // Если была НЕПЕРЕХВАЧЕННАЯ ошибка (если не было, то пустой массив считается равным null и дальше не будет выполняться)
        $e = new ErrorException(
            'From register_shutdown_function: '.$error['message'], 0, $error['type'], $error['file'], $error['line']
        );
        myExceptionHandler($e);
    }
});


function Exception_response($mess, $flag_die){
    if($flag_die){
        die($mess);
    }else{
        echo $mess;
    }
}

