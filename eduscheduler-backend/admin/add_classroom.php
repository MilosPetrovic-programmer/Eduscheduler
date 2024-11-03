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

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['classroom']) && isset($data['features']) && isset($data['floor'])) {
    $classroom = $data['classroom'];
    $features = $data['features'];
    $floor = $data['floor'];

    $stmt = $con->prepare("INSERT INTO classrooms (classroom, features, floor) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $classroom, $features, $floor);

    if ($stmt->execute()) {
        $response = array("success" => true, "message" => "Učionica uspešno dodata.");
    } else {
        $response = array("success" => false, "message" => "Došlo je do greške pri dodavanju učionice.");
    }

    $stmt->close();
    $con->close();
} else {
    $response = array("success" => false, "message" => "Podaci o učionici nisu potpuni.");
}

echo json_encode($response);
?>
