<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: $redirect_url");
    exit;
}

require 'database/config.php';
require 'Auth/UserRegistration.php';
require 'Traits/ValidatorTrait.php';

class DatabaseOperations
{
    use ValidatorTrait;

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function validateFormData($data)
    {
        $rules = [
            'name' => 'required|string|max:45',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:11|min:11',
        ];

        return $this->validateRequestData($data, $rules);
    }
}


$databaseOperations = new DatabaseOperations($conn);
$userRegistration = new UserRegistration($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = 'employee';

    $data = [
        "name" => $name,
        "email" => $email,
        "password" => $password,
        "confirm_password" => $confirm_password,
        "role" => $role,
        "phone" => $phone
    ];

    $result = $databaseOperations->validateFormData($data);
    if (!$result) {
        $response  = $userRegistration->registerUser('users', $data);
        if ($response == 200) {
            header("Location: index.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>
    <?php require_once 'components/head.php'; ?>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <?php require_once 'components/spinner.php'; ?>

        <div class="form-box">
            <div class="login-container" id="login">
                <div class="top">
                    <span>Already have an account? <a href="login.php">Sign In</a></span>
                    <header>Sign Up</header>
                </div>
                <form method="post" action="signup.php">
                    <div class="input-box">
                        <input type="text" class="input-field" name="name" placeholder="Full Name">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="email" class="input-field" name="email" placeholder="Email">
                        <i class="bx bx-mail-send"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" class="input-field" placeholder="Password">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="tel" name="phone" class="input-field" placeholder="Phone">
                        <i class="bx bxs-phone"></i>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" name="signup" value="Sign Up">
                    </div>
                </form>
                <?php
                if (isset($result)) {
                    $errors = json_decode($result, true);
                    if (!empty($errors['error'])) {
                        echo '<div class="alert alert-danger p-1 m-2 alert-dismissible fade show" role="alert" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-offset="100">';
                        echo '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>';
                        foreach ($errors['error'] as $field => $error) {
                            echo "<strong>$field:</strong> $error<br>";
                        }
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
        <?php require_once 'components/scripts.php'; ?>
</body>

</html>