<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Primi JSON payload i dekodiraj ga
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? null;

    // Loguj primljeni podatak
    error_log("Primljen email: " . $email);

    // Provera da li je email validan
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mail = new PHPMailer(true);
        // Generiši jedinstveni token
        $token = bin2hex(random_bytes(32));

        // Čuvanje tokena u bazi zajedno sa email adresom
        $stmt = $con->prepare("UPDATE professors SET reset_token = ?, token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$token, $email]);

        if ($stmt->affected_rows > 0) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'mimiizoki@gmail.com';
                $mail->Password = 'ptkl uuqm ddgp jpnf'; // promeni ovo
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('tvojemail@gmail.com', 'EduScheduler Admin');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Postavljanje Sifre';
                $resetLink = "http://localhost:3000/reset-password?token=" . $token;
                $mail->Body = 'Klikni na ovaj <a href="' . $resetLink . '">link</a> kako bi postavio novu šifru.';

                $mail->send();
                echo json_encode(['success' => true, 'message' => 'Email je uspešno poslat.']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Došlo je do greške pri slanju emaila: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Email nije pronađen u bazi.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Nevažeća email adresa.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nevažeći zahtev.']);
}
?>