<?php
class Controller_Cart extends Controller {
    public function __construct() {
        Controller::__construct();
        $this->model = new Model_Cart();
    }

    public function action_index() {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

 
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

    
            $cart_items = $this->model->get_cart_items($user_id);

            
            $data = array(
                'cart_items' => $cart_items,
                'title' => 'Cart'
            );

            $this->view->generate('pages/cart_view.php', 'template_view.php', $data);
        } else {

            echo "user is not logged in";
        }
    }



    public function action_add(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['user_id'];
            $book_id = $_POST['book_id'];
            $quantity = $_POST['quantity'];
        
        
            $this->model->add_to_cart($user_id, $book_id, $quantity);
            header("Location: http://localhost/hws/my_prj/public_html/cart/edit_cart");

            
        }

        
        
    }

    public function action_pay(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['user_id'])) {
            header("Location: /hws/my_prj/public_html/user/login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            
            $otp = mt_rand(100000, 999999);

            $_SESSION['transaction'] = array(
                "first_name" => $_POST["first_name"],
                "last_name" => $_POST["last_name"],
                "last_four_digits" => substr($_POST["card_number"], -4),
            );

            $user_id = $_SESSION['user_id'];
            $_SESSION['cart'] =  $this->model->get_cart_items($user_id);

            echo $otp;
            exit();
        }
        

        $this->view->generate('pages/payment_view.php', 'template_view.php');
    }

    public function action_clear(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['user_id'])) {
            header("Location: /hws/my_prj/public_html/user/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $this->model->clear_cart($user_id);

        header("Location: /hws/my_prj/public_html/home");
        exit;
    }



    public function action_edit_cart() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['user_id'])) {
            header("Location: http://localhost/hws/my_prj/public_html/home");
            exit;
        }
    
        $user_id = $_SESSION['user_id'];
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST['delete_item']) && isset($_POST['cart_id'])) {
                $cart_id = $_POST['cart_id'];
                $this->model->remove_from_cart($user_id, $cart_id);
                header("Location: http://localhost/hws/my_prj/public_html/cart/edit_cart");

            }
            

            if (isset($_POST['update_quantity']) && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
                $cart_id = $_POST['cart_id'];
                $quantity = $_POST['quantity'];
                $this->model->update_cart_item($user_id, $cart_id, $quantity);
                header("Location: http://localhost/hws/my_prj/public_html/cart/edit_cart");
            }
        }
    
        $cart_items = $this->model->get_cart_items($user_id);
    
        $this->view->generate('pages/edit_cart_view.php', 'template_view.php', ['cart_items' => $cart_items]);
    }
    

    public function action_api_add(){
        try{
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        
                if(!isset($_POST['json'])){
                    echo "it's an api func";
                    exit;
                }
                
                if(json_decode( $_POST['json'])!== true){
                    echo "json func should be true";
                    exit;
                }
                
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                
                if (!isset($_SESSION['user_id'])) {
                    
                    $this->view->json(new Response(403, "Not Authorized"));
                    exit;
                }
    
                $user_id = $_SESSION['user_id'];
                
                if (isset($_POST['book_id']) && isset($_POST['quantity'])){
                    $book_id = $_POST['book_id'];
                    $quantity = $_POST['quantity'];
                    $this->model->add_to_cart($user_id, $book_id, $quantity);
                    $this->view->json(new Response(200, "New book added to cart"));
                }
                
                
                exit;
            }
    
            $this->view->json(new Response(404, "Empty"));
        }
        catch(Exception $e){
            $this->view->json(new Response(400,$e->getMessage()));
        }

    }

    public function action_api_update(){

        try{
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
                if(!isset($_POST['json'])){
                echo "it's an api func";
                exit;
            }
            
            if(json_decode( $_POST['json'])!== true){
                echo "json func should be true";
                exit;
            }
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['user_id'])) {
                
                $this->view->json(new Response(403, "Not Authorized"));
                exit;
            }
            
            $user_id = $_SESSION['user_id'];
            
            if (isset($_POST['book_id']) && isset($_POST['quantity'])){
                $book_id = $_POST['book_id'];
                $quantity = $_POST['quantity'];
                $this->model->update_cart_item($user_id, $book_id, $quantity);
                $this->view->json(new Response(200, "Book Updated"));
            }
            
            exit;
        }
        
            $this->view->json(new Response(404, "Empty"));
        }
        catch(Exception $e){
            $this->view->json(new Response(400,$e->getMessage()));
        }
    }

}