<?php
	session_start();
	$arr = array();
	$arr['sessionid']=md5(session_id());
	echo json_encode(array('s'=>$arr));
?>