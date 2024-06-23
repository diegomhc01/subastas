<?php
include('validar.php');
include('coneccion.php');   
include('../mail/remates.php');
if(isset($_SESSION['susuario'])){
    $idfirma = $_SESSION['sidfirma'];
    // Iniciamos el "bucle" para enviar multiples correos. 
    $sql = "SELECT email FROM cliente WHERE idfirma = $idfirma and estado = 0";
    $rs = mysql_query($sql);
    $correo[] = 'diegomhc01@gmail.com';
    $correo[] = 'diegomhc01@gmail.com';
    $correo[] = 'diegomhc01@gmail.com';
    $correo[] = 'ccorgniati@gmail.com';
    $correo[] = 'ccorgniati@gmail.com';
    $correo[] = 'ccorgniati@gmail.com';
    //while($fila=mysql_fetch_array($rs)){ 
        //$correoenv = $fila[0];
    for($i=0;$i<6;$i++){
        $correoenv = $correo[$i];
        EnviarMails($correoenv,$cc,$cco,'Remate Nro 56',$cuerpo.$correoenv);
    }
}

function EnviarMails($to, $cc, $cco, $subject, $body) {
    include_once("../PHPMailer/class.phpmailer.php");
    include_once("../PHPMailer/class.smtp.php");
    
    $from = "inforemate@brandemannsc.com.ar";
    $evalc = "xV*FQQ@9nV";  // clave
    
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
    
     

