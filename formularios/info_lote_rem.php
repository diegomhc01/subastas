<div id="infolote">
	<?php 
		if(isset($_SESSION['sperfil'])){
			if($_SESSION['sperfil']==2){
				echo '<div id="infolote_rem"></div>';
				echo '<input type="button" id="btnabrir" value="Abrir Lote" style="display:none;">';
			}
			/*
			if($_SESSION['sperfil']==1){				
				echo '<div id="infolote_cli"></div>';				
				
				if($_SESSION['stiporemate']==1){

					echo '<div id="sonido_remate">';
					echo '	<div id="rematador_audio">';
					echo '			<object type="application/x-shockwave-flash" ';
					//echo '			data="http://videostreaminghd.hdstreamhost.com/players/jwplayerV6/jwplayer/jwplayer.flash.swf" ';
					echo '			data="http://videostreaminghd.hdstreamhost.com/players/jwp7/jwplayer.flash.swf" ';
					echo '			width="390px" height="10px" style="float:left;" bgcolor="#000000" ';
					echo '			id="myElement" name="myElement"  tabindex="0">';
					echo '			<param name="allowfullscreen" value="false">';
					echo '			<param name="allowscriptaccess" value="always">';
					echo '			<param name="seamlesstabbing" value="true">';
					echo '			<param name="wmode" value="opaque"></object>';
					echo '	</div>';
					echo '</div>';
				}
				*/
			if($_SESSION['sperfil']==1){				
				echo '<div id="infolote_cli"></div>';					
				if($_SESSION['stiporemate']==1){
					echo '<iframe src="http://source.bustream.com/player/video/brandem2/" height="0" width="0"></iframe>';
						/*echo '<object type="application/x-shockwave-flash" ';
						echo 'data="http://videostreaminghd.hdstreamhost.com/players/jwp7/jwplayer.flash.swf" ';
						echo 'width="640" height="1" bgcolor="#000000" id="player_swf_0" ';
						echo 'name="player_swf_0" ';
						echo 'class="jw-swf jw-reset" '; 
						echo 'style="display: block; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"> ';
						echo '<param name="allowfullscreen" value="true"> ';
						echo '<param name="allowscriptaccess" value="always"> ';
						echo '<param name="wmode" value="opaque"> ';
						echo '<param name="menu" value="false">';
						echo '</object>';
						*/
					}								
				echo '<div id="infolote_cre"></div>';
				echo '<input type="button" id="btnsolicitarcredito" name="btnsolicitarcredito" value="Solicitar Credito">';
				echo '<input type="button" id="btnvercredito" name="btnvercredito" value="Ver Credito">';
			}
			if($_SESSION['sperfil']==3 || $_SESSION['sperfil']==4){
				echo '<div id="infolote_ope"></div>';
			}
			if($_SESSION['sperfil']==7){
				echo '<div id="infolote_cli"></div>';
				if($_SESSION['stiporemate']==1){
					echo '<iframe src="http://source.bustream.com/player/video/brandem2/" height="0" width="0"></iframe>';
				} 
			}
		}
	 ?>
</div>