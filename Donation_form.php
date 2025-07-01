<?php
// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs from $_POST superglobal
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');
    $selectedAmount = sanitize_input($_POST['selectedAmount'] ?? '0');

    // Basic validation
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = urlencode('Invalid input. Please fill in all required fields and provide a valid email.');
        header("Location: index.html?status=error&message=$error_message");
        exit;
    }

    // Recipient email for the donation notification
    $to_email = 'sudev.bazoo@gmail.com'; // !!! IMPORTANT: Change this to your actual email address !!!
    $subject = "New Donation Form Submission from " . $name;

    // Email content for the recipient
    $email_body = "You have received a new donation form submission.\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Selected Donation Amount: ₹" . $selectedAmount . "\n";
    $email_body .= "Message:\n" . $message . "\n";

    // Headers for the recipient email
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send email to the recipient
    $mail_sent_to_you = mail($to_email, $subject, $email_body, $headers);

    // Prepare confirmation email for the user
    $user_subject = "Thank You for Your Donation!";
    $user_email_body = "Dear " . $name . ",\n\n";
    $user_email_body .= "Thank you for your generous donation of ₹" . $selectedAmount . "!\n";
    $user_email_body .= "We appreciate your support.\n\n";
    $user_email_body .= "Best regards,\nYour Organization";

    // Headers for the user's confirmation email
    $user_headers = "From: no-reply@yourdomain.com\r\n"; // !!! IMPORTANT: Change this to a valid email from your domain !!!
    $user_headers .= "Reply-To: " . $to_email . "\r\n"; // User can reply to your main email
    $user_headers .= "MIME-Version: 1.0\r\n";
    $user_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send confirmation email to the user
    $mail_sent_to_user = mail($email, $user_subject, $user_email_body, $user_headers);

    if ($mail_sent_to_you && $mail_sent_to_user) {
        $success_message = urlencode('Thank you for your donation! A confirmation email has been sent.');
        header("Location: index.html?status=success&message=$success_message");
        exit;
    } else {
        $error_message = urlencode('There was an error sending the email. Please try again later.');
        error_log("Failed to send email. To: $to_email, User: $email");
        header("Location: index.html?status=error&message=$error_message");
        exit;
    }

} else {
    // If accessed directly without POST data
    $error_message = urlencode('Invalid request method.');
    header("Location: index.html?status=error&message=$error_message");
    exit;
}
?>
