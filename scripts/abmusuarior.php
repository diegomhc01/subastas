<?php
include('validar.php');	
include('coneccion.php');

	$arr = array('success'=>false,'mensaje'=>'');

	$idfirma = 1;

	$error = false;

	$apellido = filter_input(INPUT_POST, 'apellidoc', FILTER_SANITIZE_SPECIAL_CHARS);
	if($apellido===FALSE || is_null($apellido)) $error = true;
	$nombre = filter_input(INPUT_POST, 'nombrec', FILTER_SANITIZE_SPECIAL_CHARS);
	if($nombre===FALSE || is_null($nombre)) $error = true;
	$email = filter_input(INPUT_POST, 'emailc', FILTER_VALIDATE_EMAIL);
	if($email===FALSE || is_null($email)) $error = true;
	$telefono = filter_input(INPUT_POST, 'telefonoc', FILTER_SANITIZE_SPECIAL_CHARS);
	if($telefono===FALSE || is_null($telefono)) $error = true;

	$clave = filter_input(INPUT_POST, 'clavec', FILTER_SANITIZE_SPECIAL_CHARS);
	if($clave===FALSE || is_null($clave)) $error = true;

	$clave1 = filter_input(INPUT_POST, 'clave1c', FILTER_SANITIZE_SPECIAL_CHARS);
	if($clave1===FALSE || is_null($clave1)) $error = true;
	
	if(!$error){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$usuario = $email;
		$apellido = strtoupper($apellido);
		$nombre = strtoupper($nombre);
		$apeynom = $apellido.', '.$nombre;

		$sql = "INSERT INTO usuarios (usuario, clave, perfil, estado, operador, apellido, nombre, idfirma) ";
		$sql .= "VALUES ('$usuario', '$clave', 1, 0, '', '$apellido', '$nombre', $idfirma)";
		$rs = mysql_query($sql);

		$idusuario = mysql_insert_id();

		$sql = "INSERT INTO persona (apeynom, estado) VALUES ('$apeynom', 0)";

		$rs1 = mysql_query($sql);

		$idpersona = mysql_insert_id();

		$sql = "INSERT INTO cliente (estado, telefono, email, idpersona, idusuario, idfirma) VALUES ";
		$sql .= "(0, ";
		$sql .= "'$telefono', ";
		$sql .= "'$email', ";
		$sql .= "$idpersona, ";
		$sql .= "$idusuario,";
		$sql .= "$idfirma)";

		$rs2 = mysql_query($sql);

		$sql = "INSERT INTO creditos (usuario, monto, estado) VALUES ('$usuario',0,0)";
		$rs3 = mysql_query($sql);

		if($rs && $rs1 && $rs2 && $rs3){
			mysql_query("COMMIT");
			$mensaje = "SE HA REGISTRADO CORRECTAMENTE";
			$arr = array('success'=>true,'mensaje'=>$mensaje);
			
			$cuerpoo = generarcuerpo($email,$clave,$apeynom);
		  	$titulo = 'Brandemann y Cia. SC - Remates por Internet';
		    $correoenv = $email;
		    EnviarMails($correoenv,'','',$titulo,$cuerpoo);			
		}else{
			mysql_query("ROLLBACK");
			$mensaje = "ERROR AL REGISTRARSE";
			$arr = array('success'=>false,'mensaje'=>$mensaje);
		}
	}	
	echo json_encode($arr);

function EnviarMails($to, $cc, $cco, $subject, $body) {
    include_once("../PHPMailer/class.phpmailer.php");
    include_once("../PHPMailer/class.smtp.php");
    
    //$from = "inforemate@brandemannsc.com.ar";
    //$evalc = "xV*FQQ@9nV";  // clave
    $from = "informes@brandemann.develhard.com";
    $evalc = "HDwIa!zN4c8T";

    $mail = new PHPMailer();
    $mail->Mailer = "smtp";  
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host =  'mail.brandemannsc.com.ar'; // SMTP a utilizar. Por ej. "smtp.elserver.com"
    $mail->Username = $from;    // Correo completo a utilizar
    $mail->Password = $evalc; // Contraseña
    $mail->Port = 25; // Puerto a utilizar
    $mail->From = $from;   // Desde donde enviamos (Para mostrar)
    $mail->FromName = "Brandemann y Cia SC";
    $mail->AddAddress($to); // Esta es la dirección a donde enviamos
    if ($cc <> "") $mail->AddCC($cc); // Copia
    if ($cco <> "") $mail->AddBCC($cco); // Copia oculta
    $mail->IsHTML(true); // El correo se envía como HTML
    $mail->Subject = $subject; // Este es el titulo del email.
    $mail->Body = $body; // Mensaje a enviar
    //$mail->AddAttachment('../mail/logo.gif', 'logo.gif');
    $exito = $mail->Send(); // Envía el correo.
    return $exito;
}
    
function generarcuerpo($email,$clave,$apeynom){
    $cuerpo = '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <img src="http://www.develhard.com/intertv/remates/mail/logo.gif">
        <p><strong>Estimado '.$apeynom.': Bienvenido a nuestro Sistema de Remates por Internet</strong></p>
		<p>Por medio de la presente le confirmamos que Ud. <strong>se ha registrado exitosamente</strong></p>
		<p>Con su Usuario podrá acceder libremente a los Remates por Internet en las fechas y horarios indicados en nuestro sitio www.brandemannsc.com.ar en la sección denominada "Remate en Línea". Para acceder, haga clic en  <a href="http://www.develhard.com/brandemann"><strong>INGRESAR</strong></a> e indique su E-mail y Clave.</p>
		<p>Le recordamos que si está interesado en comprar durante los Remates por Internet, deberá previamente solicitarnos la autorización correspondiente.</p>
		<p>Siempre estamos a su disposición para evacuar toda duda y facilitarle la experiencia de operar con nosotros.</p>
		<p>Le agradecemos su confianza y quedamos a su disposición.</p>
		<p></p>
		<p><strong>Datos de acceso</strong></p>
		<p>Usuario:<strong>'.$email.'</strong></p>
		<p>Clave:<strong>La que ingresó cuando se registró oportunamente</strong></p>
		<p>Brandemann y Cía</p>
		<p>Casa Central: Eduardo Castex, La Pampa</p>
		<p>www.brandemannsc.com.ar / info@brandemannsc.com.ar</p>
		<p>tel: 02334-453512 / 14 / 40 (líneas rotativas)</p>
		<p>fax: 02334-452242</p>

    </body>
</html>';
return $cuerpo;
//        <p>Oportunamente Ud. se suscribió en nuestro sitio web para recibir recordatorios de remates. Si desea anular dicha suscripción, por favor haga clic en el siguiente AQUI</p>
}
?>
