<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'localhost'; 
    $mail->Port = 1025;
    $mail->SMTPAuth = false;
    
    $mail->setFrom('no-reply@example.com', 'Test Mail');
    $mail->addAddress('nguyenthethuy.qnam@gmail.com');
    
    $mail->Subject = 'MailHog Test';
    $mail->Body = 'This is a test email sent via MailHog.';
    
    if ($mail->send()) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email: " . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "Mailer Error: " . $e->getMessage();
}
?>
