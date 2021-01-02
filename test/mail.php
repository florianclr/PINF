<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->SMTPDebug = 2;
$mail->SMTPAuth = true;
$mail->isSMTP();
$mail->setAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'MONADRESSEMAIL';
$mail->Password = 'password';
$mail->SMTPSecure = 'ssl';
$mail->Port = '465';


$mail->setFrom('adressemaillambda', 'Personne');
$mail->addAddress('MONADRESSEMAIL');



$mail->isHTML(true);
$mail->Subject = 'Demande d ouverture de compte';
$mail->Body = 'Ceci est un test.';


if ($mail->Send() == false)
{
	die($mail->ErrInfo);
}
else
{
	echo "It worked!\n";
}
?>