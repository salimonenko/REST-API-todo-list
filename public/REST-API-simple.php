<?php

define('access', 'permit'); // Константа доступа к модулям
// Для более серьезной защиты нужно, к примеру, реализовать полный запрет непосредственного доступа ко ВСЕМ модулям кроме этого и routes/web.php +++
// Например, с ипользованием сессий/токенов.

header('Content-Type: text/html; charset=utf-8');

require_once '../config/determine_absolute_PATH.php';
// Рекуррентно определяем путь до начального каталога "REST-API-todo-list" (не более 10 итераций)
$path_ABSOLUTE = PATH(__DIR__);

include_once $path_ABSOLUTE. '/database/create_database.php';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>REST API для управления списком задач (To-Do List) на PHP</title>

<style>
    * {font-size: 14px}
    p {margin: 2px; line-height: 100%}

    .save_number, .show_number{display: inline-block}
    .info{border: solid 1px; min-height: 100px; display: inline-block; min-width: 550px; width: 550px; margin: 10px; }
    .show_number{margin-top: 10px}
    .ol{display: inline-block}
    #buttons{display: inline-block; vertical-align: top; margin-left: 20px;}
    #buttons > div {margin: 15px}
    #buttons > div > button {width: 250px; height: 40px}
    #buttons > div.POST, #buttons > div.GET, #buttons > div.PUT, #buttons > div.DELETE {max-width: 250px}
    #buttons > div.POST > input, #buttons > div.GET > input, #buttons > div.PUT > input, #buttons > div.DELETE > input {max-width: 100%; width: 100%; box-sizing: border-box}

    #testing_INFO {display: inline-block; vertical-align: top;}
    #testing_INFO > button {padding: 5px; background-color: red}

</style>

</head>

<body>
<h1>Реализация API с CRUD-операциями для задач:</h1>

<div class="ol">
    <ol>
        <li>Создание задачи: POST /tasks (поля: title, description, status)</li>
        <li>Просмотр списка задач: GET /tasks (возвращает все задачи).</li>
        <li>Просмотр одной задачи: GET /tasks/{id}.</li>
        <li>Обновление задачи: PUT /tasks/{id}.</li>
        <li>Удаление задачи: DELETE /tasks/{id}.</li>
        <li>Посмотреть структуру проекта и узнать о требующихся доработках: CHECK_PROBLEMS</li>
    </ol>
<p>С учетом валидации данных.</p>

    <br/>
    <div id="xhr_message" class="info"></div>
</div>



<div id="buttons">
<div class="POST">
    <button title="Создание задачи: POST /tasks (поля: title, description, status)" value="/tasks{title, description, status}">POST /tasks <br/>(поля: title, description, status)</button>
    <input type="text" name="title" placeholder="title..." />
    <input type="text" name="description" placeholder="description..."/>
    <input type="text" name="status" placeholder="status..." />
</div>

<div class="GET">
    <button title="Просмотр ВСЕХ задач: GET /tasks (возвращает все задачи)." value="/tasks">GET /tasks</button>
    <input type="hidden" name="ID" value="0"/>
</div>

<div class="GET">
    <button title="Просмотр одной задачи: GET /tasks/{id}" value="/tasks/{id}">GET /tasks/{id}</button>
    <input type="text" name="ID" placeholder="task_ID..." />
</div>

<div class="PUT">
    <button title="Обновление задачи: PUT /tasks/{id}" value="/tasks/{id}">PUT /tasks/{id}</button>
    <input type="text" name="ID" placeholder="task_ID..." />
    <input type="text" name="title" placeholder="title..." />
    <input type="text" name="description" placeholder="description..."/>
    <input type="text" name="status" placeholder="status..." />
</div>

<div class="DELETE">
    <button title="Удаление задачи: DELETE /tasks/{id}." value="/tasks/{id}">DELETE /tasks/{id}</button>
    <input type="text" name="ID" placeholder="task_ID..." />
</div>
</div>

<div id="testing_INFO">
    <button>Узнать о требующихся доработках <br/>в этом проекте</button>
</div>


<script>


(function () { // Задаем обработчики кликов для кнопок
    var REST_methods = ['POST', 'GET', 'PUT', 'GET', 'DELETE']; // Строго задаем только возможные AJAX-запросы (белый список)

    var buttons_parent = document.getElementById('buttons');
    buttons_parent.addEventListener("click", function (e) { // Задаем обработчик клика для кнопок
        var button = e.target;

        if(button.nodeName.toLowerCase() === 'button'){

            if(button.parentNode.hasAttribute('class')){ // Есть ли атрибут "класс"
                var className = button.parentNode.getAttribute('class').split(' '); // Разбиваем этот атрибут на несколько классов

                for(var j=0; j < className.length; j++){ // Для каждого класса
                    var className_j = className[j].replace(/ /g, '');
                    if(REST_methods.indexOf(className_j) > -1){
                        var inputs = button.parentNode.getElementsByTagName('input');
                        manager(button, className_j, 'xhr_message', inputs);
                    }
                }
            }
        }
    });

function manager(button, className, id_to_RESPONSE, inputs) {
    var method = encodeURIComponent(className);
    var todo = encodeURIComponent(button.value);

//    alert(className+' '+ inputs.length)
    var data = '';
    for(var i=0; i < inputs.length; i++){
        var valueq = encodeURIComponent(inputs[i].value);
        var nameq = encodeURIComponent(inputs[i].name);

        if(valueq === '' && nameq !== 'GET_ALL_task_number'){
            alert('Не заполнено поле для кнопки: \n' + button.title);
            return;
        }

        data += inputs[i].name + '='+ valueq + '&';
    }

    sender(method, todo, id_to_RESPONSE, data); // Посылаем на сервер метод (POST, GET и т.п.), а также данные сообразно этому методу
}


function sender(method, todo, id_to_RESPONSE, data, path) { // Функция отправляет сообщение на сервер  и ждет того или иного ответа, выводя потом его в alert
        var xhr = new XMLHttpRequest();

        // Готовим тело сообщения для отправки     // в encodeURIComponent преобразует в формат, который может принять сервер
        var body = data + 'METHOD='+method+'&todo='+todo;

        var this_DIR = 'REST-API-todo-list';
        var this_DIR_reg = new RegExp('^(.*?' + this_DIR + ')(.*)$');

        var this_URL = window.location.href;
            this_URL = this_URL.replace(this_DIR_reg, '$1') + '/';

        xhr.open("POST", this_URL+'routes/web.php', true); // Имена всех методов посылаем только методом POST
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function xhr_state() {
            if (xhr.readyState != 4) return;
            if (xhr.status == 200) {

            }else if (xhr.status == 201){
// После подтверждения получения сообщения сервером выдаем оповещение
                alert('Операция '+ method + ' выполнена.');
            }else if(xhr.status == 204){
                alert('Операция '+ method + ' выполнена.');
                document.getElementById(id_to_RESPONSE).innerHTML = 'Нет данных для этого ID.';
            }else {

                alert('xhr message: '+xhr.statusText); // Сообщение об ошибке на транспортном (ТСР) уровне. Обычно вызвано проблемами  с доступом к сети или неправильной работой РНР на сервере, т.п.
            }
            document.getElementById(id_to_RESPONSE).innerHTML = xhr.responseText; // Ответ придет в блок с id=id_to_RESPONSE
        };
        xhr.send(body);
        return false;
 }


// Функция делает запрос на сервер, а он просматривает все файлы проекта и ищет там строчки, содержащие отметки + + + (без пробелов). Это - места, где замечена необходимость доработок
function show_problems() {
    sender('CHECK_PROBLEMS', '', 'xhr_message', '');
}

document.getElementById('testing_INFO').getElementsByTagName('button')[0].onclick = show_problems;

})();

</script>

</body>
</html>