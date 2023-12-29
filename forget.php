<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

    $servername = "92.205.147.175";
    $username = "momen";
    $password_db = "MoMeN011**";
    $dbname = "sharkawi_muc";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Create database connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // $email = $_POST['Email'];
    $email = $_POST['Email'];
    // Get the user's email and password from the database based on the provided ID

    $stmt = $conn->prepare('SELECT password FROM users WHERE email = ?');
    $stmt->bind_param('i', $email);
    $stmt->execute();
    $stmt->bind_result($password);
    $stmt->fetch();
    $stmt->close();

   $mail->isSMTP();                                            //Send using SMTP
   $mail->Host       = 'localhost';                     //Set the SMTP server to send through
   $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
   $mail->Username   = '';                     //SMTP username
   $mail->Password   = '';                               //SMTP password
      // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 25;         
    // Set the sender's email address
    $mail->setFrom('mucuniversity2@gmail.com');

    // Check if the email was found in the database
    if ($email) {
        // Set the recipient's email address
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Forgetten Password';
        $mail->Body = 'Your password is: ' . $password;

        // Send the email
        $mail->send();
        echo "Your Correct Password is  Sent to Your University Email Successfully";
    } else {
        echo "Email not found in the database for the provided ID.";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
