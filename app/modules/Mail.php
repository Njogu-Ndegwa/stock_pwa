<?php
declare(strict_types=1);

namespace app;

use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

/**
 * The mail module, a means through which you can send a mail, only one static function to send it
 */
class Mail
{
    public static function sendEmail(array $senderOptions, String $recipient, String $subject, String $name, String $content)
    {
        $mail = new PHPMailer(true);

        try {

        //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = $senderOptions['email_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $senderOptions['email_username'];
            $mail->Password   = $senderOptions['email_password'];
            $mail->SMTPSecure = $senderOptions['smtp_secure_options'];
            $mail->Port       = $senderOptions['email_port'];

            //Recipients
            $mail->setFrom($senderOptions['email_username'], 'Henfrey');
            $mail->addAddress($recipient, $name);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $mail->MsgHTML($content);

            if ($mail->send()) {
                $responseArray['response'] = '200';
                $responseArray['message'] = 'Success sending the email';
                $responseArray['data'] = null;
            }
        } catch (Exception $error) {
            $responseArray['response'] = '500';
            $responseArray['message'] = "An error occurred sending the email to you";
            $responseArray['data'] = null;

            Logger::logToFile('Error', 'A PHPMailerException Error'. $error->getMessage());
        } catch (\Exception $error) {
            $responseArray['response'] = '500';
            $responseArray['message'] = "An error occurred sending the email to you";
            $responseArray['data'] = null;
            Logger::logToFile('Error', 'An Exception Error'. $error->getMessage());
        }

        return $responseArray;
    }
}
