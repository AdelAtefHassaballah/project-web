<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: $redirect_url");
    exit;
}

require 'Auth/UserLogin.php';
require 'Traits/ValidatorTrait.php';
require 'database/config.php';

class DatabaseOperations
{
    use ValidatorTrait;

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function validateFormData($data)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        return $this->validateRequestData($data, $rules);
    }
}

$databaseOperations = new DatabaseOperations($conn);
$userLogin = new UserLogin($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

    $data = [
        "email" => $email,
        "password" => $password
    ];

    $result = $databaseOperations->validateFormData($data);
    if (!$result) {
        $response  = $userLogin->loginUser('users', $data);
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
                    <a href="signup.php">Don't have an account?<span></span> Sign Up</a>
                    <header>Login</header>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="input-box">
                        <input type="email" class="input-field" name="email" placeholder="Username or Email">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" class="input-field" placeholder="Password">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" name="submit" value="Sign In">
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