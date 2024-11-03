<?php
header('Content-Type: application/json');
include "../functions/init.php";

// Dozvoli pristup sa svih origin-a
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // keširanje na 1 dan
}

// Access-Control zahtevi se dobijaju tokom OPTIONS zahteva
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

$data = json_decode(file_get_contents('php://input'), true);

file_put_contents('php://stderr', print_r($data, TRUE)); // Logovanje ulaznih podataka

if (!$data) {
    echo json_encode(["success" => false, "message" => "Nema primljenih podataka"]);
    exit;
}

$name = isset($data['name']) ? $data['name'] : null;
$surname = isset($data['surname']) ? $data['surname'] : null;
$email = isset($data['email']) ? $data['email'] : null;
$password = isset($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : null;

if ($name && $surname && $email && $password) {
    $query = "INSERT INTO professors (name, surname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssss", $name, $surname, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profesor uspešno dodat"]);
    } else {
        echo json_encode(["success" => false, "message" => "Greška prilikom dodavanja profesora"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Nevažeći unos"]);
}

$con->close();
?>
