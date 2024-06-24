<?php
include_once('../tcpdf/config/tcpdf_config.php'); 
include_once('../tcpdf/tcpdf.php');  
	
if(isset($arrr) && isset($arrh)){	
	$fecha = $arrr['fecha'];
	$hora = $arrr['hora'];
	$titulo = $arrr['titulo'];
	$dia = date("d", strtotime($arrr['fecha']));
	switch(date("n", strtotime($arrr['fecha']))){
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
	$anio = date("Y", strtotime($arrr['fecha']));
	//$anio = $fecha('Y');
	$fecha = "$dia de $mes de $anio";
	$fechalarga = "$dia dias del mes de $mes del año $anio";

	$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);  
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
  
  	//$pdf->Addpage();  
  	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  	
  	
	$cantidad = count($arrh);	
	$nrolote = 0;	
	for($i=0;$i<$cantidad;$i++){
			$haciendaarr = $arrh[$i];			
			if($nrolote!=$haciendaarr[1]){
				if(isset($html)){					
					$pdf->startPageGroup();
					$pdf->AddPage();
					$pdf->writeHTML($html,false,true, false, false,''); 
				}
				$nrolote=$haciendaarr[1];
				$html = '<html>
					<head>
					</head>
					<body>
					<table>
						<tr><td style="font-size:11px;text-align:center;width:205px;height:20px;">'.$titulo.'</td><td></td><td></td><td></td><td style="font-size:11px;text-align:center;width:205px;height:20px;">'.$fecha.'</td><td></td><td></td><td></td><td></td><td></td><td>'.$hora.'</td></tr>
						<tr><td></td></tr>
					</table>
				<table>
				<tbody>
				<tr>
					<td style="font-size:18px;">Lote N°</td><td style="font-size:18px;">'.$haciendaarr[1].'</td>
					<td></td><td></td>
				</tr>
				<tr>
					<td style="border-top:1px solid #000000;font-size:11px;">Cabezas</td><td style="border-top:1px solid #000000;font-size:11px;text-align:right;"><pre>'.$haciendaarr[2].' </pre></td>
					<td style="border-top:1px solid #000000;font-size:11px;border-left:1px solid #000000;"> Precio Venta</td><td style="border-top:1px solid #000000;"></td>					
				</tr>
				<tr>
					<td style="font-size:11px;border-top:1px solid #000000;">Precio Inicio</td><td style="font-size:11px;border-top:1px solid #000000;text-align:right"><pre>$ '.trim($haciendaarr[3]).' </pre></td>
					<td style="border-left:1px solid #000000;"></td><td></td>
				</tr>
				<tr>
					<td style="font-size:11px;border-top:1px solid #000000;">Plazo</td><td style="border-top:1px solid #000000;font-size:11px;text-align:right;"><pre>'.$haciendaarr[4].' </pre></td>
					<td style="font-size:11px;border-top:1px solid #000000;border-left:1px solid #000000;"> Comprador</td><td style="border-top:1px solid #000000;"></td>
				</tr>
				<tr><td></td><td></td><td style="border-left:1px solid #000000;"></td><td></td></tr>
				<tr><td style="border-top:1px solid #000000;font-size:14px;"><strong>DATOS DE HACIENDA</strong></td><td style="border-top:1px solid #000000;"></td>
				<td style="border-top:1px solid #000000;"></td><td style="border-top:1px solid #000000;"></td></tr>
				</tbody>
				</table>';
			}
	//style="font-size:11px;text-align:center;width:35px;height:20px;"
		$html .= '
		<table>
			<tbody>';
				$html .= '<tr>';
				$html .= '<td style="border-top:1px solid #000000;">Categor&iacute;a</td>';
				$html .= '<td style="border-top:1px solid #000000;">'.$haciendaarr[10].'</td>';
				$html .= '<td style="border-top:1px solid #000000;">Calidad</td>';
				$html .= '<td style="border-top:1px solid #000000;">'.$haciendaarr[14].'</td>';
				$html .= '<td style="border-top:1px solid #000000;">Localidad</td>';
				$html .= '<td style="border-top:1px solid #000000;">'.$haciendaarr[6].'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
				$html .= '<td>Raza/Cruza</td>';
				$html .= '<td>'.$haciendaarr[9].'</td>';
				$html .= '<td>Estado</td>';
				$html .= '<td>'.$haciendaarr[13].'</td>';
				$html .= '<td>Provincia</td>';
				$html .= '<td>'.$haciendaarr[7].'</td>';
				$html .= '</tr>';

				$html .= '<tr>';
				$html .= '<td>Pelaje</td>';
				$html .= '<td>'.$haciendaarr[12].'</td>';
				$html .= '<td>Sanidad</td>';
				$html .= '<td>'.$haciendaarr[16].'</td>';
				$html .= '<td></td>';
				$html .= '<td></td>';
				$html .= '</tr>';

				$html .= '<tr>';
				$html .= '<td>Tipo Precio</td>';
				$html .= '<td>'.$haciendaarr[19].'</td>';
				$html .= '<td>Uniformidad</td>';
				$html .= '<td>'.$haciendaarr[17].'</td>';
				$html .= '<td>Evaluador</td>';
				$html .= '<td>'.$haciendaarr[5].'</td>';			
				$html .= '</tr>';

				$html .= '<tr>';
				$html .= '<td>Trazados</td>';
				$html .= '<td>'.$haciendaarr[18].'</td>';
				$html .= '<td></td>';
				$html .= '<td></td>';
				$html .= '<td>Vendedor</td>';
				$html .= '<td>'.$haciendaarr[8].'</td>';			
				$html .= '</tr>';
				
				$html .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
				$html .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
				$html .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
				$html .= '</tbody></table></body></html>';
	}

  	$archivo = 'catalogocr_'.time().'.pdf';
  	$url = '../reportes/'.$archivo;
  	$pdf->Output($url,'F');  
  	chmod($url, 0750);  	

}
?>