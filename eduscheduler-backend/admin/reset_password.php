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
        header("Access-Control-Allow-Methods: GET, POST, UPDATE, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $token = $input['token'] ?? null;
    $newPassword = $input['newPassword'] ?? null;

    if ($token && $newPassword) {
        // Prvo pripremi SELECT upit da proveriš token
        $stmt = $con->prepare("SELECT email FROM professors WHERE reset_token = ? AND token_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            // Zatvori prethodni statement pre novog upita
            $stmt->close();

            // Pripremi UPDATE upit za resetovanje šifre
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $con->prepare("UPDATE professors SET password = ?, reset_token = NULL, token_expires = NULL WHERE reset_token = ?");
            $stmt->execute([$hashedPassword, $token]);

            echo json_encode(['success' => true, 'message' => 'Šifra uspešno promenjena.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nevažeći ili istekao token.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Nedostaje token ili nova šifra.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nevažeći zahtev.']);
}
?>
