<?php

include "../functions/init.php";


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

if (!isset($_SESSION['name'])) {
    error_log("Sesija nije pronađena ili korisnik nije ulogovan.");
    http_response_code(400);
    echo json_encode(['message' => 'Korisnik nije ulogovan.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dekodiraj JSON ulaz
    $data = json_decode(file_get_contents("php://input"), true);

    // Loguj primljene podatke za debugging
    error_log("Primljeni podaci: " . print_r($data, true));

    // Ekstrahuj podatke iz dekodiranog JSON-a
    $calendar = isset($data['date']) ? $data['date'] : '';
    $startTime = isset($data['startTime']) ? $data['startTime'] : '';
    $endTime = isset($data['endTime']) ? $data['endTime'] : '';
    $amphitheater = isset($data['amphitheater']) ? $data['amphitheater'] : '';
    $professor = $_SESSION['name'];

    // Loguj ekstrahovane podatke za debugging
    error_log("Ekstrahovani podaci - Kalendar: $calendar, Početno vreme: $startTime, Krajnje vreme: $endTime, Amfiteatar: $amphitheater, Profesor: $professor");

    // Provera da li su svi potrebni podaci prisutni
    if (empty($calendar) || empty($startTime) || empty($endTime) || empty($amphitheater)) {
        error_log("Nedostaju potrebni podaci. Kalendar: $calendar, Početno vreme: $startTime, Krajnje vreme: $endTime, Amfiteatar: $amphitheater");
        http_response_code(400);
        echo json_encode(['message' => 'Nedostaju potrebni podaci za rezervaciju.']);
        exit();
    }

    // Kreiraj novu PDO konekciju
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=eduschedulerdb", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO busy_classrooms (calendar, startTime, endTime, amphitheater, professor) 
                VALUES (:calendar, :startTime, :endTime, :amphitheater, :professor)";
        $stmt = $pdo->prepare($sql);

        // Loguj SQL upit i parametre za debugging
        error_log("SQL upit: $sql");
        error_log("SQL parametri: Kalendar: $calendar, Početno vreme: $startTime, Krajnje vreme: $endTime, Amfiteatar: $amphitheater, Profesor: $professor");

        $stmt->bindParam(':calendar', $calendar);
        $stmt->bindParam(':startTime', $startTime);
        $stmt->bindParam(':endTime', $endTime);
        $stmt->bindParam(':amphitheater', $amphitheater);
        $stmt->bindParam(':professor', $professor);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Rezervacija uspešna!'];
            echo json_encode($response);
        } else {
            $response = ['success' => false, 'message' => 'Greška prilikom rezervacije.'];
            echo json_encode($response);
        }
    } catch (PDOException $e) {
        error_log("PDO greška: " . $e->getMessage());
        $response = ['success' => false, 'message' => 'Greška prilikom povezivanja na bazu.'];
        echo json_encode($response);
    }

    $pdo = null;
} else {
    error_log("Podaci nisu poslani putem POST metode.");
    http_response_code(400);
    echo json_encode(['message' => 'Podaci nisu poslani putem POST metode.']);
}
?>
