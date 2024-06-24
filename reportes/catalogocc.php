<?php
include_once('../tcpdf/config/tcpdf_config.php'); 
include_once('../tcpdf/tcpdf.php');  
	
if(isset($arrr) && isset($arrh)){	
	$fecha = $arrr[0]['fecha'];
	$hora = $arrr[0]['hora'];
	$titulo = $arrr[0]['titulo'];
	$dia = date("d", strtotime($arrr[0]['fecha']));
	switch(date("n", strtotime($arrr[0]['fecha']))){
		case 1:
			$mes = 'Enero';
			break;
		case 2:
			$mes = 'Febrero';
			break;
		case 3:
			$mes = 'Marzo';
			break;
		case 4:
			$mes = 'Abril';
			break;
		case 5:
			$mes = 'Mayo';
			break;
		case 6:
			$mes = 'Junio';
			break;
		case 7:
			$mes = 'Julio';
			break;
		case 8:
			$mes = 'Agosto';
			break;
		case 9:
			$mes = 'Septiembre';
			break;
		case 10:
			$mes = 'Octubre';
			break;
		case 11:
			$mes = 'Noviembre';
			break;
		case 12:
			$mes = 'Diciembre';
			break;
	}	
	$anio = date("Y", strtotime($arrr[0]['fecha']));
	//$anio = $fecha('Y');
	$fecha = "$dia de $mes de $anio";
	$fechalarga = "$dia dias del mes de $mes del año $anio";

	$pdf = new TCPDF("L", "mm", "A4", true, 'UTF-8', false);  
	// set document information  
	$pdf->SetCreator(PDF_CREATOR);  
	$pdf->SetAuthor('Remates por Internet');  
	$pdf->SetTitle('catalogoc');
	$pdf->SetSubject('catalogoc');  
	$pdf->SetKeywords('catalogoc, Remates por Internet'); 

	// set default header data  
	//$pdf->SetHeaderData('logob.png','50');
  
	// set header and footer fonts  
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
  
	// set default monospaced font  
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);  
  
	//set margins  
	$pdf->SetMargins(PDF_MARGIN_LEFT, '15', PDF_MARGIN_RIGHT);  
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
  
	//set auto page breaks  
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);  
	$pdf->SetDisplayMode('real','default'); 	
  	$pdf->SetFont('Helvetica', '', '8');
  
  	$pdf->Addpage();  
  	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  	
  	$html = '<html>
<head>
</head>
<body>
<table>
	<tr><td style="font-size:11px;text-align:center;width:205px;height:20px;">'.$titulo.'</td><td></td><td></td><td></td><td style="font-size:11px;text-align:center;width:205px;height:20px;">'.$fecha.'</td><td></td><td></td><td></td><td></td><td></td><td>'.$hora.'</td></tr>
	<tr><td></td></tr>
</table>
<table border="1">
	<thead>
		<tr>
			<th align="center" style="font-size:11px;text-align:center;width:35px;height:20px;">Lote</th>
			<th align="center" style="font-size:11px;text-align:center;width:35px;height:20px;background-color:#CCCCCC;">Cab</th>
			<th align="center" style="font-size:11px;text-align:center;width:70px;height:20px;">Categoría</th>
			<th align="center" style="font-size:11px;text-align:center;width:35px;height:20px;">Traz</th>
			<th align="center" style="font-size:11px;text-align:center;width:45px;height:20px;">Kg/cab</th>
			<th align="center" style="font-size:11px;text-align:center;width:55px;height:20px;">$ Inicio</th>
			<th align="center" style="font-size:11px;text-align:center;width:105px;height:20px;">Localidad</th>
			<th align="center" style="font-size:11px;text-align:center;width:105px;height:20px;">Provincia</th>
			<th align="center" style="font-size:11px;text-align:center;width:35px;height:20px;">Plazo</th>
			<th align="center" style="font-size:11px;text-align:center;width:55px;height:20px;">$ Venta</th>
			<th align="center" style="font-size:11px;text-align:center;width:155px;height:20px;">Observaciones</th>
		</tr>
	</thead>
	<tbody>';
	$cantidad = count($arrh);
	for($i=0;$i<$cantidad;$i++){
		$haciendaarr = $arrh[$i];
		$html .= '<tr>';
		$html .= '<td style="font-size:11px;text-align:center;width:35px;height:20px;">'.$haciendaarr[1].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:35px;height:20px;background-color:#CCCCCC;">'.$haciendaarr[2].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:70px;height:20px;">'.$haciendaarr[3].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:35px;height:20px;">'.$haciendaarr[4].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:45px;height:20px;">'.$haciendaarr[5].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:55px;height:20px;">'.$haciendaarr[6].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:105px;height:20px;">'.$haciendaarr[7].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:105px;height:20px;">'.$haciendaarr[8].'</td>';
		$html .= '<td style="font-size:11px;text-align:center;width:35px;height:20px;">'.$haciendaarr[9].'</td>';
		$html .= '<td style="width:55px;height:20px;"></td>';
		$html .= '<td style="width:155px;height:20px;"></td>';
		$html .= '</tr>';
	}
$html .='</tbody>
</table>
</body>
</html>';
  	$pdf->writeHTML($html,false,true, false, false,''); 
  	$archivo = 'catalogoc_'.time().'.pdf';
  	$url = '../reportes/'.$archivo;
  	$pdf->Output($url,'F');  
  	chmod($url, 0750);  	
 }
?>