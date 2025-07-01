<?php


// this code is developed and liciensed by Bispage.com

session_start(); 
header('Content-Type: application/json'); 

$response = array('success' => false, 'message' => 'An unknown error occurred.');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = isset($_POST['name']) ? htmlspecialchars(strip_tags(trim($_POST['name']))) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(strip_tags(trim($_POST['email']))) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(strip_tags(trim($_POST['phone']))) : ''; 
    $subject = isset($_POST['subject']) ? htmlspecialchars(strip_tags(trim($_POST['subject']))) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(strip_tags(trim($_POST['message']))) : '';

    // Basic validation (without CAPTCHA)
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        $response['message'] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
    } else {
        $to = 'bispage.research@gmail.com';
        
        $email_subject = "Contact From: " . $name;
        
        $email_body = "You have received a new message from your website contact form.\n\n" .
                      "Here are the details:\n\n" .
                      "Name: " . $name . "\n\n" .
                      "Email: " . $email . "\n\n" .
                      "Phone: " . $phone . "\n\n" .
                      "Subject: " . $subject . "\n\n" .
                      "Message:\n" . $message;

        // Set email headers
        $headers = "From: bispage@bispage.com\r\n"; 
        $headers .= "Reply-To: " . $email . "\r\n"; 
        $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

        // Attempt to send the email
        if (mail($to, $email_subject, $email_body, $headers)) {
            $response['success'] = true;
            $response['message'] = 'Your message has been sent successfully!';
        } else {
            $response['message'] = 'Failed to send email. Please try again later.';
            
        }

        
        $submission_data = "Date: " . date('Y-m-d H:i:s') . "\n" .
                           "Name: " . $name . "\n" .
                           "Email: " . $email . "\n" .
                           "Phone: " . $phone . "\n" .
                           "Subject: " . $subject . "\n" .
                           "Message:\n" . $message . "\n" .
                           "------------------------------------\n\n";

        $file_path = 'submissions.txt'; 
        file_put_contents($file_path, $submission_data, FILE_APPEND | LOCK_EX); 
    }
} else {
    
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit(); 
?>
