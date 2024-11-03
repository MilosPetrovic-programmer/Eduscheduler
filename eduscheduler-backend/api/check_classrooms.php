<?php
// check_classrooms.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eduschedulerdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date('Y-m-d'); // Trenutni datum
$currentTime = date('H:i:s'); // Trenutno vreme

// Prvo postavi sve učionice kao nezauzete
$reset_sql = "UPDATE classrooms SET occupied = 0";
$conn->query($reset_sql);


$sql = "SELECT amphitheater, startTime, endTime, professor 
        FROM busy_classrooms 
        WHERE calendar = '$currentDate' 
        AND startTime <= '$currentTime' 
        AND endTime > '$currentTime'";
$result = $conn->query($sql);

$busyClassrooms = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $busyClassrooms[] = [
            'amphitheater' => $row['amphitheater'],
            'startTime' => $row['startTime'],
            'endTime' => $row['endTime'],
            'professor' => $row['professor']
        ];
    }
}

// Ažuriraj occupied status za zauzete učionice
foreach ($busyClassrooms as $classroom) {
    $update_sql = "UPDATE classrooms SET occupied = 1 WHERE classroom = '" . $classroom['amphitheater'] . "'";
    $conn->query($update_sql);
}

// Obrisi unose iz busy_classrooms gde je endTime prošao ili datum prošao
$delete_sql = "DELETE FROM busy_classrooms 
               WHERE (calendar = '$currentDate' AND endTime <= '$currentTime') 
               OR (calendar < '$currentDate')";
$conn->query($delete_sql);

$conn->close();

header('Content-Type: application/json');
echo json_encode($busyClassrooms);
?>
