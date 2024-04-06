<?php
// Контроллер отвечает за маршрутизацию 
// и действия для каждого раздела,
// подключаемые страницы. Контроллер Home
// открывает главную страницу всего сайта.
class Controller_Home extends Controller {
    // Для выбор конкретной модели потребуется
    // переопределить метод-конструктор:
    public function __construct() {
        // Статическим образом возможно вызвать
        // родительский метод-конструктор,
        // чтобы не переопределять View
        Controller::__construct();
        // Далее происходит выбор модели Home:
        $this->model = new Model_Home();
    }

    function action_index() {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = (isset($_SESSION['user_id']))? $this->model->suggest_books(true, $_SESSION['user_id']): $this->model->suggest_books(false);

        $this->view->generate("pages/home_view.php", "template_view.php", $data);
    }

    function action_about() {
        $this->view->generate("pages/about_view.php", "template_view.php");
    }

    function action_contacts() {
        $this->view->generate("pages/contact_view.php", "template_view.php");
    }
}