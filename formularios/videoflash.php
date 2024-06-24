		<?php
		echo '<!--<object type="application/x-shockwave-flash" id="UstreamViewer" name="UstreamViewer" 
			 	data="http://static-cdn1.ustream.tv/swf/live/viewer:274.swf?vrsl=c:634&amp;amp;ulbr=100"
				<param name="wmode" value="direct">
				<param name="allowfullscreen" value="false">
				<param name="bgcolor" value="#000000">
				<param name="allowscriptaccess" value="always">
				<param name="flashvars" value="cid=17992693&amp;locale=es_ES&amp;sessionid=false&amp;autoplay=true&amp;enablejsapi=1&amp;sv=6&amp;ts=1399424464517">
			</object>

			
			-->';					
		if($_SESSION['sperfil']==2){
			echo '';
		}		
		if($_SESSION['stiporemate']==2 || $_SESSION['stiporemate']==3){
			?>
				<div id="myElement_wrapper" style="float:left;margin-left:4px; margin-top: 4px;" >
					<iframe src="http://source.bustream.com/player/video/brandem/" frameborder="0" scrolling="no" height="335" width="613"></iframe><!--  -->
				</div>
			
			<?php 
		}
		if($_SESSION['stiporemate']==1){
			?>
			<div id="videolote"></div>
				
			<?php 
		}
		?>		

			
		

			
