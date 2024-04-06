<?php
// Создание класса View, который будет отвечать
// за рендер страницы по переданному шаблону
class View {
    // Метод generate() принимает в себя три параметра:
    // контент страницы (отдельный файл вёрстки), базовый
    // шаблон (html-основа с meta, head и так далее)
    // и массив  данными, которые будут использоваться
    // на самой странице.
    public function generate($content, $template, $data = null) {
        if(is_array($data)) {
            // Если массив $data был передан, 
            // то из него извлекаются ключи
            // и преобразовываются в переменные
            // для использования в $template и $content:
            // ["title" => "Home"] станет $title = "Home"
            extract($data);
        }
        // Далее подключается файл шаблона
        include "../app/views/$template";
    }

    public function json($data = null) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
    }
}