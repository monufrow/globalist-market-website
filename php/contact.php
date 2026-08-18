<?php

require __DIR__ . '/config.php';
require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.html');
    exit;
}

// Read submitted form values.
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields.
if (
    $name === '' ||
    $email === '' ||
    $subject === '' ||
    $message === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: ../contact.html?error=1');
    exit;
}

// Limit field lengths.
if (
    strlen($name) > 100 ||
    strlen($email) > 254 ||
    strlen($subject) > 200 ||
    strlen($message) > 5000
) {
    header('Location: ../contact.html?error=1');
    exit;
}

// Escape user input before placing it into the HTML email.
$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port = SMTP_PORT;

    // Sender should be your authenticated domain account.
    $mail->setFrom(SMTP_USER, 'Globalist Market Website');

    // Send the message to the Globalist Market inbox.
    $mail->addAddress('globalistmarket@gmail.com');

    // Allow replies to go directly to the person who submitted the form.
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $safeSubject;

    $mail->Body = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> {$safeName}</p>
        <p><strong>Email:</strong> {$safeEmail}</p>
        <p><strong>Subject:</strong> {$safeSubject}</p>
        <p><strong>Message:</strong></p>
        <p>{$safeMessage}</p>
    ";

    $mail->AltBody =
        "New Contact Form Submission\n\n" .
        "Name: {$name}\n" .
        "Email: {$email}\n" .
        "Subject: {$subject}\n\n" .
        "Message:\n{$message}";

    $mail->send();

    header('Location: ../contact.html?success=1');
    exit;
} catch (Exception $e) {
    // Do not expose SMTP or PHPMailer errors to the visitor.
    error_log('Contact form email failed: ' . $e->getMessage());

    header('Location: ../contact.html?error=1');
    exit;
}

