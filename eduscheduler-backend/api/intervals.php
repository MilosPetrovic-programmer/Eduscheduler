<?php
// your-backend-endpoint.php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Retrieve JSON POST data
$data = json_decode(file_get_contents("php://input"));

// Check if data is set and valid
if (!isset($data->date) || !isset($data->roomName)) {
    http_response_code(400);
    echo json_encode(array("error" => "Invalid data sent"));
    exit;
}

// Extract date and roomName from JSON data
$date = $data->date;
$roomName = $data->roomName;

// Database credentials and connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduschedulerdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL query
$sql = "SELECT startTime, endTime, professor FROM busy_classrooms 
        WHERE calendar = ? AND amphitheater = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $date, $roomName);
$stmt->execute();
$result = $stmt->get_result();

// Prepare JSON response array
$response = array();
$busyIntervals = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each row to the busyIntervals array
        $busyIntervals[] = array(
            'startTime' => $row['startTime'],
            'endTime' => $row['endTime'],
            'professor' => $row['professor']
        );
    }
}

$workingHoursStart = 8; 
$workingHoursEnd = 21;   

$freeIntervals = array();
$lastEndTime = $workingHoursStart;

foreach ($busyIntervals as $interval) {
    $startTime = (int)$interval['startTime'];
    $endTime = (int)$interval['endTime'];
    
    if ($lastEndTime < $startTime) {
        $freeIntervals[] = array(
            'startTime' => $lastEndTime,
            'endTime' => $startTime
        );
    }
    $lastEndTime = max($lastEndTime, $endTime);
}

if ($lastEndTime < $workingHoursEnd) {
    $freeIntervals[] = array(
        'startTime' => $lastEndTime,
        'endTime' => $workingHoursEnd
    );
}

// Combine busy and free intervals into response
$response['busyIntervals'] = $busyIntervals;
$response['freeIntervals'] = $freeIntervals;

// Close statement and database connection
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
