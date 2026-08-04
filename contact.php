<?php
require 'config.php';

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Structure
// 1. Only accept POST requests

// 2. Read the form values

// 3. Validate required fields

// 4. Stop spam

// 5. Create email

// 6. Send email with PHPMailer

// 7. Redirect user

// Only allow POST requests
$mail = new PHPMailer(true);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request.");
}

// Get form data
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$company = trim($_POST["company"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

// Honeypot spam protection
if (!empty($_POST["website"])) {
    die("Spam detected.");
}

// Required fields
if (empty($name) || empty($email) || empty($message)) {
    die("Please complete all required fields.");
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

//real forwarding
try {
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port = SMTP_PORT;
    $mail->Timeout = 20;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('info@globalistmarket.com', 'Globalist Market Website'); //email to be sent from

    $mail->addAddress('monufrow@trinity.edu'); //email to send to

    $mail->addReplyTo($email, $name); //gives the proper email/name for replying to the email
        
    $mail->Subject = "Website Contact: " . ($subject ?: "General Inquiry"); //email subject

    $mail->isHTML(true);
    
    $message = htmlspecialchars($message);
    $name = htmlspecialchars($name);
    $company = htmlspecialchars($company);
    $phone = htmlspecialchars($phone);
    $subject = htmlspecialchars($subject);
    $email = htmlspecialchars($email);

    $mail->Body = "
        <h2>New Website Inquiry</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Company:</strong> $company</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Subject:</strong> $subject</p>
        <hr>
        <p>$message</p>";

    $mail->AltBody = "Name: $name\nEmail: $email\n\n$message";

    $mail->send();

    header("Location: contact.html?success=1");
    exit();
}
catch (Exception $e) {
    header("Location: contact.html?error=1");
    exit();
}

// Redirect 

//end of file