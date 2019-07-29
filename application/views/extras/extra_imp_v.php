<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
if($tipo == 'todos')
{
	$titulo = 'REPORTE DE HORAS EXTRAS';
}
else
{
	$titulo = 'REPORTE INDIVIDUAL DE HORAS EXTRAS';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>REPORTE DE HORAS EXTRAS</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<link href="/html/css/extra.css" rel="stylesheet" type="text/css" />
	<script language="javascript" type="text/javascript">
		function imprimir(){
			//window.print();
		}
	</script>
</head>
<style>
.normal th, .normal td {
  border: 1px solid #000;
}
</style>
<body onload="imprimir()">

<div id="contenedor-pagina">

<div id="encabezado"><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" alt="Logo" /></div>
<div id="titulo">
	<center><?=$titulo?>
	<div><?=$dia.'-'.$mes.'-'.$anho?></div></center>
</div>

<div id="contenido">
<?php
	foreach($buscar_extras as $Datos_extras)
	{
		
		$id_extra = $Datos_extras['id_extra'];
		$id_usuario_v = $Datos_extras['id_usuario'];
		$nombre = $Datos_extras['nombre'];
		$hora_v = $Datos_extras['hora'];
		$inicio = $Datos_extras['inicio'];
		$fin = $Datos_extras['fin_real'];
		$total_h = $Datos_extras['total_h'];
		$total_m = $Datos_extras['total_m'];
		$cod_empleado = $Datos_extras['cod_empleado'];
		
?>
	<table style='width: 80%;'>
		<tr>
			<td colspan='3'><strong><?=$cod_empleado?> - <?=$nombre?></strong></td>
		</tr>
<?php	
		foreach($Datos_extras['extra_pedido'] as $Datos_ext)
		{
			$codigo_cliente = $Datos_ext["codigo_cliente"];
			$proceso = $Datos_ext["proceso"];
			$producto = $Datos_ext["nombre_traba"];
			$fecha_entrega = $Datos_ext["fecha_entrega"];
?>
		<tr>
			<td style='width: 25%;'>&nbsp;&nbsp;&nbsp;<?=$codigo_cliente?>-<?=$proceso?></td>
			<td><?=$producto?></td>
		</tr>
<?php		
		}
	
	foreach($Datos_extras['extra_otro'] as $Datos_extra_otro)
	{
		$otro = $Datos_extra_otro["otro"];
?>
		<tr>
			<td style='width: 25%;'></td>
			<td><?=$otro?></td>
		</tr>
<?php	
	}
?>
		<tr>
			<td class="total" colspan='3'><strong><?=$inicio?>  a  <?=$fin?> </strong> &nbsp; &nbsp; Total: &nbsp; <strong><?=number_format($total_h, 2)?> horas</strong></td>
		</tr>
	</table>
<?php
	}
?>
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
</div>
</body>
</html>