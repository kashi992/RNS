<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/Exception.php';
require __DIR__ . '/PHPMailer.php';
require __DIR__ . '/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Helper to safely fetch fields
    function field($key) {
        return trim($_POST[$key] ?? '');
    }

    $name    = field('name');
    $email   = filter_var(field('email'), FILTER_VALIDATE_EMAIL);
    $phone   = field('phone');
    $date    = field('date');
    $time    = field('time');
    $service = field('service');
    $message = field('message');

    // Basic validation
    if (!$name || !$email || !$phone || !$date || !$time || !$service || !$message) {
        http_response_code(400);
        echo "Please complete all required fields.";
        exit;
    }

    // Prevent header injection in text fields
    foreach ([$name, $phone, $service] as $v) {
        if (preg_match('/[\r\n]/', $v)) {
            http_response_code(400);
            echo "Invalid input.";
            exit;
        }
    }

    // Build HTML email
    $subject = "New Booking Request — {$service}";
    $now     = date('Y-m-d H:i:s');

    $htmlBody = <<<HTML
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>{$subject}</title>
<style>
  body { font-family: Arial, Helvetica, sans-serif; color:#222; }
  .wrap { max-width:600px; margin:0 auto; padding:16px; }
  h2 { margin:0 0 12px; font-size:20px; }
  p  { margin:0 0 12px; }
  table { border-collapse: collapse; width:100%; }
  th, td { text-align:left; padding:10px; border-bottom:1px solid #eee; vertical-align:top; }
  th { width:180px; background:#fafafa; font-weight:600; }
  .footnote { font-size:12px; color:#666; margin-top:10px; }
</style>
</head>
<body>
  <div class="wrap">
    <h2>New Contact Form Submission</h2>
    <p>You’ve received a new booking enquiry from your website.</p>
    <table role="presentation" cellpadding="0" cellspacing="0">
      <tr><th>Name</th><td>{$name}</td></tr>
      <tr><th>Email</th><td>{$email}</td></tr>
      <tr><th>Phone</th><td>{$phone}</td></tr>
      <tr><th>Preferred Date</th><td>{$date}</td></tr>
      <tr><th>Preferred Time</th><td>{$time}</td></tr>
      <tr><th>Requested Service</th><td>{$service}</td></tr>
      <tr><th>Car Details / Message</th><td><pre style="white-space:pre-wrap;margin:0;">{$message}</pre></td></tr>
    </table>
    <p class="footnote">Submitted at {$now}</p>
  </div>
</body>
</html>
HTML;

    $plainBody =
"New Contact Form Submission

Name: {$name}
Email: {$email}
Phone: {$phone}
Preferred Date: {$date}
Preferred Time: {$time}
Requested Service: {$service}

Car Details / Message:
{$message}

Submitted at {$now}
";

    $mail = new PHPMailer(true);

    try {
        // SMTP (Gmail). If you prefer cPanel mail, swap these for mail.yourdomain.com etc.
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sohaibanwar5876@gmail.com';
        $mail->Password   = 'imbs cbgc iilk jkln'; // Use a Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or ENCRYPTION_SMTPS with port 465
        $mail->Port       = 587;

        // From/To
        $mail->setFrom('admin@rnstyreandautoservice.com', 'Website');
        $mail->addAddress('admin@rnstyreandautoservice.com'); // recipient
        $mail->addReplyTo($email, $name);               // reply goes to the customer

        // Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainBody;

        $mail->send();
        header('Location: /thank_you.html');
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(405);
    echo "Sorry, this form cannot be submitted directly.";
}
