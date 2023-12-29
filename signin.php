<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST["idnumber"];
    $password = $_POST["passcode"];

    if (strlen($id) !== 8) {
        echo "Invalid ID format. ID should have a length of 8 characters.";
        exit;
    }
    // Database connection details
    $servername = "64.226.68.255";
    $username = "phpmyadmin";
    $password_db = "MoMeN011**";
    $dbname = "sharkawi_muc";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute an SQL query to check if the user exists
    $check_stmt = $conn->prepare("SELECT id, password FROM users WHERE id = ?");
    $check_stmt->bind_param("s", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User found, check password
        $user_data = $check_result->fetch_assoc();
        $stored_password = $user_data["password"];

        if ($password == $stored_password) {
            // Password is correct
            echo "Valid login!";
        } else {
            // Password is incorrect
            echo "Invalid password!";
        }
    } else {
        // User not found
        echo "Invalid ID!";
    }

    // Close the statement and database connection
    $check_stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
