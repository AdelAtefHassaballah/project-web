<?php
require './database/config.php';

class UserRegistration
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /*
    |--------------------------------------------------------------------------
    | register User Function
    |--------------------------------------------------------------------------
    */

    public function registerUser($table, $request)
    {
        $name = $request['name'];
        $email = $request['email'];
        $password = $request['password'];
        $confirm_password = $request['confirm_password'];
        $phone = $request['phone'];
        $role = $request['role'];
        if ($password !== $confirm_password) {
            return 400; // Bad request
        }

        $check_email_query = "SELECT * FROM `$table` WHERE email = ?";
        $check_email_stmt = $this->conn->prepare($check_email_query);
        $check_email_stmt->bind_param('s', $email);
        $check_email_stmt->execute();
        $check_email_result = $check_email_stmt->get_result();

        if ($check_email_result->num_rows > 0) {
            return 400; // Bad request
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_user_query = "INSERT INTO `$table` (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)";
        $insert_user_stmt = $this->conn->prepare($insert_user_query);
        $insert_user_stmt->bind_param('sssss', $name, $email, $hashed_password, $role, $phone);
        $insert_user_result = $insert_user_stmt->execute();

        if ($insert_user_result) {
            $user_id = $insert_user_stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email; 
            $_SESSION['user_name'] = $name; 
            $_SESSION['user_role'] = $role; 
            return 200; // Success
        } else {
            return 500; // Internal server error
        }
    }
}


// Usage
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
//     $userRegistration = new UserRegistration($conn);
//     $name = isset($_POST['name']) ? $_POST['name'] : '';
//     $email = isset($_POST['email']) ? $_POST['email'] : '';
//     $password = isset($_POST['password']) ? $_POST['password'] : '';
//     $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
//     $role = isset($_POST['role']) ? $_POST['role'] : '';
//     $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

//     $result = $userRegistration->registerUser('users', $name, $email, $password, $confirm_password, $role, $phone);
// }
