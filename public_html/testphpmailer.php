<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require '../phpmailer/vendor/autoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 2; //0 to hide LOGS and 2 to show logs
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'hello@crushit.fit';
    $mail->Password = 'sahiyogita1A';
    $mail->setFrom('hello@crushit.fit', 'CrushIt');
    $mail->addReplyTo('hello@crushit.fit', 'CrushIt');
    $mail->addAddress('abhijeet.sweden@gmail.com', 'Abhijeet Mandal');
    $mail->Subject = 'PHPMailer SMTP message';
    //echo "realpath: ".  realpath(__DIR__)."<br/>";
    $mail->msgHTML(file_get_contents('message.html'), __DIR__);
    $mail->AltBody = 'This is a plain text message body';
    //$mail->addAttachment('test.txt');
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }
?>
    </body>
</html>
