<?php// Structure
// 1. Only accept POST requests

// 2. Read the form values

// 3. Validate required fields

// 4. Stop spam

// 5. Create email

// 6. Send email with PHPMailer

// 7. Redirect user
?>

<?php

// Only allow POST requests
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

// Temporary output for testing
echo "<h2>Form Submitted Successfully!</h2>";

echo "<strong>Name:</strong> $name <br>";
echo "<strong>Email:</strong> $email <br>";
echo "<strong>Company:</strong> $company <br>";
echo "<strong>Phone:</strong> $phone <br>";
echo "<strong>Subject:</strong> $subject <br>";
echo "<strong>Message:</strong><br>";
echo nl2br(htmlspecialchars($message));
//$mail = new PHPMailer(true);
// ...
//$mail->send();

?>