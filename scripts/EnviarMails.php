<?php   		

function EnviarMails($to, $cc, $cco, $subject, $body)  {

	include_once("../PHPMailer/class.phpmailer.php");
	include_once("../PHPMailer/class.smtp.php");
	
	$from = "dhirschfeld@lapampa.gob.ar";
	$evalc = "xxxxxx";  // clave
	
	$mail = new PHPMailer();
	$mail->Mailer = "smtp";  
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Host =  // SMTP a utilizar. Por ej. "smtp.elserver.com"
	$mail->Username = $from;    // Correo completo a utilizar
	$mail->Password = $evalc; // Contraseña
	$mail->Port = 587; // Puerto a utilizar
	$mail->From = $from;   // Desde donde enviamos (Para mostrar)
	$mail->FromName = "Diego Hirschfeld";
	$mail->AddAddress($to); // Esta es la dirección a donde enviamos
	if ($cc <> "") $mail->AddCC($cc); // Copia
	if ($cco <> "") $mail->AddBCC($cco); // Copia oculta
	$mail->IsHTML(true); // El correo se envía como HTML
	$mail->Subject = $subject; // Este es el titulo del email.
	$mail->Body = $body; // Mensaje a enviar
	//$mail->AddAttachment('imagenes/imagen.jpg', 'imagen.jpg');
	$exito = $mail->Send(); // Envía el correo.
	return $exito;
}

?>