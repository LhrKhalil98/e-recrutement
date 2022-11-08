<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="app_mailer")
     */
    public function index(): Response
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();      
            $mail->Host       = 'localhost';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
            $mail->SMTPAutoTLS = false; 
            $mail->SMTPSecure = false; 
            $mail->Username   = 'tunisie@weconseil.com';                     //SMTP username
            $mail->Password   = 'Hamida20222';                               //SMTP password
            $mail->Port       = 25;    
         
                                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                                           
            //Recipients
            $mail->setFrom('tunisie@weconseil.com', 'WeConseil');
            $mail->addAddress('lhrkhalil98@gmail.com', 'Khalil');     //Add a recipient
          
     
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
