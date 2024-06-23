	<?php
		if($_SESSION['sperfil']==2){
	?>
		<div id="chatrem">
			<div id="chat_rem" >
				<div id="chat">
					<div id="charla"></div>
				</div>
			</div>
			<div id="oferta_rem" style="display:none;">
				<div id="datosloteabiertorem"></div>
				<label for="txtoferente">Oferta ganadora</label>
				<input type="text" name="txtoferente" id="txtoferente" value="">
				
				<input type="text" name="txtmontor" id="txtmontor" value="">
			</div>
		</div>
	<?php			
		}else{			
			if($_SESSION['sperfil']==3 || $_SESSION['sperfil']==4){
			?>
				<audio id="not_audio"><source src="sonidos/notmsg.mp3" type="audio/mpeg"></audio>
				<div id="chat" style="height: 340px;">				
				<div id="charla" style="height: 305px;"></div>
				<div id="usuarios" style="height: 280px;"></div>
				<div id="totalconectados"></div>
				<div id="mensaje">		
					<input type="text" name="txtmsg" id="txtmsg" value="">
					<input type="button" name="btnmsg" id="btnmsg" value="Enviar">		
				</div>
				</div>
				<div id="msgtodos" style="display:none;">
					<p>Desea enviar el mensaje a todos los clientes ?</p>
				</div>
			<?php
			}else{
			?>
				<audio id="not_audio"><source src="sonidos/notmsg.mp3" type="audio/mpeg"></audio>
				<div id="chat">
					<div id="charla"></div>
					<div id="usuarios"><ul></ul></div>
					<div id="mensaje">		
						<input type="text" name="txtmsg" id="txtmsg" value="">
						<input type="button" name="btnmsg" id="btnmsg" value="Enviar">		
					</div>
				</div>
			<?php
			}
		}
	?>	
<div id="msgerror" style="display:none;"></div>