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
        header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM busy_classrooms WHERE id = ?";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Učionica uspešno obrisana']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Greška pri brisanju učionice']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Greška pri pripremi upita']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Nedostaje ID učionice']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Neispravan metod zahteva']);
}

//$mysqli->close();
?>
