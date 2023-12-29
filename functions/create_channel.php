<?php

// Assuming you have a database connection established, replace the placeholders accordingly

// Get the raw POST data
$data = file_get_contents("php://input");

// Check if the data is not empty
if (!empty($data)) {
  // Decode the JSON data
  $Data = json_decode($data, true);

  $participantsData = $Data["partcipants"];
  // Check if the decoding was successful
  if ($participantsData !== null) {
    // Call the function with the decoded data
    $result = createParticipantRow($participantsData);

    // Return the result as JSON
    echo json_encode($result);
  } else {
    // Handle JSON decoding error
    echo json_encode(["message" => "Error decoding JSON data."]);
  }
} else {
  // Handle empty POST data
  echo json_encode(["message" => "No data received."]);
}

function createParticipantRow($participantsData)
{
  // Database connection details
  $conn = new mysqli("64.226.68.255", "phpmyadmin", "MoMeN011**", "sharkawi_muc");


  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Extract participant IDs
  $participantIds = array_column($participantsData, 'id');

  // Sort participant IDs to make the order irrelevant
  sort($participantIds);
  // Convert sorted participant IDs to JSON
  $participantsJson = json_encode($participantIds);

  // Check if a row with the same participants already exists
  $stmtCheck = $conn->prepare("SELECT id FROM channels WHERE participants = ?");
  $stmtCheck->bind_param("s", $participantsJson);
  $stmtCheck->execute();
  $stmtCheck->store_result();

  // If a row with the same participants exists, return the message
  if ($stmtCheck->num_rows > 0) {
    $stmtCheck->bind_result($existingId);
    $stmtCheck->fetch();
    $stmtCheck->close();
    $conn->close();
    return ["message" => "Already exists", "channel_id" => $existingId];
  }

  // Generate a random ID of 24 characters
  $id = generateRandomId();

  // Join participant names with underscore
  $name = implode('_', array_column($participantsData, 'name'));

  // Set is_private to true
  $is_private = true;

  // Prepare SQL statement
  $stmt = $conn->prepare("INSERT INTO channels (id, name, is_private, participants) VALUES (?, ?, ?, ?)");

  // Bind parameters
  $stmt->bind_param("ssss", $id, $name, $is_private, $participantsJson);

  // Execute the statement
  $stmt->execute();

  // Close statement and connection
  $stmt->close();
  $stmtCheck->close();
  $conn->close();

  return ["message" => "Channel created"];
}

// Function to generate a random ID of 24 characters
function generateRandomId()
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomId = '';

  for ($i = 0; $i < 24; $i++) {
    $randomId .= $characters[rand(0, strlen($characters) - 1)];
  }

  return $randomId;
}
