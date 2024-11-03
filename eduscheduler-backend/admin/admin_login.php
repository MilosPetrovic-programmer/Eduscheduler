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

// Podaci za povezivanje na bazu podataka
$host = 'localhost';
$dbname = 'eduschedulerdb';
$username = 'root';
$password = '';

// Povezivanje na bazu podataka
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Došlo je do pogreške pri povezivanju na bazu: " . $e->getMessage()]);
    exit;
}

// Provera da li su podaci poslati POST metodom
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Uzimanje podataka iz POST zahteva
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->username) && isset($data->password)) {
        $username = htmlspecialchars($data->username);
        $password = htmlspecialchars($data->password);

        // Priprema SQL upita
        $stmt = $pdo->prepare("SELECT * FROM admin_panel WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Provera da li je korisnik pronađen i da li se lozinka poklapa
        if ($user && password_verify($password, $user['password'])) {
            // Prijava uspešna
            $_SESSION['username'] = $user['username']; // Dodavanje korisničkog imena u sesiju
            echo json_encode(["success" => true, "message" => "Prijava uspešna.", "username" => $user['username']]);
        } else {
            // Neuspešna prijava
            echo json_encode(["success" => false, "message" => "Neispravno korisničko ime ili lozinka."]);
        }
    } else {
        // Nedostaju podaci
        echo json_encode(["success" => false, "message" => "Nedostaju podaci za prijavu."]);
    }
} else {
    // Neispravan metod
    echo json_encode(["success" => false, "message" => "Neispravan zahtev."]);
}
?>
