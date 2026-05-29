<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$name    = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
$message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));

if (!$name || !$email || !$phone || !$message) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ivaiagent05@gmail.com';
    $mail->Password   = 'xvsoulhaexcmsfrp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('ivaiagent05@gmail.com', 'Madhav Engineering Works');
    $mail->addAddress('ivaiagent05@gmail.com', 'Madhav Engineering Works');
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Inquiry from ' . $name;
    $mail->Body    = "
        <html><body style='font-family: Arial, sans-serif; color: #333;'>
            <h2 style='color: #1a237e;'>New Inquiry - Madhav Engineering Works</h2>
            <table style='width:100%; border-collapse: collapse;'>
                <tr><td style='padding:8px; border:1px solid #ddd; background:#f5f5f5;'><strong>Name</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$name}</td></tr>
                <tr><td style='padding:8px; border:1px solid #ddd; background:#f5f5f5;'><strong>Email</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$email}</td></tr>
                <tr><td style='padding:8px; border:1px solid #ddd; background:#f5f5f5;'><strong>Phone</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$phone}</td></tr>
                <tr><td style='padding:8px; border:1px solid #ddd; background:#f5f5f5;'><strong>Message</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$message}</td></tr>
            </table>
        </body></html>
    ";
    $mail->AltBody = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nMessage: {$message}";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Inquiry sent successfully!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send. Error: ' . $mail->ErrorInfo]);
}
