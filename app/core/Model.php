<?php
// Модель должна реализовывать конструктор,
// который сразу устанавливает подключение
// к базе данных и открывает видимость
// для классов-наследников.
abstract class Model {
    protected $db;
    public function __construct() {
        $this->db = new Database();
    }
    public function get_data() {
        // return $data;
    }
}