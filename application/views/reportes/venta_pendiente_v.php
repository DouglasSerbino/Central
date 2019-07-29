<?
echo '<?xml version="1.0" encoding="UTF-8"?>';
$meses_v = array('01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">

	<head>
		<title>REPORTE GERENCIAL</title>
		<link rel="shortcut icon" href="/html/img/ico-cg.png" />
		<link rel="stylesheet" href="/html/css/estilo.002.css" />
		<link href="/html/css/extra.css" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
		<br /><br /><br /><br /><br /><br />
		<div id="contenedor-pagina">
		<div id="encabezado" class='soy_encabezado'>
			<center>REPORTE GENERAL DE PEDIDOS SIN FACTURAR &nbsp; <?=$meses_v[$mes]?>-<?=$anho?></center>
			<img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" width="125" alt="<?=$this->session->userdata('grupo')?>" />
		</div>
		<div id="contenido">
		
<?
//print_r($procesos);
$suma = 0;
foreach($procesos as $Informacion)
{
	$a = 0;
	$suma_cli = 0;
	$id_cliente = $Informacion["id_cliente"];
		if($Id_Cliente != "" && $id_cliente != $Id_Cliente)
		{
			continue;
		}
?>
	<table style="width:80%;">
		<tr>
			<td colspan='4'><strong><br />&raquo; <?=$Informacion['nombre_cliente']?></strong></td>
		</tr>
<?php
	foreach($Informacion['procesos'] as $fila2)
	{
		$suma = $suma + $fila2["venta"];
		$suma_cli = $suma_cli + $fila2["venta"];
		$procesando = " (*)";
		if($fila2['fecha_reale'] != '0000-00-00')
		{
			$procesando = "";
		}
		
		
		$a++;
?>
		<tr>
			<td style='width: 40%;'><?=$fila2["sap"]?> [<?=$fila2['codigo_cliente']?>-<?=$fila2['proceso']?>]</td>
			<td style='width: 30%;'><?=date('d-m-Y', strtotime($fila2["fecha"]))?></td>
			<td>$ <?=$fila2["venta"]?> <?=$procesando?></td>
		</tr>
<?php	
	}
?>
		<tr>
			<td colspan="2"><strong>Total Cliente: &nbsp; </strong></td>
			<td><strong>$ <?=number_format($suma_cli, 2)?></strong></td>
		</tr>
	</table>
		
<?php
}
?>
	<table>
		<tr>
			<td colspan='4'><strong>Total: &nbsp; &nbsp; $ <?=number_format($suma, 2)?></strong></td>
		</tr>
	</table>
	</div>
</div>
</body>