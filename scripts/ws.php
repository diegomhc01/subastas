<?php

require_once('websockets.php');

class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
    

  protected function process ($user, $message) {    
    $this->send($user,$message);
  }
  
  protected function connected ($user) {   
    $this->user = $user;
    // Do nothing: This is just an echo server, there's no need to track the user.
    // However, if we did care about the users, we would probably have a cookie to
    // parse at this step, would be looking them up in permanent storage, etc.
  }
  //PODRIA PROGRAMAR ACA PARA QUITAR DEL LISTADO DE USUARIOS CONECTADOS
  //CUANDO UN USUARIO SE DESCONECTA
  protected function closed ($user) {
    $usuario = $user->usuario;
    $idusuario = $user->idusuario;
    $nombre = $user->nombre;
    $apellido = $user->apellido;
    $perfil = $user->perfil;
    foreach($this->users as $u){
      if($u->perfil==4 || $u->perfil==2){
        if($u->perfil==4){
          $mes = json_encode(array('accion'=>9999,'usuarios'=>'<li class="clsusuariosop" id="'.$idusuario.'"><input type="button" class="clsbtnchatusuario" style="display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background-color:#0099CC;color:white;"" name="btnchatusuario" id="btnchatusuario'.$user->idusuario.'" value="'.$user->nombre.' '.$user->apellido.'"></li>','usdesc'=>$usuario,'usuario'=>$idusuario));
        }          
        if($u->perfil==2){            
          $mes = json_encode(array('accion'=>9999,'usuarios'=>'<li class="clsusuariosrem" id="'.$idusuario.'" style="background-color:#DDDDDD,color:black;padding-left:10px;margin-bottom:10px;border-bottom:1px solid black;">'.$nombre.' '.$apellido.'</li>','usdesc'=>$usuario,'usuario'=>$idusuario));
        }
        if($u->handshake){
            $mes = $this->frame($mes,$u);
            $result = @socket_write($u->socket, $mes, strlen($mes));
        }else{
          $holdingMessage = array('user' => $u, 'message' => $mes);
          $this->heldMessages[] = $holdingMessage;
        }
      }         
      if($perfil==4){
        if($u->perfil==1){
            $estilo = "width: 113px;font-size: 10px;border: 1px solid black;margin-bottom:2px;height:45px;background-color:#DDDDDD;color:black;";            
            $habilitado = 'disabled';       
            $mes = json_encode(array('accion'=>9999,'usuario'=>$idusuario,'success'=>true,'estilo'=>$estilo,'habilitado'=>$habilitado));
            if($u->handshake){
                $mes = $this->frame($mes,$u);
                $result = @socket_write($u->socket, $mes, strlen($mes));
            }else{
              $holdingMessage = array('user' => $u, 'message' => $mes);
              $this->heldMessages[] = $holdingMessage;
            }
        }
        if($u->perfil==4){
          $mes = json_encode(array('accion'=>9999,'usuarios'=>'<li class="clsusuariosop" id="'.$idusuario.'"><input type="button" class="clsbtnchatusuario" style="display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background-color:#0099CC;color:white;"" name="btnchatusuario" id="btnchatusuario'.$user->idusuario.'" value="'.$user->nombre.' '.$user->apellido.'"></li>','usdesc'=>$usuario,'usuario'=>$idusuario));
          if($u->handshake){
            $mes = $this->frame($mes,$u);
            $result = @socket_write($u->socket, $mes, strlen($mes));
          }else{
            $holdingMessage = array('user' => $u, 'message' => $mes);
            $this->heldMessages[] = $holdingMessage;
          }          
        }
        if($u->perfil==2){
          $mes = json_encode(array('accion'=>9999,'usuarios'=>'<li class="clsusuariosrem" id="'.$idusuario.'" style="background-color:#DDDDDD,color:black;padding-left:10px;margin-bottom:10px;border-bottom:1px solid black;">'.$nombre.' '.$apellido.'</li>','usdesc'=>$usuario,'usuario'=>$idusuario));
          if($u->handshake){
            $mes = $this->frame($mes,$u);
            $result = @socket_write($u->socket, $mes, strlen($mes));
          }else{
            $holdingMessage = array('user' => $u, 'message' => $mes);
            $this->heldMessages[] = $holdingMessage;
          }          
        }
      }

    }
    $this->disconnect($user->socket);
    
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
  }

}


$echo = new echoServer("0.0.0.0","9000");

try {
  $echo->run();
}
catch (Exception $e) {
  //$file = fopen("/home/develhard/public_html/intertv/remates/fotos/ses.txt", "a");
  //$file = fopen("C:\\xampp\\htdocs\\intertv\\remates\\fotos\\ses.txt", "a");

  //$info = $e->getMessage();
  //fwrite($file, $info);
  //fclose($file);
  $echo->stdout($e->getMessage());
}


