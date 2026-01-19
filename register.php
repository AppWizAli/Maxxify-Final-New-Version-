<?php
session_start();
require_once 'config.php'; // Include DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($email) || empty($name) || empty($password) || empty($confirm_password)) {
        exit("Please fill all fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit("Invalid email address.");
    }

    if ($password !== $confirm_password) {
        exit("Passwords do not match.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into DB
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone_number, password) VALUES (:name, :email, :phone, :password)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':password' => $hashedPassword
        ]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            exit("Email already exists.");
        } else {
            exit("Registration failed: " . $e->getMessage());
        }
    }
}
?>
