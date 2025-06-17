<?php
/**
 * Contact Form Handler
 *
 * Processes contact form submissions and sends emails
 * 
 * @package VincentRestaurant
 */

// Include configuration file
require_once '../config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['contact_name']) && 
    isset($_POST['contact_email']) && 
    isset($_POST['contact_subject']) && 
    isset($_POST['contact_message'])
) {
    // Sanitize input data
    $contact_name = test_input($_POST['contact_name']);
    $contact_email = test_input($_POST['contact_email']);
    $contact_subject = test_input($_POST['contact_subject']);
    $contact_message = test_input($_POST['contact_message']);
    
    // Build message
    $email_message = "Tên: " . $contact_name . "\n";
    $email_message .= "Email: " . $contact_email . "\n\n";
    $email_message .= "Nội dung:\n" . $contact_message;
    
    // Set headers
    $headers = "From: " . $contact_email . "\r\n";
    
    // Send mail
    $mail_sent = mail(
        CONTACT_EMAIL, 
        EMAIL_SUBJECT_PREFIX . $contact_subject, 
        $email_message,
        $headers
    );

    // Provide response
    if ($mail_sent) {
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Tin nhắn đã được gửi thành công!
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Đã xảy ra sự cố khi gửi tin nhắn, vui lòng thử lại!
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
}
?>