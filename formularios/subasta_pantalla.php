<div id="subasta">
	<?php	
		include('scripts/macro.php');
		$macro = new Macro();
		$macro->buscarremateabierto();		
		if(isset($_SESSION['stiporemate'])){
			if($_SESSION['stiporemate']==1){
				include('formularios/videoflash.php');
			}
			if($_SESSION['stiporemate']==2 || $_SESSION['stiporemate']==3){
				include('formularios/videoflash.php');				
			}				
		}
	?>
</div>



