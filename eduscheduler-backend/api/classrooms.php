<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include '../functions/db.php';

header('Content-Type: application/json');

$query = "SELECT * FROM classrooms";
$result = query($query);

confirm($result);

$classrooms = fetch_all($result);

echo json_encode($classrooms);
?>
