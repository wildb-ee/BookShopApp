<?php

// Работа с базой данных, как модель управления,
// вынесена в отдельный класс.
class Database {
    // Подключение хранится в приватном поле $pdo.
    private $pdo;
    // В приватном поле $query находится текущий
    // запрос и работа с ним.
    private $query;

    // Конструктор отвечает за инициализацию
    // подключения к базе данных.
    public function __construct() {
        // Далее происходит само подключение
        // в поле всего класса.
        $this->pdo = new PDO(
            "mysql:host=" . HOST . ";" .
            "port=" . PORT . ";" .
            "dbname=" . DATABASE . ";" .
            "charset=" . CHARSET,
            USER, PASSWORD
        );
    }

    // Деструктор отвечает за разрыв соединения
    // посредством обнуления переменных запроса
    // и всего подключения.
    public function __destruct() {
        if ($this->query !== null) {
            $this->query = null;
        }
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }

    // Метод getRow() позволяет получить
    // одну единственную строчку, передав
    // в неё SQL-запрос и параметры-маркеры,
    // возвращает объект.
    public function getRow($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        $this->query->execute($args);
        // Метод fetch() возвращает одну строку.
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    // Метод getAll() позволяет получить
    // многомерный массив объектов (всю таблицу)
    // и принимает такие же аргументы.
    public function getAll($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        $this->query->execute($args);
        // Метод fetchAll() возвращает много строк.
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Метод getCount() позволит получать количество
    // найденных строк по SELECT-запросу.
    public function getCount($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        $this->query->execute($args);
        return $this->query->rowCount();
    }

    // Метод insert() позволит добавлять значения
    // в базу данных по команда и после этого
    // возвращать id вставленного значения. 
    public function insert($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        return ($this->query->execute($args)) ? $this->pdo->lastInsertId() : 0;
    }

    public function update($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        return $this->query->execute($args);
    }

    public function delete($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        return $this->query->execute($args);
    }

    public function getOne($sql, $args = []) {
        $this->query = $this->pdo->prepare($sql);
        $this->query->execute($args);
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }


}