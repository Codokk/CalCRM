<?php
$to = "Codokk101@gmail.com";
$subject = "This is a test";
$message = "<strong>OMEGALUL</strong> U R A <i>LOOZER</i>";
// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Additional headers
$headers[] = 'From: birthday@example.com';
$headers[] = 'Cc: birthdayarchive@example.com';
$headers[] = 'Bcc: birthdaycheck@example.com';

mail($to, $subject, $message, implode("\r\n", $headers));
?>