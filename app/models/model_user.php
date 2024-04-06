<?php


class Model_User extends Model {
    public function get_data() {
        return [
            "title" => "User"
        ];
    }

    private function logAuthentication($userId) {
        $actionName = 'Authentication';
        $this->logAction($userId, $actionName);
    }

    private function logAddressChange($userId) {
        $actionName = 'Address Change';
        $this->logAction($userId, $actionName);
    }



    private function logFormSubmission($userId) {
        $actionName = 'Form Submission';
        $this->logAction($userId, $actionName);
    }

    private function logAction($userId, $actionName) {
        $query = "INSERT INTO logs (userId, idAction, datetime) VALUES (:userId, (SELECT idAction FROM actions WHERE name = :actionName), NOW())";
        $params = array(':userId' => $userId, ':actionName' => $actionName);
        $this->db->insert($query, $params);
    }



    public function get_user($id) {

        $query = "SELECT * FROM users WHERE user_id = :id";
        $params = ["id" => $id];

        if ($this->db->getCount($query, $params) == 0) {
            return false;
        } else {
            return $this->db->getRow($query, $params);
        }
    }

    public function register_user($username, $email, $password, $phone, $firstName, $lastName, $is_admin) {
        $existing_user = $this->get_user_by_username_or_email($username, $email);
        if ($existing_user) {
            throw new Exception( "User with the provided username or email already exists.");
        }
    
        $hashed_password = $this->p_hash($password);
    
        $query = "INSERT INTO users (username, email, phone, first_name, last_name, password, is_admin) VALUES (:username, :email, :phone, :first_name, :last_name, :password, :is_admin)";
        $params = ["username" => $username, "email" => $email, "phone" => $phone, "first_name" => $firstName, "last_name" => $lastName, "password" => $hashed_password, "is_admin" => $is_admin];
        
        return $this->db->insert($query, $params);
    }
    
    private function get_user_by_username_or_email($username, $email) {
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $params = ["username" => $username, "email" => $email];
        return $this->db->getOne($query, $params);
    }







    public function update_user($user_id, $fieldsToUpdate) {
        $params = array();

        $updateQuery = "UPDATE users SET ";

    
        foreach ($fieldsToUpdate as $field => $value) {
            if ($field === 'password') {
                $updateQuery .= "password = :password,";
                $params[":password"] = $this->p_hash($value);
            } elseif ($field !== 'username' && $field !== 'email') {
                $updateQuery .= "$field = :$field, ";
                $params[":$field"] = $value;
            }
        }
    
        $updateQuery = rtrim($updateQuery, ", ");
    
        $updateQuery .= " WHERE user_id = :user_id";
        $params[':user_id'] = $user_id;
    
        $result = $this->db->update($updateQuery, $params);
    
        return $result;
    }
    
    public function add_address($user_id, $street, $city) {
        $query = "INSERT INTO addresses (user_id, street, city) VALUES (:user_id, :street, :city)";
        $params = array(
            ':user_id' => $user_id,
            ':street' => $street,
            ':city' => $city
        );
        $this->logAddressChange($user_id);
        $result = $this->db->insert($query, $params);
        return $result;
    }

    public function get_addresses($user_id) {
        $query = "SELECT * FROM addresses WHERE user_id = :user_id";
        $params = array(':user_id' => $user_id);
        $addresses = $this->db->getAll($query, $params);
        
        return $addresses;
    }
    
    public function update_address($user_id,$address_id, $street, $city) {
        $query = "UPDATE addresses SET street = :street, city = :city WHERE address_id = :address_id";
        $params = array(
            ':street' => $street,
            ':city' => $city,
            ':address_id' => $address_id
        );
        $this->logAddressChange($user_id);
        $result = $this->db->update($query, $params);
        return $result;
    }
    
    private function p_hash($password){
        $salt = base64_encode(random_bytes(16));
        $hashed_password = crypt($password, '$5$' . $salt);

        return $hashed_password;
    }


    public function login_user($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $params = ["username" => $username];
        $user = $this->db->getRow($query, $params);

        if (!$user){
            throw new Exception("No User Found");
            exit;
        }
        
        $salt = substr($user['password'], 3, 22);
        $new_hashed_password = crypt($password, '$5$' . $salt);
        
        $this->logAuthentication($user['user_id']);
        if ($user && $user['password'] === $new_hashed_password) {
            return $user;
        } else {
            return false;
        }
    }

    public function save_message($first_name, $last_name, $email, $phone, $theme, $message) {
        $query = "INSERT INTO feedback (first_name, last_name, email, phone, theme, message) 
                  VALUES (:first_name, :last_name, :email, :phone, :theme, :message)";
        
        $params = array(
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":phone" => $phone,
            ":theme" => $theme,
            ":message" => $message
        );
        
        return $this->db->insert($query, $params);
    }


}

