<?php

class CRUD_methods{
// Методы POST, GET, PUT, DELETE
    // для соединения с базой данных и имя таблицы
    protected $conn;
    protected $table_name = TABLE_NAME; // "REST_CRUD_test_table";

    // свойства объекта (только 4 поля)
    public $id;
    public $title;
    public $description;
    public $status;

    // конструктор с $db как соединение с базой данных
    public function __construct($db){
        $this->conn = $db;
    }

    // Создаем запись в базе данных
    public function POST($mayBe_params_val){
        $mayBe_params_val_keys = array_keys($mayBe_params_val);

        $arr = array();
        foreach ($mayBe_params_val as $item){
            $arr[] = $this->conn->quote($item);
        }

        if(sizeof($arr) !== sizeof($mayBe_params_val)){ // Если Вдруг потерялись значения массива
            http_response_code(500);
            Exception_response('Error: Array sizes are not equal', 1); // Прекратить работу
        }

        $mayBe_params_val = $arr;

        $field_s = "$this->table_name(". implode(", ", $mayBe_params_val_keys) . ") ";
        $value_s = " VALUES (". implode(", ", $mayBe_params_val). ")";

        // Делаем запрос для вставки Реквизитов задачи, полученных от клиента
//        $query = "INSERT INTO $this->table_name($field1, $field2, $field3) VALUES (3, 5, 7)";  // Шаблон
        $query = "INSERT INTO ". $field_s. $value_s;


        $returnObj = false;
        try {
            $returnObj = $this->conn->query($query);
//            print_r($returnObj->fetch());
            return false;
        } catch (PDOException $e) {
            return true;
        }

        /* (ЭТО ЕСЛИ ДЕЛАТЬ ЧЕРЕЗ ПОДГОТОВКУ ЗАПРОСА И Т.Д. - другой вариант)
        // Подготовляем запрос
        $stmt = $this->conn->prepare($query);
        // Преобразуем опасные символы в безопасные последовательности
        $this->name=htmlspecialchars(strip_tags($this->num));
        // bind values
        $stmt->bindParam(":num", $this->num);
        // execute query
        if($stmt->execute()){
            return true;
        }*/
    }


    // Выводим записи из базы данных
    public function GET($mayBe_params_val){
        $ID = $mayBe_params_val['ID'];

        if($ID !== '0'){
        // Делаем запрос для вывода числа по id записи
            $query = "SELECT * FROM `$this->table_name` WHERE `ID` = :ID";

        } else {
            $query = "SELECT * FROM `$this->table_name`";
        }

        // Подготовляем запрос
        $stmt = $this->conn->prepare($query);

        if($ID !== '0'){
        // execute query
            if($stmt->execute(array('ID' => $ID))){
                $value = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } else {
            // execute query
            if($stmt->execute()){
                $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        if(empty($value)){
            return 0;
        }

        return $value;
    }


    public function PUT($mayBe_params_val){
/*  Изменяем (обновляем) записи в базе данных
    База данных MySQL, по крайней мере в реализации РНР, - до сих пор достаточно сырая и ненадежная технология. Поэтому даже при заведомо ошибочном SQL-запросе (например, при вводе валидного, но несуществующего ID) обновления строки, естественно, не будет, но ошибок может быть НЕ ПОКАЗАНО(!). Поэтому полагаться на корректность выполнения операции PUT здесь НЕЛЬЗЯ. Проверку возвращаемогого результата сделать, похоже, НЕВОЗМОЖНО. Следует осуществлять дополнительную проверку, что данные: +++
  1. Существуют в обновляемой строке,
  2. Действительно обновились, причем - корректно.
*/
        $id = $mayBe_params_val['ID'];
        unset($mayBe_params_val['ID']);

        // Делаем запрос для ЗАМЕНЫ Реквизитов задачи, на те, что получены от клиента
        $query = "UPDATE $this->table_name SET ";
        foreach ($mayBe_params_val as $key=>$value){
            $query .= $key. " = ". $this->conn->quote($value);
            array_pop($mayBe_params_val);
                if(sizeof($mayBe_params_val)){ // Если был удален НЕ последний элемент массива, то добавляем запятую
                    $query .= ", ";
                }
        }
        $query = $query.  " WHERE id=". $id;
//      $query = "UPDATE Users SET name = :username, age = :userage WHERE id = :userid" // Шаблон

        $returnObj = false;
        try {
            $returnObj = $this->conn->query($query);
//            print_r($returnObj->fetch()); // Не работает
            return false;
        } catch (PDOException $e) {
            return true;
        }

    }

    // Удаляем строку из БД
    public function DELETE($mayBe_params_val){
        $id = $mayBe_params_val['ID'];

// Делаем запрос для УДАЛЕНИЯ Реквизитов задачи, на те, что получены от клиента
        $query = "DELETE FROM `REST_CRUD_test`.`$this->table_name` WHERE `$this->table_name`.`ID` = ". $id;

        $returnObj = false;
        try {
            $returnObj = $this->conn->query($query);
//            print_r($returnObj->fetchAll()); // Не всегда показывает ошибку. Нужно доработать: после попытки удаления проверять, сохранилась за удаляемая строка (запись) или нет +++
            return false;
        } catch (PDOException $e) {
            return true;
        }
    }

}


class show_DATA_types extends CRUD_methods{

    public function show_DATA_types_MySQL(){ // В будущем, возможно, делать запрос к БД для определения фактических типов полей +++
/****    ЭТО КОРРЕКТНО РАБОТАЕТ В рнр 5.3. А в РНР 8 ЧАСТИЧНО(!) ИЗМЕНИЛСЯ(!) формат данных, выводимых методом fetchall(). Так портят язык РНР.  ****/
// Два вида запроса, на выбор
  /*      $query = array("SHOW COLUMNS  FROM $this->table_name FROM REST_CRUD_test",
            $query = "SELECT   COLUMN_NAME, DATA_TYPE FROM  INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_SCHEMA = 'REST_CRUD_test' AND  TABLE_NAME = '$this->table_name'");
*/

        return field_MAX_TYPE_SIZE('');
    }


}




