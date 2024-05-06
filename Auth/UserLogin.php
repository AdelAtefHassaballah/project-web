<?php
require './database/config.php';

class UserLogin
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /*
    |--------------------------------------------------------------------------
    | login User Function
    |--------------------------------------------------------------------------
    */

    public function loginUser($table, $request)
    {

        $email = $request['email'];
        $password = $request['password'];

        $check_user_query = "SELECT * FROM `$table` WHERE email = ?";
        $check_user_stmt = $this->conn->prepare($check_user_query);
        $check_user_stmt->bind_param('s', $email);
        $check_user_stmt->execute();
        $check_user_result = $check_user_stmt->get_result();

        if ($check_user_result->num_rows == 1) {
            $user = $check_user_result->fetch_assoc();
            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                return http_response_code(200); 
            } else {
                return http_response_code(401);
            }
        } else {
            return http_response_code(404);
        }
    }
}
