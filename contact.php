<?php
session_start();
require_once 'database.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['account_id'])) {
        die("You must be logged in to send a message.");
    }

    // Get user_id from users_profiles
    $accountId = $_SESSION['account_id'];

    // Fetch the user_id from users_profiles
    $stmt = $conn->prepare("SELECT user_id FROM users_profiles WHERE account_id = ?");
    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        die("User profile not found.");
    }

    $user = $result->fetch_assoc();
    $userId = $user['user_id'];

    // Get and sanitize form data
    $category = $_POST['category'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate required fields
    if (empty($category) || empty($name) || empty($email) || empty($message)) {
        die("All fields are required.");
    }

    // Insert into contacts table
    $insert = $conn->prepare("INSERT INTO contacts (user_id, category, name, email, message) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("issss", $userId, $category, $name, $email, $message);

    if ($insert->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Error: Could not send message.";
    }

    $stmt->close();
    $insert->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
