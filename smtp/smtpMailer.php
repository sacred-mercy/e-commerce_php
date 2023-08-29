<?php
require_once 'PHPMailer.php';
require_once 'SMTP.php';
require_once 'Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function smtp_mailer($to, $subject, $msg, $file = null, $filename = null)
{
	require_once 'env.php';
	$email = $EMAIL;
	$pass = $EMAIL_PASS;
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587;
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	// $mail->SMTPDebug = 2; 
	$mail->Username = $email;
	$mail->Password = $pass;
	$mail->SetFrom($email);
	$mail->Subject = $subject;
	$mail->Body = $msg;
	$mail->AddAddress($to);
	if ($file != null)
		$mail->AddAttachment($file, $filename);
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => false
		)
	);
	if (!$mail->Send()) {
		return $mail->ErrorInfo;
	} else {
		return 'Sent';
	}
}
?>