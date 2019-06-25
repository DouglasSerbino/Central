<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Sistema de Seguimiento Corporativo - CentralGraphics" />
	<!-- meta name="codename" content="Fenix, Chicken Run" /-->
	<meta name="author" content="Daniel Echeverria y Marvin Pocasangre" />
	<title><?=$Titulo_Pagina?> - CentralGraphics</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<link rel="stylesheet" href="/html/css/estilo.002.css" />
</head>
<body>
	
	<div id="contenedor">
<?
//Quien realiza la requisicion
$Departamento = '';
$Costo = '';
$Fecha_Salida = '';
$Num_requisicion = '';
if($Requisicion > 0)
{
	foreach($Requisicion as $Datos_requisicion)
	{
		$Departamento = $Datos_requisicion['departamento'];
		$Costo = $Datos_requisicion['codigo'];
		$Fecha_Salida = $Datos_requisicion['fecha_salida'];
		$Num_requisicion = $Datos_requisicion['numero_requ'];
	}
}

?>
<style>
	table{
		width: 100%;
	}
	.bordes td, .bordes th{
		border: 1px solid #000;
	}
</style>
<br /><br /><br /><br /><br />
<div class="informacion">
	
	<div class="informacion_cont">
		
		<img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" alt="<?=$this->session->userdata('nombre_grupo')?>" alt="Logo CentralGraphics" />
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		<strong>REQUISICION DE MATERIALES &nbsp; - &nbsp; N&deg; <?=$Num_requisicion?></strong>
		
		<br style="clear: both;" /><br />
		<table>
			<tr>
				<td style="width: 100px;">Departamento:</td>
				<td><?=$Departamento?> - <?=$Costo?></td>
				<td><strong>&#9711;</strong> SAP</td>
			</tr>
			<tr>
				<td>Fecha:</td>
				<td><?=$Fecha_Salida?></td>
				<td><strong>&#9711;</strong> CARGA WEB</td>
			</tr>
		</table>
		
		<br />
		
		<table class="bordes">
			<tr>
				<th>C&Oacute;D. SAP</th>
				<th>ESPECIFICACIONES</th>
				<th>CANTIDAD</th>
				<th>REFERENCIA</th>
			</tr>
<?
//Materiales de la requisicion
if($Materiales > 0)
{
	foreach($Materiales as $Datos_materiales)
	{
?>
			<tr>
				<td><?=$Datos_materiales['codigo_sap']?></td>
				<td><?=$Datos_materiales['nombre_material']?></td>
				<td><?=$Datos_materiales['cantidad'].' '.$Datos_materiales['tipo']?></td>
				<td>&nbsp;</td>
			</tr>
<?
	}
}
?>
		</table>
		
		<br />
		<strong>Trabajo:</strong>
		<span style="display: inline-block; width: 450px; border-bottom: 1px solid #000;"></span>
		
		<br /><br /><br />
		
		<table>
			<tr>
				<td style="width: 26%; border-bottom: 1px solid #000000;">&nbsp;</td>
				<td>&nbsp;</td>
				<td style="width: 26%; border-bottom: 1px solid #000000;">&nbsp;</td>
				<td>&nbsp;</td>
				<td style="width: 26%; border-bottom: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<th style="text-align: center;">Autorizado</th>
				<th>&nbsp;</th>
				<th style="text-align: center;">Entregado</th>
				<th>&nbsp;</th>
				<th style="text-align: center;">Recibido</th>
			</tr>
		</table>
		
		
	</div>
	
	<div id="controles_impr">
		<input type="button" class="boton" id='imprimir' value="Imprimir Hoja" onclick="window.print();" />
	</div>
	
</div>