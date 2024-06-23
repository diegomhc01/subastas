<?php
include('validar.php');
include('coneccion.php');   
//include('../mail/remates.php');
include_once("../PHPMailer/class.phpmailer.php");
include_once("../PHPMailer/class.smtp.php");
if(isset($_SESSION['susuario'])){

    setlocale(LC_TIME, 'es_RA');
    $cuerpo = '';
    $idfirma = $_SESSION['sidfirma'];
    $idremate = filter_input(INPUT_POST,'param',FILTER_VALIDATE_INT);
    
    // Iniciamos el "bucle" para enviar multiples correos. 
    $sql = "SELECT date_format(r.fecha,'%w') as diasemana, ";
    $sql .= "date_format(r.fecha, '%d/%m/%Y') as fecha, r.hora, r.titulo, ";
    $sql .= "SUM(cantcabezas) AS cabezas, r.numero, r.tipo ";
    $sql .= "FROM remate r, lotes l WHERE r.idremate = $idremate and l.idremate = r.idremate ";
    $sql .= "GROUP BY r.fecha, r.hora, r.titulo ";    
    $rsr = mysql_query($sql);
    while($fila=mysql_fetch_array($rsr)){ 
        $dias = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles','Jueves', 'Viernes', 'S&aacute;bado');
        $fecha = $dias[$fila[0]].' '.$fila[1];
        $hora = $fila[2];
        $titulo = $fila[3];       
        $cantidad = $fila[4];
        $numero = $fila[5];
        $tipo = $fila[6];
        
    }

    $sql = "SELECT email FROM cliente WHERE estado <> 2";
    $rscliente = mysql_query($sql);
    if($tipo==1){//INTERNET
        $cuerpoo = generarcuerpo($fecha,$hora,$numero,300);
    }
    if($tipo==2){//FERIAS
        $cuerpoo = generarcuerpo($fecha,$hora,$numero,400);
    }
    //$cuerpoo = generarcuerpo($fecha,$hora,$numero,$cantidad);
    //$cuerpoo = generarcuerpoinicio();
    //$cuerpoo = generarcuerpocerdo();
    //$titulo = '10 remate anual de las cabanas LA ROSETA Y ALDO VIEJO';
    
    EnviarMails($rscliente,'','',$titulo,$cuerpoo);
    /*
    while($fcliente=mysql_fetch_array($rscliente)){
        if($fcliente[0]=='pablo@hwmail.com.ar' || 
            $fcliente[0]=='alejandro@hwmail.com.ar' ||
            $fcliente[0]=='ejencquel@advaserver.com'){            
        }else{
            $exito = false;
            $correoenv = $fcliente[0];
            $correoenv = 'diegomhc01@gmail.com';
            //$exito = EnviarMails($correoenv,'','',$titulo,$cuerpoo);
        }
    }
    */
    
}
 
function EnviarMails($to, $cc, $cco, $subject, $body) {
    //$from = "inforemate@brandemannsc.com.ar";
    $from = "informes@brandemann.develhard.com";
    //$evalc = "xV*FQQ@9nV";  // clave
    $evalc = "HDwIa!zN4c8T";
    
    $mail = new PHPMailer();
    $mail->PluginDir = "../PHPMailer/";
    $mail->Mailer = "smtp";  
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host =  'mail.brandemannsc.com.ar'; // SMTP a utilizar. Por ej. "smtp.elserver.com"
    //$mail->Host =  'mail.brandemann.develhard.com'; // SMTP a utilizar. Por ej. "smtp.elserver.com"
    $mail->Username = $from;    // Correo completo a utilizar
    $mail->Password = $evalc; // Contrase&ntilde;a
    $mail->Port = 25; // Puerto a utilizar
    $mail->From = $from;   // Desde donde enviamos (Para mostrar)
    $mail->FromName = "Brandemann y Cia SC";
    while($fcliente=mysql_fetch_array($to)){
        if($fcliente[0]=='pablo@hwmail.com.ar' || 
            $fcliente[0]=='alejandro@hwmail.com.ar' ||
            $fcliente[0]=='ejencquel@advaserver.com'){                    
        }else{
            $exito = false;
            $correoenv = $fcliente[0];        
            //$correoenv = 'diegomhc01@gmail.com';
            //$exito = EnviarMails($correoenv,'','',$titulo,$cuerpoo);
            
            $mail->AddAddress($correoenv); // Esta es la direcci&oacute;n a donde enviamos
            if ($cc <> "") $mail->AddCC($cc); // Copia
            if ($cco <> "") $mail->AddBCC($cco); // Copia oculta
            $mail->IsHTML(true); // El correo se env&iacute;a como HTML
            $mail->Subject = $subject; // Este es el titulo del email.
            $mail->Body = $body; // Mensaje a enviar
            //$mail->AddAttachment('../mail/logo.gif', 'logo.gif');
            $exito = $mail->Send(); // Env&iacute;a el correo.
            if(!$exito) echo $mail->ErrorInfo;
            $mail->ClearAddresses(); 
        }
    }
    return $exito;
 }
    
function generarcuerpo($fecha,$hora,$numero,$cantidad){
    $cuerpo = '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <img src="http://www.develhard.com/intertv/remates/mail/logo.gif">
        <p>Estimado cliente:</p>
        <p>Le comunicamos que este '.$fecha.', a las '.$hora.' hs realizaremos el '.$numero.'&deg; Remate de Hacienda en nuestro sitio www.brandemannsc.com.ar.</p>
        <p>Tendremos a la venta '.$cantidad.' vacunos de invernada y cr&iacute;a que ya est&aacute;n siendo publicados en el cat&aacute;logo.</p>
        <p>Con su correo y clave podr&aacute; acceder al remate como ESPECTADOR.</p>
        <p>Si desea participar como COMPRADOR, por favor cont&aacute;ctenos para que le habilitemos el permiso correspondiente.</p>
        <p>Esperamos tener el agrado de verlo conectado durante el remate y aprovechamos para enviarle un saludo cordial.</p>
        <p></p>
        <p>Brandemann y C&iacute;a SC.</p>
        <p></p>
        <p></p>

    </body>
    </html>';
    return $cuerpo;
//        <p>Oportunamente Ud. se suscribi&oacute; en nuestro sitio web para recibir recordatorios de remates. Si desea anular dicha suscripci&oacute;n, por favor haga clic en el siguiente AQUI</p>
}
function generarcuerpocerdo(){
    $cuerpo = '<html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        </head>
        <body>
            <img src="http://www.develhard.com/intertv/remates/mail/logo.gif">
            <p>Estimados clientes y amigos el motivo de este mail es para invitarlos al</p>
            <p>10º remate anual de las caba&ntilde;as LA ROSETA Y ALDO VIEJO  de los</p> 
            <p>se&ntilde;ores SAUL PEDERSOLI Y FEDERICO PISSINIS ma&ntilde;ana 02 DE JULIO</p> 
            <p>en las instalaciones del CLUB ESTUDIANTIL  donde podr&aacute;n apreciar 60 reproductores</p>
            <p>porcinos de todas las razas y cruzas para que puedan dar con su gusto</p>
            <p>y elegir el m&aacute;s indicado con el tipo de desarrollo porcino que ustedes lleven adelante.</p>
            <p></p>
            <p></p>
            <p>Al medio d&iacute;a el sal&oacute;n de la planta alta del club almorzaremos como siempre y</p>
            <p>a las 14.30 hs comenzaremos con las ventas en la pista del predio,</p> 
            <p>los plazos ser&aacute;n de 30/60 d&iacute;as y se otorgara un 5 % de descuento a quienes</p> 
            <p>quieran pagar las operaciones de contado.</p>
            <p>Para quienes no puedan viajar, el remate ser&aacute; trasmitido por internet y podr&aacute;n ofertar</p> 
            <p>a los tel&eacute;fonos ah&iacute; publicados ingresando en nuestra p&aacute;gina web <a href="http://brandemannsc.com.ar">WWW.BRANDEMANNSC.COM.AR</a></p>
            <p>o  ingresando directamente a la <a href="http://brandemannsc.com.ar/?page_id=11291">transmisi&oacute;n en vivo</a></p>
            <p></p>
            <p></p>
            <p></p>
            <p>Desde ya much&iacute;simas gracias, los esperamos!!!!!!!!!</p>
        </body>
    </html>';
    return $cuerpo;
}
function generarcuerpoinicio(){
    $cuerpo = '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <img src="http://www.develhard.com/intertv/remates/mail/logo.gif">
        <p>Estimados amigos y clientes:</p>    
        <p>En este mail queremos invitarlos al primer remate integrado de hacienda el cual podr&aacute;n operar,  como ya ven&iacute;amos anunci&aacute;ndoles, presencialmente desde la FERIA DE EDUARDO CASTEX como as&iacute; tambi&eacute;n desde su computadora en sus oficinas u hogares como est&eacute;n acostumbrados a seguirnos en estos ya tres a&ntilde;os de remates por internet, camino que tomamos juntos y creemos nos fue funcional a todos.</p>
        <p>En este &uacute;ltimo paso, vamos a rematar hacienda f&iacute;sica, desde los corrales de la feria,  los cuales van a salir filmados en el momento que pasen por la pista de ventas para que ustedes puedan verlos y as&iacute; poder ofertar desde sus sistemas de internet como habitualmente lo hacen o acompa&ntilde;&aacute;ndonos desde la feria.</p>
        <p>Luego en el sal&oacute;n, mientras compartimos el almuerzo, vamos a estar rematando la hacienda filmada la cual por supuesto podr&aacute;n operar tambi&eacute;n tanto presencialmente como por internet.</p>
        <p>Es nuevo desaf&iacute;o y por lo tanto les pedimos que nos acompa&ntilde;en tanto en los negocios como as&iacute; tambi&eacute;n en las cr&iacute;ticas constructivas que siempre nos ayudan a crecer y darle forma a este nuevo sistema que juntos vamos a desarrollar para darle la mejor utilidad y practicidad a la comercializaci&oacute;n de hacienda como desde hace m&aacute;s de 60 a&ntilde;os venimos haciendo juntos.</p>
        <p>El sistema es nuevo, por lo tanto le recomendamos que lo conozcan cuanto antes, para poder operar con comodidad, o para los m&aacute;s cercanos que se arrimen hasta la feria as&iacute; lo van conociendo y adem&aacute;s compartimos un d&iacute;a de remate como lo fue tradicionalmente.</p>
        <p>Desde este momento ya se est&aacute;n cargando los lotes que van a ser rematados y se est&aacute;n filmando, y a partir de ma&ntilde;ana a la tarde van a estar publicados con cantidades los lotes que van a ver pasar por los pista.</p>
        <p><strong>En el cat&aacute;logo podr&aacute;n ver los lotes filmados, recuerden que para operar deber&aacute;n ingresar con su usuario y contrase&ntilde;a (es el mismo de siempre) al igual que en el otro sistema. </strong></p>
        <p><strong>Les dejamos nuestros tel&eacute;fonos por cualquier duda que se les presente o inconveniente para <a href="http://www.develhard.com/brandemann">ingresar</a>:</strong></p>
        <p></p>
        <p>Guerre&ntilde;o Gast&oacute;n 02334 15-486884</p>
        <p>Cristian Corgniatti 02334 15-411244</p>
        <p>Hern&aacute;n Brandemann 02302 15-419018</p>
        <p>Oficina 02334-453512/14/72</p>
        <p>Desde ya much&iacute;simas gracias a todos, los esperamos….</p>
        <p></p>
        <p>Brandemann y C&iacute;a SC.</p>
        <p></p>
        <p></p>

    </body>
    </html>';
    return $cuerpo;
//        <p>Oportunamente Ud. se suscribi&oacute; en nuestro sitio web para recibir recordatorios de remates. Si desea anular dicha suscripci&oacute;n, por favor haga clic en el siguiente AQUI</p>
}


