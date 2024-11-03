<?php
include "../functions/init.php";

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$classroom = $data['classroom'];
$features = $data['features'];
$floor = $data['floor'];

$query = "UPDATE classrooms SET classroom = ?, features = ?, floor = ? WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ssii", $classroom, $features, $floor, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Učionica uspešno izmenjena"]);
} else {
    echo json_encode(["success" => false, "message" => "Greška pri izmeni učionice"]);
}

$stmt->close();
$con->close();
?>
