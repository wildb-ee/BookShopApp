<?php
session_start();

class Controller_User extends Controller {
    public function __construct() {
        Controller::__construct();
        $this->model = new Model_User();
    }

    public function action_index() {

    }

    public function action_register() {
        
        try {
            
            if (isset($_SESSION['user_id'])) {
                header("Location: http://localhost/hws/my_prj/public_html/");
                exit;
            }
        
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST["username"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $phone = $_POST["phone"];
                $firstName = $_POST["first_name"];
                $lastName = $_POST["last_name"];
                $is_admin = isset($_POST['is_admin']) ? 1 : 0;

                if($username == null || $email == null  || $password == null 
                || $phone == null || $firstName == null || $lastName == null){
                    throw new Exception("Some fields are empty fill them");
                }

        
                $registration_success = $this->model->register_user($username, $email, $password, $phone, $firstName, $lastName, $is_admin);
        
                if ($registration_success) {

                    header("Location: login");
                    exit;

                } else {

                    throw new Exception("Registration failed, try again");

                }
            }
        
            $this->view->generate('pages/register_view.php', 'template_view.php', isset($error_message) ? ["error_message" => $error_message] : []);
        
        }
        catch (Exception $e) {

            $error_message = $e->getMessage();
            $this->view->generate('pages/error.php', 'template_view.php', $error_message);
            exit;

        }

    }

    public function action_login() {

        try {
        if (isset($_SESSION['user_id'])) {
            header("Location: http://localhost/hws/my_prj/public_html/");
            exit;
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            

                $username = $_POST["username"];
                $password = $_POST["password"];
                
                if($username== null || $password == null){
                    throw new Exception("Username or password not entered");
                }
    
                $user_data = $this->model->login_user($username, $password);
                
                if ($user_data) {
                    $_SESSION['user_id'] = $user_data['user_id'];
                    $_SESSION['is_admin'] = $user_data['is_admin'];
    
                    header("Location: profile");
                    exit;
                } else {
                    throw new Exception('Invalid username or password.');
                }


        }
        $this->view->generate('pages/login_view.php', 'template_view.php');

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $this->view->generate('pages/error.php', 'template_view.php', $error_message);
        exit;}
    }


    public function action_profile() {

        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit;
        }


        $user_id = $_SESSION['user_id'];
        $user_data = $this->model->get_user($user_id);
        $user_data['addresses'] = $this->model->get_addresses($user_id);
    
        $this->view->generate('pages/profile_view.php', 'template_view.php', $user_data);
    }

    public function action_save_message(){

        try{
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $theme = $_POST['theme'];
                $message = $_POST['message'];

                if($theme == null || $message == null){
                    throw new Exception("Theme or Message is empty");
                }
                
                if(!isset($_SESSION["user_id"])){
                    $first_name = $_POST['first_name'];
                    $last_name = $_POST['last_name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];

                    if($first_name == null || $last_name == null || $email == null || $phone == null){
                        throw new Exception("Some of the Credentials are empty");
                    }

                    $this->model->save_message($first_name,$last_name,$email,$phone,$theme,$message);
                    header("Location: /hws/my_prj/public_html/home");
                }

                else{
                    $user = $this->model->get_user($_SESSION["user_id"]);
                    $this->model->save_message($user["first_name"],$user["last_name"],$user["email"],$user["phone"],$theme,$message);
                    header("Location: /hws/my_prj/public_html/home");
                    
                }
                
            }
    
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            $this->view->generate('pages/error.php', 'template_view.php', $error_message);
            exit;}

    }




   public function action_edit_profile() {


        try{
            if (!isset($_SESSION['user_id'])) {
                header("Location: login");
                exit;
            }
    
            $user_id = $_SESSION['user_id'];
            $user_data = $this->model->get_user($user_id);
            $user_data['addresses'] = $this->model->get_addresses($user_id);
    
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $user_fields = array(
                    'first_name' => $_POST["first_name"], 
                    'last_name' => $_POST["last_name"],   
                    'phone' => $_POST["phone"],
                    'password' => $_POST["password"],
                );

                if ($user_fields['password']== null || preg_match('/^\s+$/', $user_fields['password'])){
                    unset($user_fields['password']);
                }
                
                if($user_fields['first_name']== null || $user_fields['last_name']== null||
                    $user_fields['phone']== null){
                        throw new Exception("Credentials Cannot be Empty");
                }
                

                $result = $this->model->update_user($user_id, $user_fields);
    
                if ($result) {
                    if (isset($_POST['street']) && isset($_POST['city'])) {
                        $streets = $_POST['street'];
                        $cities = $_POST['city'];
    
                        foreach ($streets as $index => $street) {
                            $city = $cities[$index];
                            
                            if (isset($_POST['address_id'][$index])) {
                                $address_id = $_POST['address_id'][$index];
                                $this->model->update_address($user_id,$address_id, $street, $city);
                            } else {
                                $this->model->add_address($user_id, $street, $city);
                            }
                        }
                    }
    
    
                    echo json_encode(array("status" => "success"));
                    exit;
                } else {
    
                    echo json_encode(array("status" => "error"));
                    exit;
                }
            }
    
    
            $this->view->generate('pages/edit_profile_view.php', 'template_view.php', $user_data);



        }
        catch (Exception $e) {
            $error_message = $e->getMessage();
            $this->view->generate('pages/error.php', 'template_view.php', $error_message);
            exit;
        }

    }


    public function action_sign_out() {
        session_unset();

        session_destroy();

        header("Location: login");
        exit;
    }

    public function action_api_get(){
        try{
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
        
        
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
                $user_data = $this->model->get_user($user_id);

                $this->view->json(new Response(200, "OK", new User($user_data["username"], $user_data["email"])));
    
            }
            $this->view->json(new Response(404, "Empty"));
        }
        catch(Exception $e){
            $this->view->json(new Response(400,$e->getMessage()));
        }
    }

    public function action_api_edit(){
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
                $user_data = $this->model->get_user($user_id);

                if(isset($_POST['username']) || isset($_POST['email'])){
                    $username = $user_data["username"];
                    $email = $user_data["email"];

                    if(isset($_POST['username'])){
                        $username = $_POST['username'];
                        $this->model->update_user($user_id, $username, $email);
                    }
                    if(isset($_POST['email'])){
                        $email = $_POST['email'];
                        $this->model->update_user($user_id, $username, $email);
                    }
                    
                    $user_data = $this->model->get_user($user_id);
                    $this->view->json(new Response(200, "Profile Changed", new User($user_data["username"], $user_data["email"])));
                    exit;
                }
    

            }
            $this->view->json(new Response(404, "Empty"));
        }
        catch(Exception $e){
            $this->view->json(new Response(400,$e->getMessage()));
        }

    }



    public function action_api_login(){
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
                
    
                $email = $_POST["email"];
                $password = $_POST["password"];
                
    
                $user_data = $this->model->login_user($email, $password);
                
                if ($user_data) {
                    $_SESSION['user_id'] = $user_data['user_id'];
        
                    $this->view->json(new Response(200, "Login Successful"));

                } else {
                    $this->view->json(new Response(400, "Invalid password or login."));
                }
                
                exit;
            }
            $this->view->json(new Response(404, "Empty"));            
        }
        catch(Exception $e){
            $this->view->json(new Response(400,$e->getMessage()));
        }
        
    }

    public function action_api_register(){
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
                
    
                $username = $_POST["username"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                
                
                $registration_success = $this->model->register_user($username, $email, $password);
                
                if ($registration_success) {
                    
                    $this->view->json(new Response(200, "Registration Successful", new User($username, $email)));
                
                } else {
                    
                    $this->view->json(new Response(400, "Registration failed. Please try again."));
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