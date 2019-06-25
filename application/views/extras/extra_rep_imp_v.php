<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//En"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>REPORTE GENERAL DE HORAS EXTRAS</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<link href="/html/css/extra.css" rel="stylesheet" type="text/css" />
	<style>
.normal th, .normal td {
  border: 1px solid #000;
}
</style>
</head>

	<body>
		
		<div id="contenedor-pagina">
			
			<div id="encabezado"><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" alt="Logo" /></div>
			
			<div id="titulo" style='text-align: center;'>
				REPORTE GENERAL DE HORAS EXTRAS
				<div>Periodo: <?=$fecha1?> &nbsp; al &nbsp; <?=$fecha2?></div>
			</div>
			
			<div id="contenido">	
	
<?
$nombre_ant = "";
$total_horas = 0;

foreach($HExtras as $Datos_extras)
{
	$fecha = $Datos_extras["fecha"];
	$nombre = $Datos_extras["nombre"];
	$codigo_empleado = $Datos_extras["cod_empleado"];
	$inicio = $Datos_extras["inicio"];
	$fin_real = $Datos_extras["fin_real"];
	$fin = $Datos_extras["fin"];
	$total_h = $Datos_extras["total_h"];
	
	if($nombre != $nombre_ant)
	{
		if($nombre_ant != "")
		{
?>
			<tr style='font-size: 13px;'>
				<td>&nbsp;</td>
				<th>Total</th>
				<th style='text-align: left;'><?=number_format($total_horas, 2)?> horas</th>
			</tr>
		</table>
		<br>
<?php
			
			$total_horas = 0;
		}
?>	
			<strong><?=$codigo_empleado?> - <?=strtoupper($nombre)?></strong>
			<table style='width: 70%;'>
<?php
		$nombre_ant = $nombre;
	}
	
	$total_horas += $total_h;
?>
				<tr style='font-size: 12px; text-align: left;' >
					<td style='width: 30%;'><?=$fecha?></td>
					<td style='width: 30%;'>&nbsp; <?=$inicio?> a <?=$fin_real?></td>
					<td style='text-align: left;'><?=number_format($total_h, 2)?> horas</td>
				</tr>
<?php	
}
?>
				<tr style='font-size: 13px;'>
					<td>&nbsp;</td>
					<th>Total</th>
					<th style='text-align: left;'><?=number_format($total_horas, 2)?> horas</th>
				</tr>
			</table>
	<br /><br />
				<table class="tabla_centro">
					<tr>
						<td style="text-align: center;">
							<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u>
							<br />Otoniel Ruiz
						</td>
					</tr>
				</table>
			</div>
			<br>
			<br>
		</div>
	</body>
</html>