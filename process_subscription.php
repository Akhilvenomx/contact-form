<?php

$receiving_email_address = 'bispage.research@gmail.com'; // Replace with your actual email address

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? htmlspecialchars(strip_tags(trim($_POST['email']))) : '';

    // Basic validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h2 style='color: red; text-align: center;'>Invalid email address. Please enter a valid email.</h2>";
        exit();
    }
    $subject = "New Newsletter Subscription from " . $email;
    $email_body = "A new user has subscribed to your newsletter:\n\n";
    $email_body .= "Email: " . $email . "\n";

    $headers = "From: enquiry@bispage.com\r\n"; 
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

 
    if (mail($receiving_email_address, $subject, $email_body, $headers)) {
        echo "<h2 style='color: green; text-align: center;'>Thank you for subscribing! Your email has been received successfully.</h2>";
        
    } else {
        echo "<h2 style='color: red; text-align: center;'>Oops! Something went wrong and we could not process your subscription at this time. Please try again later.</h2>";
        
    }

} else {
   
    echo "<h2 style='color: orange; text-align: center;'>Access Denied. This page can only be accessed via form submission.</h2>";
    
}
?>
