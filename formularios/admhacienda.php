<?php
    include('scripts/validar.php');
    $mensaje = '';  
    if(isset($_POST['multimediagrabar'])){
        if($_POST['multimediagrabar']=='Grabar'){
            if(isset($_FILES['archivo'])){                
                $i = 0;
                foreach ($_FILES["archivo"]["error"] as $key => $error) {
                    if($error == UPLOAD_ERR_OK){                   
                        $tipo = $_FILES["archivo"]["type"][$key];
                        $tamanio = $_FILES["archivo"]["size"][$key];
                        if(substr($tipo, strpos($tipo, "jpeg"))=='jpeg') {
                            if($tamanio < 10000000){
                                $tmp_name = $_FILES["archivo"]["tmp_name"][$key];
                                $name = $_FILES["archivo"]["name"][$key];
                                $ext = pathinfo($name, PATHINFO_EXTENSION);
                                $name =  md5((string)microtime().$name).'.'.$ext;                                
                                //if(move_uploaded_file($tmp_name, '/home/intertvc/public_html/brandemann/fotos/'.$name)){ 
                                //if(move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].'/intertv/remates/fotos/'.$name)){   
                                if(move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].'/brandemann/fotos/'.$name)){   
                                    sleep(3);
                                    $_SESSION['simagen'] = $name;                                    
                                    //include('scripts/redimi.php');
                                    include('scripts/abmimagen.php');
                                    $i++;
                                }
                            }else{
                                $mensaje .= "EL ARCHIVO ES DEMASIADO GRANDE";
                            }
                        }else{
                            $mensaje .= 'NO ES DEL TIPO DE ARCHIVO CORRECTO';
                        }
                    }else{
                        //$mensaje .= $error;
                    } 
                }
                if(isset($_POST['video'])){
                    if($_POST['video']!=''){
                        $_SESSION['svideo'] = $_POST['video'];
                        include('scripts/abmvideo.php');
                    }
                }
            }else{
                $mensaje = 'NO HAY VALORES DE LAS IMAGENES';            
            }            
        }
    }
?>
<div class="col-xs-12">
    <div class="col-xs-11"> 
        <form role="form" id="formcheck">            
            <label for="chkasignar">Para asignar</label>
            <input type="checkbox" name="chkasignar" id="chkasignar" class="chkhacienda" value="1" checked="checked">
            <label for="chkrematar">Para rematar</label>
            <input type="checkbox" name="chkrematar" id="chkrematar" class="chkhacienda" value="1">
            <label for="chkrematadas">Rematadas</label>
            <input type="checkbox" name="chkrematadas" id="chkrematadas" class="chkhacienda" value="1">
        </form>
    </div>
    <div class="col-xs-1">
        <button type="button" class="btn btn-default" name="btnaddhacienda" id="btnaddhacienda"
        data-toggle="modal" data-target="haciendaModal">Agregar</button>
    </div>
</div>
<div class="col-xs-12" id="mensaje"><?php echo $mensaje; ?></div>
<table id="thacienda" class="display" cellpadding="0"  width="100%">

    <tbody></tbody>
</table>
<div id="haciendaModal" class="modal fade" role="dialog">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Datos de Hacienda</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li id="lievaluador" class="active"><a data-toggle="tab" href="#divevaluador">Evaluador</a></li>
                    <li id="livendedor"><a data-toggle="tab" href="#divvendedor">Vendedor</a></li>
                    <li id="lihacienda"><a data-toggle="tab" href="#divhacienda0">Hacienda</a></li>
                    <li id="liconvta"><a data-toggle="tab" href="#divcondvta">Cond de Venta</a></li>
                    <li id="lipesadai"><a data-toggle="tab" href="#divformpesajei">P Inspecci&oacute;n</a></li>
                    <li><a data-toggle="tab" href="#divformpesajed">P Definitiva</a></li>
                    <li><a data-toggle="tab" href="#divsanidad">Sanidad</a></li>
                    <li><a data-toggle="tab" href="#divmadres">Vientres</a></li>
                    <li><a data-toggle="tab" href="#divevaluacion">Evaluaci&oacute;n</a></li>
                </ul>
                <form role="form" id="formhacienda">
                    <div class="tab-content">
                    <?php
                        include('admevaluador.php');
                        include('admvendedor.php');
                        include('admhacienda0.php');
                        include('admcondvta.php');
                        include('admpesadai.php');
                        include('admpesadad.php');
                        include('admsanidad.php');
                        include('admmadres.php');
                        include('admevaluacion.php');
                    ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-default" id="btngrabarhacienda">Grabar</button>
            </div>            
        </div>
    </div>
</div>
<div id="divmultimedia" style="display:none;">
    <form enctype="multipart/form-data" action="" method="POST" name="formularioi" id="formularioi" class="formularioimg">
        <div class="multimedia">
            <input type="file" id="archivo1" name="archivo[]" multiple="true">
        </div>
        <div class="multimedia">
            <label for="video">Video</label>
            <input type="text" id="video" name="video">
        </div>
        <div id="fotosvideo">
            <div id="imagen1"><img width="190" height="190" class="fotosvideoimg"></div>
            <div id="imagen2"><img width="190" height="190" class="fotosvideoimg"></div>
            <div id="imagen3"><img width="190" height="190" class="fotosvideoimg"></div>
            <div id="imagen4"><img width="190" height="190" class="fotosvideoimg"></div>
        </div>
        <div id="mensaje_img">
            <p>Las im&aacute;genes deben ser con extensi&oacute;n jpg</p>
            <p>El tama&ntilde;o m&aacute;ximo de los archivos no pueden superar los 10 MB</p>
            <p>No puede subir m&aacute;s de 4 fotos</p>
            <p>De la direcci&oacute;n de yotube, debe ingresar solo el c&oacute;digo como el que est&aacute; resaltado</p>
            <p>https://www.youtube.com/watch?v=<span>4J7gIlffArM</span></p>
        </div>
        <div id="videoh"></div>
        <div class="messages"></div><br>
        <div class="showImage"></div>

        <div id="divmultimediasalir">
            <input type="submit" id="multimediagrabar" name="multimediagrabar" value="Grabar">
            <input type="submit" id="multimediasalir" name="multimediasalir" value="Cancelar">
        </div>
    </form>
</div>