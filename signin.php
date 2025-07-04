<?php
session_start();
require_once 'database.php'; // connect to DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If account found
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Save session (or redirect)
            $_SESSION['account_id'] = $user['account_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Redirect based on role (optional)
            header("Location: index.html");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
