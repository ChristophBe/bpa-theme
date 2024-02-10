<?php

require_once ABSPATH . WPINC . '/class-phpmailer.php';




function newsletter_send_mail(NewsletterSubscription $subscription, $subject, $msg, $msg_alt ="")
{

    $header = '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#1e1c32" />
    <style>
        body{background:#e3e7ea;font-family:sans-serif;margin:0}h1,h2,h3,h4{margin:0 0 20px;text-transform:uppercase;color:#1e1c32}.content{width:700px;padding:50px 0;margin:auto}.content .logo{padding-bottom:50px;text-align:right}.content .logo img{width:300px}@media (max-width:800px){.content{width:90%}.content .logo{padding-bottom:30px;text-align:right}.content .logo img{width:30%}}@media (max-width:600px){.content .logo{padding-bottom:20px;text-align:right}.content .logo img{width:40%}}.concerts{background:#1e1c32;color:#fff}.concerts h1,.concerts h2,.concerts h3{color:#ffe125}.concerts .concerts h3{margin:5px 0 10px}p{text-align:justify}hr{margin:30px 0;border:none;border-bottom:1px solid}.btn{display:inline-block;color:#000;padding:5px 15px;border:1px solid;text-decoration:none}.btn:hover{color:#333}.concerts .btn{color:#fff}.concerts .btn:hover{color:#e3e7ea}.concert-item{margin:0 0 30px}.concert-item:last-child{margin:0}
    </style>
</head>
<body>
<div class="content">
    <div class="logo">
        <img src="cid:logo" style="max-width: 40%" alt="Logo der Bläserphilharmonie Aachen e.V.">
    </div>';

    $footer = '</div>
</body>
</html>';

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    date_default_timezone_set('Etc/UTC');

    try {
        //Server settings // Enable verbose debug output
        $mail->isSMTP();                                                // Send using SMTP
        $mail->Host = 'send.one.com';                                   // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                         // Enable SMTP authentication
        $mail->Username = 'newsletter@blaeserphilharmonie-aachen.de';   // SMTP username
        $mail->Password = 'a0d6cb8b09e6d564717883931b8b73a3a91117ac';   // SMTP password
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;                                              // TCP port to connect to

        //Recipients
        $mail->setFrom('newsletter@blaeserphilharmonie-aachen.de', 'Newsletter | Bläserphilharmonie Aachen e.V.');
        // Add a recipient
        $mail->addAddress($subscription->getEmail());// Name is optional
        $mail->addReplyTo('vortand@blaeserphilhamonie-aachen.de', 'Vorstand | Bläserphilharmonie Aachen e.V.');
        $mail->addEmbeddedImage(get_template_directory() ."/assets/img/BPA_Logo_SW.svg","logo");


        // Content
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $header. $msg . $footer;
        $mail->AltBody = $msg_alt;

        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}
