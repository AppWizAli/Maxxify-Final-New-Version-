

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Prepare and execute query
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];

                header("Location:index.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
} else {
    header("Location: signin.php");
    exit;
}
?>

<!-- Display error and redirect -->
<!DOCTYPE html>
<html>
<head>
    <title>Login Failed</title>
</head>
<body>
<script>
    alert("<?= htmlspecialchars($error) ?>");
    window.location.href = "signin.php";
</script>
</body>
</html>
