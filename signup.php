<?php
// signup.php

// Include database connection
require_once 'database.php';

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $fullName = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if user agreed to terms
    if (!isset($_POST["agree"])) {
        die("You must agree to the Terms & Conditions.");
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT account_id FROM accounts WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        die("Email already exists.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into accounts table
    $role = 'user'; // default role
    $insertAccount = $conn->prepare("INSERT INTO accounts (email, password, role) VALUES (?, ?, ?)");
    $insertAccount->bind_param("sss", $email, $hashedPassword, $role);

    if ($insertAccount->execute()) {
        $accountId = $insertAccount->insert_id;

        // Insert into users_profiles table
        $insertProfile = $conn->prepare("INSERT INTO users_profiles (account_id, full_name) VALUES (?, ?)");
        $insertProfile->bind_param("is", $accountId, $fullName);

        if ($insertProfile->execute()) {
            header("Location: signin.html");
            exit;
        } else {
            echo "Failed to create user profile.";
        }

    } else {
        echo "Failed to create account.";
    }

    // Close statements and connection
    $checkEmail->close();
    $insertAccount->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
