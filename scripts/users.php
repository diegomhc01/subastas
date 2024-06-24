<?php
include('classConeccion.php');
class WebSocketUser {

  public $socket;
  public $id;
  public $headers = array();
  public $handshake = false;

  public $handlingPartialPacket = false;
  public $partialBuffer = "";

  public $sendingContinuous = false;
  public $partialMessage = "";
  
  public $hasSentClose = false;

  public $idusuario = -1;
  public $usuario = '';
  public $apellido = '';
  public $nombre = '';
  public $perfil = -1;
  public $operador = '';
  public $operadorchat = '';
  public $idfirma = -1;
  public $estadocliente = 0;
  public $idremate = -1;
  public $idlote = -1;


  function __construct($id, $socket) {
    $this->id = $id;
    $this->socket = $socket;
  }

  public function buscarusuario($id){
    Coneccion::conectar();
      $sql = "SELECT u.usuario, u.perfil, u.apellido, u.nombre, u.idfirma, u.idusuario ";
      $sql .= "FROM usuarios u, uconectados uc ";
      $sql .= "WHERE u.usuario = uc.usuario and md5(uc.idsesion) =  '$id'";      
    $rs = mysql_query($sql);
    if(mysql_num_rows($rs)>0){
      $ufila = mysql_fetch_row($rs);
      $sql = "SELECT uc.operador FROM uconectados uc WHERE uc.usuario = '".$ufila[0]."'";
      $rs1 = mysql_query($sql);
      if(mysql_num_rows($rs1)>0){
        $uconectado = mysql_fetch_row($rs1);
        $this->operador = $uconectado[0];
      }
      $this->usuario = $ufila[0];
      $this->perfil = $ufila[1];
      $this->apellido = $ufila[2];
      $this->nombre = $ufila[3];
      $this->idfirma = $ufila[4];
      $this->idusuario = $ufila[5];
    }
    mysql_close();
  }
}