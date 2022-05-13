<?php

/*

//Расписать пошагово, ход мыслей при разработке компонента

1. данные для коннекта к БД убрать в отдельный файл config.php
2. Коннект к БД вынести в отдельный файл и класс ConnectToBd
3. Основной класс QueryBuilder должен принимать объект ConnectToBd (PDO) в __construct
4. Файл start.php будет возвращать обьект класса QueryBuilder
5. Всю обработку и логику внутри класса QueryBuilder разнести по методам, методы сделать универсальными, способными работать с разными именами таблиц и именами столбцов





// API DOCUMENTATION //

config.php - Данные для соединения с БД

/classes/QueryBuilder(PDO object) - основной класс компонента, принимает объект PDO

$object = include('start.php') создает обьект класса QueryBuilder для дальнейшей работы с его методами
далее в документации $object это обьект класса QueryBuilder

ПОЛУЧЕНИЕ ВСЕХ ДАННЫХ С ТАБЛИЦЫ
array $object->getAll(string $table = "название таблицы")

ПОЛУЧЕНИЕ ОДНОЙ СТРОКИ ПО ID
array $object->getOne(string $table = "название таблицы", int $id = "идентификатор")

УДАЛЕНИЕ СТРОКИ
$object->delete(string $table = "название таблицы", int $id = "идентификатор")

СОЗДАНИЕ СТРОКИ
$object->create(string $table = "название таблицы", array $data = "данные из формы для добавления в строку")

РЕДАКТИРОВАНИЕ СТРОКИ
$object->edit(string $table = "название таблицы", array $data = "данные из формы для изменения строки", int id = "идентификатор")
*/
class QueryBuilder {

    protected $pdo;

    public function __construct($pdo) {        
        $this->pdo = $pdo;
    }

    public function getAll($table) {
        $statement = $this->pdo->query("SELECT * FROM {$table}");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($table, $id) {
        $sql = "SELECT * FROM {$table} WHERE id={$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'id' => $id
        ]);
        header("Location:index.php");
    }

    public function create($table, $data) {
        $keys = implode(',', array_keys($data));
        $tags = ':'.implode(', :', array_keys($data));   
        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$tags})";    
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
    }

    public function edit($table, $data, $id) {

        $keys = array_keys($data);

        $string ='';

        foreach ($keys as $key) {
            $string .= $key . "=:" . $key . ',';
        }

        $keys = rtrim($string, ',');
        $data['id'] = $id;

        $sql = "UPDATE {$table} SET {$keys} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);

        header("Location: index.php");
    }
}

?>