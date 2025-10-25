# REST-API-todo-list
CRUD operations for tasks: POST, GET, PUT, DELETE, CHECK_PROBLEMS

A Simple Task Management API project
Implement a REST API for managing a To-Do List in PHP using Laravel.

Implementation Requirements:
1. Create a pure PHP project.
2. Implement an API with CRUD operations for tasks:

  o Create a task: POST /tasks (fields: title, description, status).
  o View a list of tasks: GET /tasks (returns all tasks).
  o View a single task: GET /tasks/{id}.
  o Update a task: PUT /tasks/{id}.
  o Delete a task: DELETE /tasks/{id}.
  o Displaying the project structure (files and directories) with line numbers indicating where, in my opinion, improvements are required (in the files they are indicated by three plus signs + + + without spaces): CHECK_PROBLEMS.

3. Validate data (for example, title must not be empty).
4. Use MySQL as a database.

The project has been successfully tested in PHP 5.3, PHP 8.0.9.

The project has the following significant flaws:
When attempting to update or delete a record, a confirmation pozitive message is always displayed, regardless of whether the corresponding operation (request) was successful. Errors are not always displayed. For example, when attempting to delete a record with a non-existent ID, no error is displayed.

Разработка простого API для управления задачами
Реализовать REST API для управления списком задач (To-Do List) на PHP с использованием Laravel.

Требования к реализации:
1.	Создать проѳкт чистом PHP.
2.	Реализовать API с CRUD-операциями для задач:
	о Создание задачи: POST /tasks (поля: title, description, status). 
	о Просмотр списка задач: GET /tasks (возвращает все задачи). 
	о Просмотр одной задачи: GET /tasks/{id}. 
	о Обновление задачи: PUT /tasks/{id}. 
	о Удаление задачи: DELETE /tasks/{id}.
	о Вывод на экран структуры проекта (файлы и каталоги) с указанием номеров строк, где, по моему мнению, требуются доработки (в файлах они обозначены тремя плюсами + + + без пробелов): CHECK_PROBLEMS.
	
3.	Валидация данных (например, title не должен быть пустым).
4.	Использовать MySQL в качестве базы данных.

Проект успешно протестирован в РНР 5.3, РНР 8.0.9. 

Проект имеет следующие важные недоработки:
При попытке обновления или удаления записи всегда выводится утвердительное сообщение, вне зависимости от того, успешно ли выполнилась соответствующая операция (запрос). Ошибки же выводятся не во всех случаях. Например, по запросу удаления записи по несуществующему ID ошибка не выводится. 
