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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $data = json_decode(file_get_contents("php://input"), true);
  $id = $data["id"];
  $name = $data["name"];
  $surname = $data["surname"];
  $email = $data["email"];
  $password = isset($data["password"]) ? password_hash($data["password"], PASSWORD_DEFAULT) : null;

  if (empty($id) || empty($name) || empty($surname) || empty($email)) {
    echo json_encode(["success" => false, "message" => "Sva polja osim šifre su obavezna."]);
    exit;
  }

  $stmt = $con->prepare("UPDATE professors SET name = ?, surname = ?, email = ? WHERE id = ?");
  $stmt->bind_param("sssi", $name, $surname, $email, $id);
  $stmt->execute();

  if ($password) {
    $stmt = $con->prepare("UPDATE professors SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $id);
    $stmt->execute();
  }

  if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Profesor uspešno ažuriran."]);
  } else {
    echo json_encode(["success" => false, "message" => "Došlo je do greške pri ažuriranju profesora."]);
  }

  $stmt->close();
  $con->close();
} else {
  echo json_encode(["success" => false, "message" => "Neispravan zahtev."]);
}
?>
