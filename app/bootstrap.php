<?php
// Объявление констант в точке входа
// задаст значения конфигурации единожды.
define("HOST", "localhost");
define("DATABASE", "bookshop_db");
define("PORT", 3306);
define("CHARSET", "utf8mb4");
define("USER", "root");
define("PASSWORD", "");

// Подключение основных классов
// для работы приложения.
require_once "core/Database.php";
require_once "core/Model.php";
require_once "core/View.php";
require_once "core/Controller.php";
require_once "core/Route.php";
require_once "core/DTOs/Response.php";
require_once "core/DTOs/User.php";


// Запуск маршрутизации.
Route::init();
