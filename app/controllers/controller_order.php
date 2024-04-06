<?php
class Controller_Order extends Controller {
    public function __construct() {
        Controller::__construct();
        $this->model = new Model_Order();
    }

    public function action_promocode(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {

            header("Location: /hws/my_prj/public_html/user/login");
            exit;
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $promo_code = $_POST['promo_code'];
    
            $is_valid_promo_code = $this->model->validate_promo_code($promo_code);
    
            if ($is_valid_promo_code) {
                $_SESSION['promo_code'] = $promo_code;
                $discounted_cart = $this->model->apply_promo_code_to_cart($_SESSION['cart'], $this->model->get_promo_code_details($promo_code));
                $this->view->generate('pages/enter_promocode.php', 'template_view.php', ['discounted_cart' => $discounted_cart]);

            } else {
                $error_message = "Invalid promo code. Please try again.";
                $this->view->generate('pages/enter_promocode.php', 'template_view.php', ['error_message' => $error_message]);
            }
        } else {
            $this->view->generate('pages/enter_promocode.php', 'template_view.php');
        }

    }

    public function action_create() {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {

            header("Location: /hws/my_prj/public_html/user/login");
            exit;
        }


        $user_id = $_SESSION['user_id'];


        $cart_items = $_SESSION['cart'];

        $promo_code = $_SESSION['promo_code'];

        
        if (empty($cart_items)) {

            header("Location: /hws/my_prj/public_html/cart/view");
            exit;
        }


        $order_id = $this->model->make_order($user_id, $cart_items, $promo_code);

        unset($_SESSION['cart']);

        header("Location: /hws/my_prj/public_html/cart/clear");
        exit;
    }

    public function action_all(){

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {

            header("Location: /hws/my_prj/public_html/user/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        $orders = $this->model->get_all_user_orders($user_id);



        $this->view->generate('pages/user_orders.php', 'template_view.php',["orders"=>$orders]);

    }



    public function action_admin_change(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin'])) {

            header("Location: /hws/my_prj/public_html/home");
            exit;
        }

        $data = array(
            "orders" => $this->model->get_all_orders(),
            "order_details" => $this->model->get_order_details(),
            "user_addresses" => $this->model->get_all_addresses()
        );

        $statistics = array(
            "orders_made_today" => $this->model->get_orders_made_today(),
            "average_order_amount_today" => $this->model->get_average_order_amount_today(),
            "orders_in_delivery" => $this->model->get_orders_in_delivery(),
            "orders_delivered" => $this->model->get_orders_delivered(),
            "orders_canceled" => $this->model->get_orders_canceled()
        );
    
        $data['statistics'] = $statistics;
    



        $this->view->generate('pages/admin_change_view.php', 'template_view.php',$data);
    }


    public function action_change_status() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin'])) {

            header("Location: /hws/my_prj/public_html/home");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $order_id = $_POST['order_id'];
            $new_status = $_POST['new_status'];


            $success = $this->model->change_delivery_status($order_id, $new_status);
            header("Location: /hws/my_prj/public_html/order/admin_change");

        }


    }


    public function action_update_details() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin'])) {

            header("Location: /hws/my_prj/public_html/home");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $order_id = $_POST['order_id'];
            $book_id = $_POST['book_id'];
            $new_quantity = $_POST['new_quantity'];


            $success = $this->model->update_order_details($order_id, $book_id, $new_quantity);
            header("Location: /hws/my_prj/public_html/order/admin_change");

        }

    }


    public function action_change_address() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin'])) {

            header("Location: /hws/my_prj/public_html/home");
            exit;
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $order_id = $_POST['order_id'];
            $new_address = $_POST['delivery_address'];


            $success = $this->model->change_delivery_address($order_id, $new_address);
            header("Location: /hws/my_prj/public_html/order/admin_change");

        }


    }

    public function action_delete_detail(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin'])) {

            header("Location: /hws/my_prj/public_html/home");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $order_id = $_POST['order_id'];
            $book_id = $_POST['book_id'];

            $success = $this->model->delete_order_detail($order_id, $book_id);
            header("Location: /hws/my_prj/public_html/order/admin_change");

        }

    }


    public function action_cancel() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_admin'])) {

            header("Location: /hws/my_prj/public_html/home");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $order_id = $_POST['order_id'];


            $success = $this->model->cancel_order($order_id);
            header("Location: /hws/my_prj/public_html/order/admin_change");

        }


        

    }

}