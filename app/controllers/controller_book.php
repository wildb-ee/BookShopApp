<?php
class Controller_Book extends Controller {
    public function __construct() {
        Controller::__construct();
        $this->model = new Model_Book();
    }


    public function action_catalog() {
        $data = $this->model->get_all_books();
         
        
        $this->view->generate('pages/catalog_view.php', 'template_view.php', $data);
    }

    public function action_search() {
        $find = isset($_GET['find']) ? $_GET['find'] : '';
        $author_first_name = isset($_GET['author_first_name']) ? $_GET['author_first_name'] : '';
        $author_last_name = isset($_GET['author_last_name']) ? $_GET['author_last_name'] : '';
        $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
        $price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
        $price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';

 
        $search_results = $this->model->search_books($find, $author_first_name, $author_last_name, $genre, $price_min, $price_max);

        $data = [
            'search_results' => $search_results,
            'is_authorized' => isset($_SESSION['user_id'])
        ];

        $this->view->generate('pages/search_view.php', 'template_view.php', $data);
    }
}