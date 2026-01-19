<?php
session_start();
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
} else {
    $error = "Invalid request.";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Failed</title>
</head>
<body>
  <script>
    alert("<?= $error ?>");
    window.location.href = "SignIn.php";
  </script>
</body>
</html>

