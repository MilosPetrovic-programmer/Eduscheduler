<?php

include '../functions/db.php';

header('Content-Type: application/json');

$query = "SELECT * FROM busy_classrooms";
$result = query($query);

confirm($result);

$busy_classrooms = fetch_all($result);

echo json_encode($busy_classrooms);
?>
