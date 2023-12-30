<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
                require_once "../../vendor/autoload.php"; //PHPMailer Object  "F:\xampp\phpMyAdmin\vendor\autoload.php"
                $mail = new PHPMailer(true);
//C:\xampp\htdocs
                try {
                    //Server settings
                    echo "<p>";
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'mudithasamarasinghe08@gmail.com';                     //SMTP username
                    $mail->Password   = 'dscklkgchdvejrqr';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('mudithasamarasinghe08@gmail.com', 'MEDIX');
                    $mail->addAddress('mudithasamarasinghe08@gmail.com');     //Add a recipient

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Medix Expiry Date Notification';
                    $mail->Body    = 'Hello,<br><br>
        Your booking has been confirmed by <b>MEDIX</b>.<br><br>
        
        Please Find the below booking information, <br>
        <b>Booking Id<br>
        <br><br><i>Please use the above booking id for any queries.</i><br><br><b>Regards,</b><br>CAREKLEEN ';
                    $mail->AltBody = 'Hello,
        Your booking has been confirmed by CAREKLEEN.
        
        Please Find the below booking information,
        Booking Id -333333
        Please use the above booking id for any queries. 
        Regards, CAREKLEEN';

                    $mail->send();
                    echo 'Message has been sent</p>';

                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

?>
