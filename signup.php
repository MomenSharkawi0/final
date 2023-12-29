<?php

function checkPasswordStrength($pass)
{
    $uppercase = preg_match('@[A-Z]@', $pass);
    $lowercase = preg_match('@[a-z]@', $pass);
    $number = preg_match('@[0-9]@', $pass);
    $specialChar = preg_match('@[^\w]@', $pass);
    if (!$uppercase || !$lowercase || !$number || !$specialChar || strlen($pass) < 8) {
        echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize/validate if needed
    $id = filter_var($_POST["idnumber"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL);
    $pass = $_POST["passcode"];

    // Hash the password
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Check if the ID has a length of 8 characters
    if (strlen($id) !== 8) {
        echo "Invalid ID format. ID should have a length of 8 characters.";
        exit;
    }
    checkPasswordStrength($pass);
    // Check the ID prefix and set the faculty variable accordingly
    if (substr($id, 0, 4) === "2211") {
        $faculty = "Physical Therapy";
    } elseif (substr($id, 0, 4) === "2212") {
        $faculty = "Engineering";
    } elseif (substr($id, 0, 4) === "2213") {
        $faculty = "Business";
    } else {
        $faculty = "Employee";
    }
    if (!str_contains($email, strval($id))) {
        echo "Invalid email format. Email should have the same id that you entered.";
        exit;
    }

    if (!preg_match('/\.\d{8}@muc\.edu\.eg$/', $email)) {
        echo "Invalid email format. Email should end with '.8digits+@muc.edu.eg'.";
        exit;
    }


    // Database connection details
    $servername = "64.226.68.255";
    $username = "phpmyadmin";
    $password = "MoMeN011**";
    $dbname = "sharkawi_muc";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the ID already exists in the database
    $check_id_stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $check_id_stmt->bind_param("s", $id);
    $check_id_stmt->execute();
    $check_id_result = $check_id_stmt->get_result();





    // Check if the email already exists in the database
    $check_email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();


    if (($check_id_result->num_rows > 0) && ($check_email_result->num_rows > 0)) {
        echo "User already exists! with same data";
        exit;
    }

    if ($check_id_result->num_rows > 0) {
        // ID already exists, echo a message and exit
        echo "ID already exists!";
        exit;
    }

    if ($check_email_result->num_rows > 0) {
        // Email already exists, echo a message and exit
        echo "Email already exists!";
        exit;
    }

    // Prepare and execute an SQL query to insert data into the database
    $insert_stmt = $conn->prepare("INSERT INTO users (id, email, password, faculty) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("ssss", $id, $email, $pass, $faculty);

    if ($insert_stmt->execute()) {
        echo "Data saved successfully!";
    } else {
        echo "Error: " . $insert_stmt->error;
    }

    // Close the statements and database connection
    $check_id_stmt->close();
    $check_email_stmt->close();
    $insert_stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
