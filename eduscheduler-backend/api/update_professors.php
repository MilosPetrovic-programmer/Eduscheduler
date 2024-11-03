<?php
header("Content-Type: application/json");
include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$name = $data->name;
$surname = $data->surname;
$email = $data->email;
$password = $data->password;

$sql = "UPDATE professors SET name=?, surname=?, email=?, password=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $surname, $email, $password, $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Professor updated successfully"]);
} else {
    echo json_encode(["message" => "Error updating professor"]);
}

$stmt->close();
$conn->close();
?>
