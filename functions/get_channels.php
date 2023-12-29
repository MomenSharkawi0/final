<?php
// Establish a database connection (replace with your database credentials)
$mysqli = new mysqli("92.205.147.175", "momen", "MoMeN011**", "sharkawi_muc");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve messages from the database based on the specified category
$stmt = $mysqli->prepare("SELECT * FROM channels");
$stmt->execute();
$result = $stmt->get_result();

$channels = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each message to the array
        $channels[] = array(
            'name' => $row['name'],
            'id' => $row['id'],
            'is_private' => $row['is_private'],
            'participants'  => json_decode($row['participants'])
        );
    }
}

// Close the database connection
$stmt->close();
$mysqli->close();

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($channels);
