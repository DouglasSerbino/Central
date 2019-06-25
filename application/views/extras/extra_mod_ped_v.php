<?php
$usu_nombre = '';
$dpto = '';
if(isset($usuarios))
{
	foreach($usuarios as $Datos_usuario)
	{
		$usu_nombre = $Datos_usuario["nombre"];
		$dpto = $Datos_usuario["departamento"];
	}
}
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>MODIFICAR EXTRAS</title>
	<link href="<? echo "/html/css/comun.css"; ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/html/js/extra.js?n=1"></script>
	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
	<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
</head>

<body>
<div class="informacion_chica">
	<form name="miform" id='miform' action="/extras/extra_mod_ped/extra_mod_sql" method="post">
<?
$inicio = "";
$fin = "";
if(isset($inicio_fin_extra))
{
	foreach($inicio_fin_extra  as $Datos_inicio)
	{
		$inicio = $Datos_inicio["inicio"];
		$fin = $Datos_inicio["fin"];
		$fecha_entre = explode('-', $Datos_inicio['fecha']);
	}
	$fecha_mostrar = $fecha_entre[2].'-'.$fecha_entre[1].'-'.$fecha_entre[0];
	$fecha_mandar = $fecha_entre[0].'-'.$fecha_entre[1].'-'.$fecha_entre[2];
}
else
{
	$fecha_mostrar = '';
	$fecha_mandar = '';
}

echo "			<input type=\"hidden\" name=\"id_usuario\" value=\"$id_usuario\" />\n";
echo "			<input type=\"hidden\" name=\"id_extra\" value=\"$id_extra\" />\n";
?>
		<strong>Agregar Trabajos para Horas Extras &nbsp; <? echo $fecha_mostrar; ?></strong>
		
		<table class="tabla_i">
			<tr><th colspan="2"><? echo "$usu_nombre - $dpto"; ?></th></tr>
			<tr><th colspan="2">
				Inicio: <input type="text" name="inicio" id="inicio" size="6" value="<? echo $inicio; ?>" /> &nbsp;
				Fin: <input type="text" name="fin" id="fin" size="6" value="<? echo $fin; ?>" /> *
			</th></tr>
			<tr>
				<th>Proceso</th>
				<th>Descripci&oacute;n</th>
				<th width="10%">Eliminar</th>
			</tr>
<?

$i = 0;
foreach($mostrar_extras as $Datos_extras)
{
	$codigo_cliente = $Datos_extras["codigo_cliente"];
	$proceso = $Datos_extras["proceso"];
	$nombre = $Datos_extras["nombre"];
	$fecha_entrega = $Datos_extras["fecha_entrega"];
	$id_extped = $Datos_extras["id_extped"];
?>
			<tr id="fila<?=$i?>">
				<td><?=$codigo_cliente.'-'.$proceso?></td>
				<td><?=$nombre?></td>
				<td><input type="hidden" name="id_extped<?=$i?>" value="<?=$id_extped?>" />
				<input type="checkbox" name="check<?=$i?>" id="check<?=$i?>" onclick="cambia_color('fila<?=$i?>','check<?=$i?>')" /></td>
			</tr>
<?php
	$i++;
}
$otro = array();
if(isset($comentario_extra_otro))
{
	foreach($comentario_extra_otro as $Datos_otro)
	{
		$id_exto = $Datos_otro["id_exto"];
		$otro = $Datos_otro["otro"];
?>
			<tr id="fila<?=$i?>">
				<td>Adicional</td>
				<td><?=$otro?></td>
				<td><input type="hidden" name="id_exto<?=$i?>" value="<?=$id_exto?>" />
						<input type="checkbox" name="check<?=$i?>" id="check<?=$i?>" onclick="cambia_color('fila<?=$i?>','check<?=$i?>')" /></td>
			</tr>
<?php
		$i++;
	}
}
?>
		</table>
		<input type="hidden" name="total" value="<? echo $i; ?>" />
		<input type="hidden" name="fecha" value="<? echo $fecha_mandar; ?>" />
		<input type="button" class="boton" value="Modificar" onclick="envia_form()" /><br />
		
		*Ingresar los tiempos en formatos de 24 horas.
	</form>
</div>
</body>
</html>