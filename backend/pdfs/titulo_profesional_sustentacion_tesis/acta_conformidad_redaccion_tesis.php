<?php
// Include the main TCPDF library (search for installation path).
require_once '../../librerias/tcpdf/tcpdf.php';
require_once '../../utils/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	/************************ VARIABLES DE FORMULARIO *********************/
	$expediente = json_decode($_POST['expediente']);
	$graduando = json_decode($_POST['graduando']);
	//$fecha_sustentacion = $_POST['fecha_sustentacion'];	
	$fecha_sustentacion = '2020-05-25';	
	$array_jurado = json_decode($_POST['jurados'], true);	
	//$idx_secreatario = array_search('secretario', array_column($array_jurado, 'tipo'));
	$idx_secreatario = array_search('secretario', array_map(function($element) {  return $element['tipo'];}, $array_jurado) );
	$secretario = $array_jurado[$idx_secreatario]['apn'];
	/**********************************************************************/

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'UNSA');

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);


	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	// set font
	$pdf->SetFont('helvetica', 'B', 12);
	// add a page
	$pdf->AddPage();
	
	$titulo = 'ACTA DE CONFORMIDAD DE REDACCIÓN DE TESIS<br>';
	
	$pdf->writeHTMLCell(150, '', 30, '', $titulo, 0, 0, 0, true, 'C', true); 	
	$pdf->Ln(); 		
	
	$pdf->setCellHeightRatio(2.5);	
	
	$texto = 'El que suscribe secretario del jurado <b>Mg./Dr. '.$secretario.'</b> ';
	$texto .= 'confirma mediante la presente que la redacción de la Tesis titulada <b>'.$expediente->titulo.'</b> ';
	$texto .= 'sustentado por el Señor(a) <b>'.$graduando->apell_nombres.'</b> ';
	$texto .= 'con fecha <b>'.date("d/m/Y", strtotime($fecha_sustentacion)).'</b> ';
	$texto .= 'cumple los requisitos de redacción y absolución de observaciones previstas durante la sustentación, ';
	$texto .= 'en cumplimiento  con las normas previstas por la <b>'.$expediente->facultad.'</b>, autorizando su registro en el repositorio digital.<br><br><br>';
	
	$pdf->SetFont('helvetica', '', 12);
	$pdf->writeHTMLCell(150, '', 30, '', $texto, 0, 0, 0, true, 'J', true); 
	$pdf->Ln(); 				
	$pdf->SetFont('helvetica','B',12);	
	$firma = '<span style="text-decoration:overline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SECRETARIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
	$pdf->writeHTMLCell(150, '', 30, '', $firma, 0, 0, 0, true, 'C', true); 
	$pdf->Ln();
	
	//$pdf->Output('Acta_conformidad_redaccion_tesis_'.$expediente->codigo.'.pdf', 'I');
	$pdf->Output('acta_conformidad_redaccion_tesis_'.$expediente->codigo.'.pdf', 'D');
}
else {
	echo "<h3>No tiene permiso de acceso para mostrar el archivo</h3>";
}
?>