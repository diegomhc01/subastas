<?php

//require_once('./daemonize.php');
require_once('users.php');
//require_once('macro.php');

abstract class WebSocketServer {

  protected $userClass = 'WebSocketUser'; // redefine this if you want a custom user class.  The custom user class should inherit from WebSocketUser.
  protected $maxBufferSize;        
  protected $master;
  protected $sockets                              = array();
  protected $users                                = array();
  protected $heldMessages                         = array();
  protected $interactive                          = true;
  protected $headerOriginRequired                 = false;
  protected $headerSecWebSocketProtocolRequired   = false;
  protected $headerSecWebSocketExtensionsRequired = false;

  private $mensaje;
  private $arreglo = array();
  private $usersend;

  function __construct($addr, $port, $bufferLength = 2048) {
    $this->maxBufferSize = $bufferLength;
    $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)  or die("Failed: socket_create()");
    socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) or die("Failed: socket_option()");
    socket_bind($this->master, $addr, $port)                      or die("Failed: socket_bind()");
    socket_listen($this->master,20)                               or die("Failed: socket_listen()");
    $this->sockets['m'] = $this->master;
    $this->stdout("Server started\nListening on: $addr:$port\nMaster socket: ".$this->master);
    $this->writelog("Server started\nListening on: $addr:$port\nMaster socket: ".$this->master);
  }
  
  abstract protected function process($user,$message); // Called immediately when the data is recieved. 
  abstract protected function connected($user);        // Called after the handshake response is sent to the client.
  abstract protected function closed($user);           // Called after the connection is closed.

  protected function connecting($user) {
    // Override to handle a connecting user, after the instance of the User is created, but before
    // the handshake has completed.
  }
  
  protected function broadcast($user, $message) {
    foreach ($thi->users as $u) {
      if($u!==$user){
        if ($user->handshake) {      
          $message = $this->frame($message,$u);
          $result = @socket_write($u->socket, $message, strlen($message));
        }else {
          $holdingMessage = array('user' => $u, 'message' => $message);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }
  protected function enviarusuarionuevo($user, $mesa){ 
    foreach ($this->users as $u) {
      if($u->perfil==4 || $u->perfil==2){
        if($u->perfil==4){
          $mes = json_encode(array('accion'=>1201,'usuarios'=>'<li class="clsusuariosop" id="'.$user->idusuario.'"><input type="submit" class="clsbtnchatusuario" style="display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background-color:#DDDDDD;color:black;"" name="btnchatusuario" id="btnchatusuario'.$user->idusuario.'" value="'.$user->nombre.' '.$user->apellido.'"></li>'));
        }          
        if($u->perfil==2){            
          $mes = json_encode(array('accion'=>1201,'usuarios'=>'<li class="clsusuariosrem" id="'.$user->idusuario.'" style="background-color:#DDDDDD,color:black;padding-left:10px;margin-bottom:10px;border-bottom:1px solid black;">'.$user->nombre.' '.$user->apellido.'</li>'));
        }
        if($u->handshake){
          $mes = $this->frame($mes,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {
          $holdingMessage = array('user' => $u, 'message' => $mes);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }  
  protected function buscarremateabierto($user,$mesa){
    if ($user->handshake) {
      $m = json_decode($mesa);
      foreach ($this->users as $u) {
        if($u==$user){
          $u->idremate = $m->idremate;
          break;
        }
      }
    }
  }
  protected function enviarchat($user, $mesa){  
    $mes = json_decode($mesa);
    if ($user->handshake) {      
      foreach ($this->users as $u) {
        if($u->usuario==$mes->usrr){          
          if ($u->handshake) {      
            $mesa = $this->frame($mesa,$u);
            $result = @socket_write($u->socket, $mesa, strlen($mesa));
          }else {
            $holdingMessage = array('user' => $u, 'message' => $mesa);
            $this->heldMessages[] = $holdingMessage;
          }          
        }
        if($u->perfil==2){
          $mensaje = json_encode(array('accion'=>1100,
            'mensaje'=>$mes->mensaje,            
            'apeynomr'=>$mes->apeynomr,
            'usre'=> $mes->usre,
            'hora'=> $mes->hora,
            'success'=> true,
            'color'=>$mes->color));
          if ($u->handshake) {      
            $m = $this->frame($mensaje,$u);
            $result = @socket_write($u->socket, $m, strlen($m));
          }else {
            $holdingMessage = array('user' => $u, 'message' => $mensaje);
            $this->heldMessages[] = $holdingMessage;
          }          
        }
      }
    }
  }
  protected function enviarchattodos($user, $mesa){  
    $mes = json_decode($mesa);
    if ($user->handshake) {      
      foreach ($this->users as $u) {
        if($u->usuario!=$user->usuario){          
          if($u->perfil==2){
            $mensaje = json_encode(array('accion'=>1110,
              'mensaje'=>$mes->mensaje,
              'usre'=> $mes->usre,
              'hora'=> $mes->hora,
              'success'=> true,
              'color'=>$mes->color));
            if ($u->handshake) {      
              $m = $this->frame($mensaje,$u);
              $result = @socket_write($u->socket, $m, strlen($m));
            }else {
              $holdingMessage = array('user' => $u, 'message' => $mensaje);
              $this->heldMessages[] = $holdingMessage;
            }          
          }else{            
            if ($u->handshake) {      
              $mesa = $this->frame($mesa,$u);
              $result = @socket_write($u->socket, $mesa, strlen($mesa));
            }else {
              $holdingMessage = array('user' => $u, 'message' => $mesa);
              $this->heldMessages[] = $holdingMessage;
            }          
          }        
        }
      }
    }
  }  
  protected function enviarbloqueocliente($user, $mesa){
      foreach ($this->users as $u) {
        if(($u->perfil==4 || $u->perfil==2) && $u!=$user){
            $mes = $mesa;
          if ($u->handshake) {
            $mes = $this->frame($mes,$u);
            $result = @socket_write($u->socket, $mes, strlen($mes));
          }else {
            $holdingMessage = array('user' => $u, 'message' => $mes);
            $this->heldMessages[] = $holdingMessage;
          }
        }
      }    
  }
  protected function enviaroperadorconectado($user, $mesa){    
    $estilo = "width: 113px;font-size: 10px;border: 1px solid black;margin-bottom:2px;height:45px;";
    $estilo .= "background-color:#0099CC;color:white;";    
    $habilitado = '';

    if($user->handshake){
      foreach ($this->users as $u) {
        if($u->usuario!=$user->usuario){
          if($u->perfil==1){
            $mes = json_encode(array('accion'=>1210,'usuario'=>$user->idusuario,'success'=>true,'estilo'=>$estilo,'habilitado'=>$habilitado));
          }          
          if($u->perfil==2){
            $mes = json_encode(array('accion'=>1210,'usuario'=>$user->idusuario,'usuarios'=>'<li class="clsusuariosrem" id="'.$user->usuario.'" style="background-color:#DDDDDD,color:black;padding-left:10px;margin-bottom:10px;border-bottom:1px solid black;">'.$user->nombre.' '.$user->apellido.'</li>'));
          }
          if($u->perfil==4){
            $mes = json_encode(array('accion'=>1201,'usuarios'=>'<li class="clsusuariosop" id="'.$user->idusuario.'"><input type="submit" class="clsbtnchatusuario" style="display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: bold;line-height: 1;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background-color:#DDDDDD;color:black;"" name="btnchatusuario" id="btnchatusuario'.$user->idusuario.'" value="'.$user->nombre.' '.$user->apellido.'"></li>'));
          }
          if ($u->handshake) {                
            $m = $this->frame($mes,$u);
            $result = @socket_write($u->socket, $m, strlen($m));
          }else {
            $holdingMessage = array('user' => $u, 'message' => $mes);
            $this->heldMessages[] = $holdingMessage;
          }
        }
      }
    }
  }
  protected function desconectarusuario($user, $mesa){
    if ($user->handshake) {
      foreach ($this->users as $u) {
        if($u->perfil==4 || $u->perfil==2){
          $mes = json_encode(array('accion'=>1202, 'usuario'=>$user->usuario));
          $mes = $this->frame($mes,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {
          $holdingMessage = array('user' => $u, 'message' => $mes);
          $this->heldMessages[] = $holdingMessage;
        }      
      }
    }
  }
  protected function realizaroferta($user, $mesa){
    $mes = json_decode($mesa);
    foreach ($this->users as $u) {
      if($u->perfil==2){
        //$m = json_encode(array('accion'=>2001,'clientea'=>$user->apellido,'clienten'=>$user->nombre,'usuario'=>$user->usuario,'monto'=>$mes->monto));
        $m = json_encode(array('accion'=>2001));
        if($u->handshake){
          $m = $this->frame($m,$u);
          $result = @socket_write($u->socket, $m, strlen($m));
        }else {
          $holdingMessage = array('user' => $u, 'message' => $m);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }
  protected function realizarofertaop($user, $mesa){
    $mdecod = json_decode($mesa);
    foreach ($this->users as $u) {
      if($u!=$user){      
        if ($u->handshake) {        
          $m = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $m, strlen($m));
        }else {
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }        
      }
    }
  }
  protected function solicitarcredito($user, $mesa){
    foreach ($this->users as $u) {
      if($u->perfil==4){
        if ($u->handshake) {
          $mesa = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $mesa, strlen($mesa));
        }else {
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }
  protected function modificarcredito($user,$mesa){
    $mes = json_decode($mesa);
    foreach ($this->users as $u) {
      if($mes->detalle!=''){
        if($u!=$user){ 
          if($u->usuario==$mes->usuario){
            if ($u->handshake) {
              $mes = $this->frame($mesa,$u);
              $result = @socket_write($u->socket, $mes, strlen($mes));
            }else {
              $holdingMessage = array('user' => $u, 'message' => $mesa);
              $this->heldMessages[] = $holdingMessage;
            }
          }
          if($u->perfil==4){
              if ($u->handshake) {
                $mes = $this->frame($mesa,$u);
                $result = @socket_write($u->socket, $mes, strlen($mes));
              }else {
                $holdingMessage = array('user' => $u, 'message' => $mesa);
                $this->heldMessages[] = $holdingMessage;
              }        
          }
        }
      }       
    }
  }
  protected function enviarpasarlote($user, $mesa){
    if ($user->handshake) {
      $mdecod = json_decode($mesa);
      $mes = $this->frame($mesa,$user);
      $result = @socket_write($user->socket, $mes, strlen($mes));
      if($mdecod->success==true){
        foreach ($this->users as $u) {
          if($user!=$u){
            if($u->handshake){
              $mes = $this->frame($mesa,$user);
              $result = @socket_write($u->socket, $mes, strlen($mes));
            }else {            
              $holdingMessage = array('user' => $u, 'message' => $mesa);
              $this->heldMessages[] = $holdingMessage;
            }
          }        
        }
      }      
    }
  }
  protected function enviarbajarmartillo($user, $mesa){    
    $mdecod = json_decode($mesa);
    if($mdecod->success==true){
      foreach ($this->users as $u) {
        if($user!=$u){            
          if($u->handshake){
            $mes = $this->frame($mesa,$user);
            $result = @socket_write($u->socket, $mes, strlen($mes));
          }else {            
            $holdingMessage = array('user' => $u, 'message' => $mesa);
            $this->heldMessages[] = $holdingMessage;
          }
        }        
      }
    }
  }
  protected function enviarcerrarlote($user, $mesa){    
    $mdecod = json_decode($mesa);
    if($mdecod->success==true){
      foreach ($this->users as $u) {
        if($user!=$u && $u->perfil==4){                          
          if($u->handshake){
            $mes = $this->frame($mesa,$user);      
            $result = @socket_write($u->socket, $mes, strlen($mes));
          }else{            
            $holdingMessage = array('user' => $u, 'message' => $mesa);
            $this->heldMessages[] = $holdingMessage;
          } 
        }
        if($user!=$u && $u->idususario==$mdecod->idusuario){                          
          if($u->handshake){
            $mes = $this->frame($mesa,$user);      
            $result = @socket_write($u->socket, $mes, strlen($mes));
          }else{            
            $holdingMessage = array('user' => $u, 'message' => $mesa);
            $this->heldMessages[] = $holdingMessage;
          } 
        }else{
          if($user!=$u){            
            $arr = array('accion'=>5008,
              'success'=>true,
              'detalle'=>'SE CERRO EL LOTE N° '.$mdecod->nrolote,
              'mensajeop'=>'A TODOS '.$detalle,
              'tipo'=>'ds',
              'idusuario'=>$idusuario,
              'hora'=>$mdecod->hora);
            $mes = json_encode($arr);
            if($u->handshake){
              $mes = $this->frame($mes,$user);      
              $result = @socket_write($u->socket, $mes, strlen($mes));
            }else{            
              $holdingMessage = array('user' => $u, 'message' => $mes);
              $this->heldMessages[] = $holdingMessage;
            } 
          }
        }
      }
    }
  }
  protected function enviarabrirlote($user, $mesa){
    foreach ($this->users as $u) {
      if($u!=$user){          
        if($u->handshake){
          $mes = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {            
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }
  protected function enviarreabrirlote($user, $mesa){
    if ($user->handshake) {
      $mdecod = json_decode($mesa);
      $mes = $this->frame($mesa,$user);
      $result = @socket_write($user->socket, $mes, strlen($mes));
      foreach ($this->users as $u) {
        if($u!=$user){          
          if($u->handshake){
            $mes = $this->frame($mesa,$user);
            $result = @socket_write($u->socket, $mes, strlen($mes));
            $u->idlote = $mdecod->idlote;
          }else {            
            $holdingMessage = array('user' => $u, 'message' => $mesa);
            $this->heldMessages[] = $holdingMessage;
          }
        }
      }
    }
  }
  protected function enviaraceptaroferta($user,$mesa){
    $mesa = json_encode($mesa);
    $mdecod = json_decode($mesa);
    if($mdecod->success==true){
      foreach ($this->users as $u) {
        if($user!=$u){
          if($u->usuario==$mdecod->usuario){  
            if($u->handshake){
              $mes = $this->frame($mesa,$u);
              $result = @socket_write($u->socket, $mes, strlen($mes));
            }else {            
              $holdingMessage = array('user' => $u, 'message' => $mesa);
              $this->heldMessages[] = $holdingMessage;
            }
          }else{
            if($u->usuario==$mdecod->usuariosuperado){                
              $mensaje = json_encode(array('accion'=>5009,
                'success'=>true,
                'estado'=>2,
                'monto'=>$mdecod->monto,
                'hora'=>$mdecod->hora,
                'detalle'=>'SU OFERTA FUE SUPERADA'));
              $this->enviarofertasuperada($u,$mensaje);
            }else{
              if($u->perfil==4){
                if($u->handshake){
                  $mes = $this->frame($mesa,$u);
                  $result = @socket_write($u->socket, $mes, strlen($mes));
                }else {            
                  $holdingMessage = array('user' => $u, 'message' => $mesa);
                  $this->heldMessages[] = $holdingMessage;
                }                
              }else{
                $mensaje = json_encode(array('accion'=>5009,
                  'success'=>true,
                  'estado'=>4,
                  'monto'=>$mdecod->monto,
                  'hora'=>$mdecod->hora,
                  'detalle'=>''));
                if($u->handshake){
                 $mensaje = $this->frame($mensaje,$u);
                 $result = @socket_write($u->socket, $mensaje, strlen($mensaje));
                }else {            
                  $holdingMessage = array('user' => $u, 'message' => $mensaje);
                  $this->heldMessages[] = $holdingMessage;
                }
              }
            }
          }
        }
      }
    }
  }
  protected function enviaranularoferta($user,$mesa){
    $mdecod = json_decode($mesa);            
    if($mdecod->success==true){
      foreach ($this->users as $u) {
        if($user!=$u){
          if($mdecod->idoferta>0){
            if($u->usuario==$mdecod->usuario){//USUARIO ANULADO
              $arr = array('accion'=>5014,
                'success'=>true,
                'idoferta'=>$mdecod->idofertamax,
                'monto'=>$mdecod->monto,
                'detalle'=>$mdecod->mensajecli,
                'tipo'=>'dh',
                'idDS'=>$mdecod->idDS,
                'hora'=>$mdecod->hora);
              $m1 = json_encode($arr);
              if($u->handshake){
                $m1 = $this->frame($m1,$u);
                $result = @socket_write($u->socket, $m1, strlen($m1));
              }else {            
                $holdingMessage = array('user' => $u, 'message' => $m1);
                $this->heldMessages[] = $holdingMessage;
              }
            }else{              
              //USUARIO QUE QUEDA CON LA OFERTA GANADORA
              //EN SU MOMENTO FUE SUPERADO
              //FUE LA ULTIMA OFERTA GANADORA
              if($u->usuario==$mdecod->usuariomax){ 
                $arr1 = array('accion'=>5014,
                  'success'=>true,
                  'idoferta'=>$mdecod->idofertamax,
                  'monto'=>$mdecod->monto,
                  'detalle'=>'SU OFERTA ES LA MEJOR',
                  'tipo'=>'dh',
                  'idDS'=>$mdecod->idDS,
                  'hora'=>$mdecod->hora);
                $m2 = json_encode($arr1);
                if($u->handshake){
                  $m2 = $this->frame($m2,$u);
                  $result = @socket_write($u->socket, $m2, strlen($m2));
                }else {            
                  $holdingMessage = array('user' => $u, 'message' => $m2);
                  $this->heldMessages[] = $holdingMessage;
                }
              }else{
                //PERFIL OPEADMIN
                if($u->perfil==4){
                  $arr2 = array('accion'=>5014,
                    'success'=>true,
                    'monto'=>$mdecod->monto,
                    'tipo'=>'dh',
                    'idDS'=>$mdecod->idDS,
                    'mensajeop'=>$mdecod->mensajeop,
                    'detalle2'=>$mdecod->apeynommax,
                    'hora'=>$mdecod->hora);
                  $m3 = json_encode($arr2);
                  if($u->handshake){
                    $m3 = $this->frame($m3,$u);
                    $result = @socket_write($u->socket, $m3, strlen($m3));
                  }else {            
                    $holdingMessage = array('user' => $u, 'message' => $m3);
                    $this->heldMessages[] = $holdingMessage;
                  }
                }else{
                  //PERFIL DISTINTO DE REMATADOR
                  //AL RESTO DE LOS CLIENTES Y OPERADORES
                  if($u->perfil!=2){                  
                    $arr2 = array('accion'=>5014,
                      'success'=>true,
                      'idoferta'=>0,
                      'monto'=>$mdecod->monto,
                      'tipo'=>'dh',
                      'idDS'=>$mdecod->idDS,
                      'detalle'=>'',
                      'hora'=>$mdecod->hora);                
                    $m3 = json_encode($arr2);
                    if($u->handshake){
                      $m3 = $this->frame($m3,$u);
                      $result = @socket_write($u->socket, $m3, strlen($m3));
                    }else {            
                      $holdingMessage = array('user' => $u, 'message' => $m3);
                      $this->heldMessages[] = $holdingMessage;
                    }
                  }
                }
              }
            }            
          }
        }
      }
    }
  }
  protected function enviarofertasuperada($user, $mesa){
    if ($user->handshake) {
      $mesa = $this->frame($mesa,$user);    
      $result = @socket_write($user->socket, $mesa, strlen($mesa));
    }else {
      $holdingMessage = array('user' => $user, 'message' => $mesa);
      $this->heldMessages[] = $holdingMessage;
    }
  }
  protected function enviaromitiroferta($user,$mesa){
    $mdecod = json_decode($mesa);
    if($mdecod->success==true){
      foreach ($this->users as $u) {
        if($user!=$u){            
          if($u->idusuario==$mdecod->idusuario || $u->perfil==4){            
            if($u->handshake){
              $mes = $this->frame($mesa,$u);
              $result = @socket_write($u->socket, $mes, strlen($mes));
            }else {            
              $holdingMessage = array('user' => $u, 'message' => $mesa);
              $this->heldMessages[] = $holdingMessage;
            }
          }
        }
      }
    }
  }
  protected function enviarprecioinicio($user, $mesa){
    foreach ($this->users as $u) {
      if($user!=$u){          
        if ($u->handshake){
          $mes = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {            
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }  
  protected function enviarincremento($user, $mesa){    
    foreach ($this->users as $u){
      if($user!=$u){        
        if ($u->handshake){
          $mes = $this->frame($mesa,$u);      
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {            
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }
  protected function enviartipoprecio ($user, $mesa){
    foreach ($this->users as $u) {
      if($user!=$u){        
        if ($u->handshake){
          $mes = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {            
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }    
  }
  protected function enviarofertarcerrarop($user, $mesa){
    foreach ($this->users as $u) {
      if($user!=$u && $u->perfil==2){
        if ($u->handshake){
          $mes = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {            
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }
  protected function refrescarrematecliente($user, $mesa){
    foreach ($this->users as $u) {
      if($user!=$u){
        if ($u->handshake){
          $mes = $this->frame($mesa,$u);
          $result = @socket_write($u->socket, $mes, strlen($mes));
        }else {            
          $holdingMessage = array('user' => $u, 'message' => $mesa);
          $this->heldMessages[] = $holdingMessage;
        }
      }
    }
  }

  protected function send($user, $message) {
      /*******************************************************************
      ACA VA LO QUE RECIBE DESDE EL CLIENTE Y SE ENVIA AL CLIENTE
      TENGO QUE AGREGAR UN CAMPO MAS DEL JSON PARA SABER QUE SCRIPTS UTILIZAR

      *******************************************************************/

    if ($user->handshake) {

      $mes = json_decode($message);
      //print_r($mes);
      if(!isset($mes->accion)){
        $user->buscarusuario($mes->sessionid);
        if($user->perfil==1){
          $this->enviarusuarionuevo($user,'');
        }
        if($user->perfil==4){
          $this->enviaroperadorconectado($user,'');          
        }
      }else{
        switch($mes->accion) {
          case 1000:
                //BUSCAR PRECIO INICIAL
                include('buscar_precio_inicial.php');
                break;
          case 1100:              
                $this->enviarchat($user,$message);
                break;
          case 1110:
                $this->enviarchattodos($user,$message);
                break;
          case 2001:              
                $this->realizaroferta($user, $message);
                break;
          case 2002:              
                $this->solicitarcredito($user, $message);
                break;              
          case 4000:
                $this->modificarcredito($user, $message);
                break;
          case 4001:
                $this->realizarofertaop($user, $message);
                break;
          case 4400:
                $this->enviarbloqueocliente($user, $message);
                break;
          case 9999:              
                //$this->desconectarusuario($user, json_encode(array('accion'=>9999)));
                break;
          case 5002:
                //CHAT            
                $this->enviardatoslote($user,$message);
                break;
          case 5003:
                //ABRIR LOTE
                $this->enviarabrirlote($user,$message);
                break;
          case 1204:
                //REABRIR LOTE
                $this->enviarreabrirlote($user,$message);
                break;
          case 5005:
                //PASAR LOTE
                $this->enviarpasarlote($user,$message);
                break;
          case 5007:
                //BAJAR MARTILLO
                $this->enviarbajarmartillo($user,$message);
                break;
          case 5008:
                //MOSTRAR DESARROLLO DEL REMATE
                $this->enviarcerrarlote($user,$message);
                break;
          case 5009:
                //ACEPTAR OFERTA
                $this->enviaraceptaroferta($user,$mes);
                break;                  
          case 5010:
                $this->enviaromitiroferta($user,$message);
                break;       
          case 5011:
                $this->enviarprecioinicio($user,$message);
                break;              
          case 5012:
                $this->enviarincremento($user,$message);
                break;
          case 5013:
                $this->enviartipoprecio($user,$message);
                break;               
          case 5014:
                $this->enviaranularoferta($user,$message);
                break; 
          case 9007:
                $this->enviarofertarcerrarop($user,$message);
                break; 
          case 10000:
                $this->refrescarrematecliente($user,$message);
                break; 
        } 
      }
        //llamo al script que corresponda
        //el valor de retorno se lo asigno a $message
        //si la respuesta es a todos los clientes
        //$this->broadcast($this->users, $message)
        //si no
        
        //$mesa = $this->frame($mesa,$user);
        //$result = @socket_write($user->socket, $mesa, strlen($mesa));
    }
    else {
      // User has not yet performed their handshake.  Store for sending later.
      $holdingMessage = array('user' => $user, 'message' => $message);
      $this->heldMessages[] = $holdingMessage;
    }
  }

  protected function tick() {
    // Override this for any process that should happen periodically.  Will happen at least once
    // per second, but possibly more often.    
    /*
    foreach ($this->users as $user) {
      $this->send($user, $this->mensaje);
    }
    */
  }

  protected function _tick() {
    // Core maintenance processes, such as retrying failed messages.
    foreach ($this->heldMessages as $key => $hm) {
      $found = false;      
      foreach ($this->users as $currentUser) {
        if ($hm['user']->socket == $currentUser->socket) {
          $found = true;
          if ($currentUser->handshake) {
            unset($this->heldMessages[$key]);            
            $this->send($currentUser, $hm['message']);
          }
        }
      }
      if (!$found) {
        // If they're no longer in the list of connected users, drop the message.
        unset($this->heldMessages[$key]);
      }
    }
  }

  /**
   * Main processing loop
   */
  public function run() {
    while(true) {
      if (empty($this->sockets)) {
        $this->sockets['m'] = $this->master;        
      }
      $read = $this->sockets;
      $write = $except = null;
      $this->_tick();
      $this->tick();
    /***********************************************************************************
    ACA VA LA RESPUESTA QUE CORRESPONDA 
    TENGO QUE AGREGAR UN CAMPO MAS AL JSON PARA QUE SEPA QUE ES LO QUE TENGO QUE ACTUALIZAR
      echo "escribe arreglo\n";
      print_r($this->arreglo);
      print_r($this->mensaje);
      print_r($ar);
      print_r($ar['accion']);
    ***********************************************************************************/ 

      //socket_select() acepta matrices de sockets y las espera para cambiar el estado.
      //Aquellas que vienen con un fondo de sockets BSD reconocerán que aquellas matrices 
      //de recursos socket son de hecho los también llamados conjuntos descriptores de archivos. 
      //Se observan tres matrices de recursos socket independientes.
      //PARAMETROS
      //read
      //Los sockets listados en la matriz read serán observados para ver si los caracteres están 
      //disponibles para lectura (más precisamente, para ver si una lectura no bloqueará - en particular, 
      //un recurso socket también está listo al final del archivo, en cuyo caso un socket_read() devolverá una cadena de longitud cero).
      //write
      //Los sockets listados en la matriz write serán observados para ver si una escritura no bloqueará.
      //except
      //Los sockets listados en la matriz except serán observados para excepciones.
      @socket_select($read,$write,$except,1);
      foreach ($read as $socket) {        
        if ($socket == $this->master) {
          $client = socket_accept($socket);
          if ($client < 0) {
            $this->stderr("Falló: socket_accept()");
            continue;
          } 
          else {
            $this->connect($client);
            $this->stdout("Cliente conectado. " . $client);
            $this->writelog("Cliente conectado. " . $client);
            foreach ($this->users as $currentUser) {
              //$this->stdout("ID Usuario. " . $currentUser->handshake);
              $this->writelog("ID Usuario. " . $currentUser->handshake);
            }            
          }
        } 
        else {
          //La función socket_recv() recibe $this->maxBufferSize bytes de información en $buffer desde $socket. 
          //socket_recv() se pede usar para reunir información desde sockets conectados. 
          //Además, se pueden especificar una o más banderas para modificar el comportamiento de la función.
          //socket_recv() devuelve el número de bytes recibidos, o FALSE si hubo un error. 
          $numBytes = @socket_recv($socket, $buffer, $this->maxBufferSize, 0);           
          if ($numBytes === false) {
            //recupera el código de error real
            $sockErrNo = socket_last_error($socket);
            switch ($sockErrNo)
            {
              case 102: // ENETRESET    -- Network dropped connection because of reset
              case 103: // ECONNABORTED -- Software caused connection abort
              case 104: // ECONNRESET   -- Connection reset by peer
              case 108: // ESHUTDOWN    -- Cannot send after transport endpoint shutdown -- probably more of an error on our part, if we're trying to write after the socket is closed.  Probably not a critical error, though.
              case 110: // ETIMEDOUT    -- Connection timed out
              case 111: // ECONNREFUSED -- Connection refused -- We shouldn't see this one, since we're listening... Still not a critical error.
              case 112: // EHOSTDOWN    -- Host is down -- Again, we shouldn't see this, and again, not critical because it's just one connection and we still want to listen to/for others.
              case 113: // EHOSTUNREACH -- No route to host
              case 121: // EREMOTEIO    -- Rempte I/O error -- Their hard drive just blew up.
              case 125: // ECANCELED    -- Operation canceled
                
                $this->stderr("Desconección excepcional con el socket " . $socket ." - ".$sockErrNo);
                $this->disconnect($socket, true, $sockErrNo); // disconnect before clearing error, in case someone with their own implementation wants to check for error conditions on the socket.
                break;
              default:
                $this->stderr('Error de Socket: ' . socket_strerror($sockErrNo));
            }            
          }
          elseif ($numBytes == 0) {
            $this->disconnect($socket);
            $this->stderr("Cliente desconectado. Conección TCP perdida: " . $socket);
          } 
          else {
            $user = $this->getUserBySocket($socket);
            //handshake => conversación
            if (!$user->handshake) {
              //Quita todos los returns del mensaje
              $tmp = str_replace("\r", '', $buffer);
              //Encuentra la posición numérica de la primera ocurrencia 
              if (strpos($tmp, "\n\n") === false ) {
                //Si el cliente no ha terminado de enviar la cabecera, 
                //y luego espera antes de enviar nuestra respuesta actualización.
                continue; // If the client has not finished sending the header, then wait before sending our upgrade response.
              }
              $this->doHandshake($user,$buffer);
            } 
            else {
              //split packet into frame and send it to deframe
              //paquete dividido en marco y enviarlo a deframe
              $this->split_packet($numBytes,$buffer, $user);
            }
          }
        }
      }
    }
  }

  protected function connect($socket) {
    $user = new $this->userClass(uniqid('u'), $socket);
    $this->users[$user->id] = $user;
    $this->sockets[$user->id] = $socket;
    $this->connecting($user);
  }

  protected function disconnect($socket, $triggerClosed = true, $sockErrNo = null) {
    $disconnectedUser = $this->getUserBySocket($socket);
    
    if ($disconnectedUser !== null) {
      unset($this->users[$disconnectedUser->id]);
        
      if (array_key_exists($disconnectedUser->id, $this->sockets)) {
        unset($this->sockets[$disconnectedUser->id]);
      }
      
      if (!is_null($sockErrNo)) {
        socket_clear_error($socket);
      }

      if ($triggerClosed) {
        $this->closed($disconnectedUser);
        socket_close($disconnectedUser->socket);
      }
      else {
        $message = $this->frame('', $disconnectedUser, 'close');
        @socket_write($disconnectedUser->socket, $message, strlen($message));
      }
    }
  }

  protected function doHandshake($user, $buffer) {
    $this->user = $user;
    $magicGUID = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
    $headers = array();
    $lines = explode("\n",$buffer);
    foreach ($lines as $line) {
      if (strpos($line,":") !== false) {
        $header = explode(":",$line,2);
        $headers[strtolower(trim($header[0]))] = trim($header[1]);
      }
      elseif (stripos($line,"get ") !== false) {
        preg_match("/GET (.*) HTTP/i", $buffer, $reqResource);
        $headers['get'] = trim($reqResource[1]);
      }
    }    
    if (isset($headers['get'])) {
      $user->requestedResource = $headers['get'];
    } 
    else {
      // todo: fail the connection
      $handshakeResponse = "HTTP/1.1 405 Method Not Allowed\r\n\r\n";     
    }
    if (!isset($headers['host']) || !$this->checkHost($headers['host'])) {
      $handshakeResponse = "HTTP/1.1 400 Bad Request";
    }
    if (!isset($headers['upgrade']) || strtolower($headers['upgrade']) != 'websocket') {
      $handshakeResponse = "HTTP/1.1 400 Bad Request";
    } 
    if (!isset($headers['connection']) || strpos(strtolower($headers['connection']), 'upgrade') === FALSE) {
      $handshakeResponse = "HTTP/1.1 400 Bad Request";
    }
    if (!isset($headers['sec-websocket-key'])) {
      $handshakeResponse = "HTTP/1.1 400 Bad Request";
    } 
    else {

    }
    if (!isset($headers['sec-websocket-version']) || strtolower($headers['sec-websocket-version']) != 13) {
      $handshakeResponse = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13";
    }
    if (($this->headerOriginRequired && !isset($headers['origin']) ) || ($this->headerOriginRequired && !$this->checkOrigin($headers['origin']))) {
      $handshakeResponse = "HTTP/1.1 403 Forbidden";
    }
    if (($this->headerSecWebSocketProtocolRequired && !isset($headers['sec-websocket-protocol'])) || ($this->headerSecWebSocketProtocolRequired && !$this->checkWebsocProtocol($headers['sec-websocket-protocol']))) {
      $handshakeResponse = "HTTP/1.1 400 Bad Request";
    }
    if (($this->headerSecWebSocketExtensionsRequired && !isset($headers['sec-websocket-extensions'])) || ($this->headerSecWebSocketExtensionsRequired && !$this->checkWebsocExtensions($headers['sec-websocket-extensions']))) {
      $handshakeResponse = "HTTP/1.1 400 Bad Request";
    }

    // Done verifying the _required_ headers and optionally required headers.

    if (isset($handshakeResponse)) {
      socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
      $this->disconnect($user->socket);
      return;
    }

    $user->headers = $headers;
    $user->handshake = $buffer;

    $webSocketKeyHash = sha1($headers['sec-websocket-key'] . $magicGUID);

    $rawToken = "";
    for ($i = 0; $i < 20; $i++) {
      $rawToken .= chr(hexdec(substr($webSocketKeyHash,$i*2, 2)));
    }
    $handshakeToken = base64_encode($rawToken) . "\r\n";

    $subProtocol = (isset($headers['sec-websocket-protocol'])) ? $this->processProtocol($headers['sec-websocket-protocol']) : "";
    $extensions = (isset($headers['sec-websocket-extensions'])) ? $this->processExtensions($headers['sec-websocket-extensions']) : "";

    $handshakeResponse = "HTTP/1.1 101 Switching Protocols\r\nUpgrade: websocket\r\nConnection: Upgrade\r\nSec-WebSocket-Accept: $handshakeToken$subProtocol$extensions\r\n";
    socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
    $this->connected($user);
  }

  protected function checkHost($hostName) {
    return true; // Override and return false if the host is not one that you would expect.
                 // Ex: You only want to accept hosts from the my-domain.com domain,
                 // but you receive a host from malicious-site.com instead.
  }

  protected function checkOrigin($origin) {
    return true; // Override and return false if the origin is not one that you would expect.
  }

  protected function checkWebsocProtocol($protocol) {
    return true; // Override and return false if a protocol is not found that you would expect.
  }

  protected function checkWebsocExtensions($extensions) {
    return true; // Override and return false if an extension is not found that you would expect.
  }

  protected function processProtocol($protocol) {
    return ""; // return either "Sec-WebSocket-Protocol: SelectedProtocolFromClientList\r\n" or return an empty string.  
           // The carriage return/newline combo must appear at the end of a non-empty string, and must not
           // appear at the beginning of the string nor in an otherwise empty string, or it will be considered part of 
           // the response body, which will trigger an error in the client as it will not be formatted correctly.
  }

  protected function processExtensions($extensions) {
    return ""; // return either "Sec-WebSocket-Extensions: SelectedExtensions\r\n" or return an empty string.
  }

  protected function getUserBySocket($socket) {
    foreach ($this->users as $user) {
      if ($user->socket == $socket) {
        return $user;
      }
    }
    return null;
  }

  public function stdout($message) {
    if ($this->interactive) {
      echo "$message\n";
    }
  }

  public function stderr($message) {
    if ($this->interactive) {
      echo "$message\n";
    }
  }

  protected function frame($message, $user, $messageType='text', $messageContinues=false) {    
    switch ($messageType) {
      case 'continuous':
        $b1 = 0;
        break;
      case 'text':
        $b1 = ($user->sendingContinuous) ? 0 : 1;
        break;
      case 'binary':
        $b1 = ($user->sendingContinuous) ? 0 : 2;
        break;
      case 'close':
        $b1 = 8;
        break;
      case 'ping':
        $b1 = 9;
        break;
      case 'pong':
        $b1 = 10;
        break;
    }
    if ($messageContinues) {
      $user->sendingContinuous = true;
    } 
    else {
      $b1 += 128;
      $user->sendingContinuous = false;
    }

    $length = strlen($message);        
    $lengthField = "";
    if ($length < 126) {
      $b2 = $length;
    } 
    elseif ($length <= 65536) {
      $b2 = 126;
      $hexLength = dechex($length);
      //$this->stdout("Hex Length: $hexLength");
      if (strlen($hexLength)%2 == 1) {
        $hexLength = '0' . $hexLength;
      } 
      $n = strlen($hexLength) - 2;

      for ($i = $n; $i >= 0; $i=$i-2) {
        $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
      }
      while (strlen($lengthField) < 2) {
        $lengthField = chr(0) . $lengthField;
      }
    } 
    else {
      $b2 = 127;
      $hexLength = dechex($length);
      if (strlen($hexLength)%2 == 1) {
        $hexLength = '0' . $hexLength;
      } 
      $n = strlen($hexLength) - 2;

      for ($i = $n; $i >= 0; $i=$i-2) {
        $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
      }
      while (strlen($lengthField) < 8) {
        $lengthField = chr(0) . $lengthField;
      }
    }    
    return chr($b1) . chr($b2) . $lengthField . $message;
  }
  
  //check packet if he have more than one frame and process each frame individually
  protected function split_packet($length,$packet, $user) {
    //add PartialPacket and calculate the new $length
    if ($user->handlingPartialPacket) {
      $packet = $user->partialBuffer . $packet;
      $user->handlingPartialPacket = false;
      $length=strlen($packet);
    }
    $fullpacket=$packet;
    $frame_pos=0;
    $frame_id=1;

    while($frame_pos<$length) {
      $headers = $this->extractHeaders($packet);
      $headers_size = $this->calcoffset($headers);
      $framesize=$headers['length']+$headers_size;
      
      //split frame from packet and process it
      $frame=substr($fullpacket,$frame_pos,$framesize);

      if (($message = $this->deframe($frame, $user,$headers)) !== FALSE) {
        if ($user->hasSentClose) {
          $this->disconnect($user->socket);
        } else {
          if (preg_match('//u', $message)) {
            //$this->stdout("Is UTF-8\n".$message); 
            $this->process($user, $message);
          } else {
            $this->stderr("not UTF-8\n");
          }
        }
      } 
      //get the new position also modify packet data
      $frame_pos+=$framesize;
      $packet=substr($fullpacket,$frame_pos);
      $frame_id++;
    }
  }

  protected function calcoffset($headers) {
    $offset = 2;
    if ($headers['hasmask']) {
      $offset += 4;
    }
    if ($headers['length'] > 65535) {
      $offset += 8;
    } elseif ($headers['length'] > 125) {
      $offset += 2;
    }
    return $offset;
  }

  protected function deframe($message, &$user) {
    //echo $this->strtohex($message);
    $headers = $this->extractHeaders($message);
    $pongReply = false;
    $willClose = false;
    switch($headers['opcode']) {
      case 0:
      case 1:
      case 2:
        break;
      case 8:
        // todo: close the connection
        $user->hasSentClose = true;
        return "";
      case 9:
        $pongReply = true;
      case 10:
        break;
      default:
        //$this->disconnect($user); // todo: fail connection
        $willClose = true;
        break;
    }

    /* Deal by split_packet() as now deframe() do only one frame at a time.
    if ($user->handlingPartialPacket) {
      $message = $user->partialBuffer . $message;
      $user->handlingPartialPacket = false;
      return $this->deframe($message, $user);
    }
    */
    
    if ($this->checkRSVBits($headers,$user)) {
      return false;
    }

    if ($willClose) {
      // todo: fail the connection
      return false;
    }

    $payload = $user->partialMessage . $this->extractPayload($message,$headers);

    if ($pongReply) {
      $reply = $this->frame($payload,$user,'pong');
      socket_write($user->socket,$reply,strlen($reply));
      return false;
    }
    if (extension_loaded('mbstring')) {
      if ($headers['length'] > mb_strlen($this->applyMask($headers,$payload))) {
        $user->handlingPartialPacket = true;
        $user->partialBuffer = $message;
        return false;
      }
    } 
    else {
      if ($headers['length'] > strlen($this->applyMask($headers,$payload))) {
        $user->handlingPartialPacket = true;
        $user->partialBuffer = $message;
        return false;
      }
    }

    $payload = $this->applyMask($headers,$payload);

    if ($headers['fin']) {
      $user->partialMessage = "";
      return $payload;
    }
    $user->partialMessage = $payload;
    return false;
  }

  protected function extractHeaders($message) {
    $header = array('fin'     => $message[0] & chr(128),
            'rsv1'    => $message[0] & chr(64),
            'rsv2'    => $message[0] & chr(32),
            'rsv3'    => $message[0] & chr(16),
            'opcode'  => ord($message[0]) & 15,
            'hasmask' => $message[1] & chr(128),
            'length'  => 0,
            'mask'    => "");    
    
    $header['length'] = (ord($message[1]) >= 128) ? ord($message[1]) - 128 : ord($message[1]);
    
    if ($header['length'] == 126) {
      if ($header['hasmask']) {
        $header['mask'] = $message[4] . $message[5] . $message[6] . $message[7];
      }
      $header['length'] = ord($message[2]) * 256 
                + ord($message[3]);
    } 
    elseif ($header['length'] == 127) {
      if ($header['hasmask']) {
        $header['mask'] = $message[10] . $message[11] . $message[12] . $message[13];
      }
      $header['length'] = ord($message[2]) * 65536 * 65536 * 65536 * 256 
                + ord($message[3]) * 65536 * 65536 * 65536
                + ord($message[4]) * 65536 * 65536 * 256
                + ord($message[5]) * 65536 * 65536
                + ord($message[6]) * 65536 * 256
                + ord($message[7]) * 65536 
                + ord($message[8]) * 256
                + ord($message[9]);
    } 
    elseif ($header['hasmask']) {
      $header['mask'] = $message[2] . $message[3] . $message[4] . $message[5];
    }
    //echo $this->strtohex($message);
    //$this->printHeaders($header);    
    return $header;
  }

  protected function extractPayload($message,$headers) {
    $offset = 2;
    if ($headers['hasmask']) {
      $offset += 4;
    }
    if ($headers['length'] > 65535) {
      $offset += 8;
    } 
    elseif ($headers['length'] > 125) {
      $offset += 2;
    }
    return substr($message,$offset);
  }

  protected function applyMask($headers,$payload) {
    $effectiveMask = "";
    if ($headers['hasmask']) {
      $mask = $headers['mask'];
    } 
    else {
      return $payload;
    }

    while (strlen($effectiveMask) < strlen($payload)) {
      $effectiveMask .= $mask;
    }
    while (strlen($effectiveMask) > strlen($payload)) {
      $effectiveMask = substr($effectiveMask,0,-1);
    }    
    return $effectiveMask ^ $payload;
  }
  protected function checkRSVBits($headers,$user) { // override this method if you are using an extension where the RSV bits are used.
    if (ord($headers['rsv1']) + ord($headers['rsv2']) + ord($headers['rsv3']) > 0) {
      //$this->disconnect($user); // todo: fail connection
      return true;
    }
    return false;
  }

  protected function strtohex($str) {
    $strout = "";
    for ($i = 0; $i < strlen($str); $i++) {
      $strout .= (ord($str[$i])<16) ? "0" . dechex(ord($str[$i])) : dechex(ord($str[$i]));
      $strout .= " ";
      if ($i%32 == 7) {
        $strout .= ": ";
      }
      if ($i%32 == 15) {
        $strout .= ": ";
      }
      if ($i%32 == 23) {
        $strout .= ": ";
      }
      if ($i%32 == 31) {
        $strout .= "\n";
      }
    }
    return $strout . "\n";
  }

  protected function printHeaders($headers) {
    $this->writelog("Array\n(\n");
    foreach ($headers as $key => $value) {
      if ($key == 'length' || $key == 'opcode') {
        $this->writelog("\t[$key] => $value\n\n");
      } 
      else {
        $this->writelog("\t[$key] => ".$this->strtohex($value)."\n");
      }
    }
    $this->writelog(")\n");
  }

  protected function writelog($info){
    //$file = fopen("C:\\xampp\\htdocs\\intertv\\remates\\fotos\\log.txt", "a");    
    $file = fopen("/home/develhard/public_html/brandemann/fotos/log.txt", "a");
    //$file = fopen("/home/develhard/public_html/intertv/remates/fotos/log.txt", "a");
    //$file = fopen("/opt/lampp/htdocs/intertv-v0.2/remates/fotos/log.txt", "a");
    
    fwrite($file, $info);
    fclose($file); 
  }
}