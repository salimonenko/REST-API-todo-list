<?php

require_once __DIR__ . '/../routes/check_access.php' ;

require_once $path_ABSOLUTE . '/config/parameters.php';

$servername = SERVERNAME; // "localhost"
$database = DATABASE; // "REST_CRUD_test"
$username = USERNAME; // "root"
$password = PASSWORD; // ""

$database_table = TABLE_NAME; // "REST_CRUD_test_table";

// ********  Актуально при первом запуске, когда еще нет базы данных  **********************
// Создание соединения
$conn = @(new mysqli($servername, $username, $password));
// Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        throw new ErrorException("Ошибка подключения: " . $conn->connect_error);
    }

// Создание базы данных, если ее еще нет
$sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql) !== TRUE) {
        die("Ошибка создания базы данных: " . $conn->error);
    }
$conn->close();

// Создание нового соединения - для созданной базы данных
$conn_t = new mysqli($servername, $username, $password, $database);
// Создание таблицы в базе данных
$sql = "CREATE TABLE IF NOT EXISTS $database_table (ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title CHAR (50), description CHAR (200), status INT (1))";

$mes = '';
    if(!mysqli_query($conn_t, $sql)){
        $mes = "ERROR: Не удалось выполнить $sql. " . mysqli_error($conn_t);
    }
// Закрыть подключение
$conn_t->close();

    if($mes !== ''){
        http_response_code(500);
        echo $mes;
    }


