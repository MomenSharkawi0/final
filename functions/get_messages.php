<?php
// Establish a database connection (replace with your database credentials)
$mysqli = new mysqli("92.205.147.175", "momen", "MoMeN011**", "sharkawi_muc");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve messages from the database based on the specified category
$channel_id = isset($_GET['channel_id']) ? $_GET['channel_id'] : 'akjshdksajdlksad';


$stmt = $mysqli->prepare("SELECT * FROM messages WHERE channel_id = ?  ");
$stmt->bind_param("s", $channel_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each message to the array
        $messages[] = array(
            'sender' => $row['sender'],
            'channel_id' => $row['channel_id'],
            'message' => $row['message'],
            'userid' => $row['userid'],
        );
    }
}

// Close the database connection
$stmt->close();
$mysqli->close();

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);
