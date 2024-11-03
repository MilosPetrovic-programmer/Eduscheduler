<?php
session_start(); // Dodajte ovu liniju na početku

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
include '../functions/db.php';

if (isset($_SESSION['name'])) {
    $professor_name = $_SESSION['name']; 

    $query = "SELECT * FROM busy_classrooms WHERE professor = '$professor_name'";
    $result = query($query);

    confirm($result);

    $appointments = fetch_all($result);

    echo json_encode($appointments);
} else {
    echo json_encode(["success" => false, "message" => "Niste ulogovani."]);
}
?>
