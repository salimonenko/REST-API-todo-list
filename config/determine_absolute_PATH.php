<?php
// Рекурентно определяем абсолютный путь к начальному каталогу  REST-API-todo-list
function PATH($path, $i=0){
    while (basename($path) !== 'REST-API-todo-list' && $i++ < 10){
        $path = realpath($path. '/../');
    }
    return $path;
};
