<?
foreach($informacion_procesos as $Datos_proceso)
{
	$proceso = $Datos_proceso["codigo_cliente"]."-".$Datos_proceso["proceso"];
	$trabajo = $Datos_proceso["producto_n"];
	$cliente = $Datos_proceso["cliente"];
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>TIEMPOS</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<link href="/html/css/extra.css" rel="stylesheet" type="text/css" />
	<link href="/html/css/tiempo_consumo.css" rel="stylesheet" type="text/css" />
	<script language="javascript" type="text/javascript">
		function imprimir(){
			window.print();
		}
	</script>
</head>

<body onload="imprimir()">

<div id="contenedor-pagina">

<div id="encabezado"><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" width="125" alt="<?=$this->session->userdata('grupo')?>" /></div>
<div id="titulo" align='center'>REPORTE DE PRODUCCION <? echo $proceso; ?></div>

<?php
if(count($informacion_sap) != 0)
{
	foreach($informacion_sap as $Datos_sap)
	{
		$ordenes = $Datos_sap['orden'];
		$codigo_sap = $Datos_sap['sap'];
	}
}
else
{
	$ordenes = ',';
	$codigo_sap = '';
}
?>
<div style="text-align:left">
	<br />Cliente: <strong><?=$cliente?></strong>
	<br />Producto: <strong><?=$trabajo?></strong><br />
	No. Pedido SAP: <strong><?=$codigo_sap?></strong> &nbsp; No. de Orden:<strong>

<?php
$num_orden = explode(',',$ordenes);
foreach($num_orden as $mostrar)
{
?>
	&nbsp;&raquo;<?=$mostrar?>
<?php
}
?>
</strong>
	</div>
<table>
	<tr>
		<td class="puesto"><strong>Puesto</strong></td>
		<td class="fecha"><strong>Fecha</strong></td>
		<td class="inicio"><strong>Inicio</strong></td>
		<td class="fin"><strong>Fin</strong></td>
		<td class="horas"><strong>Horas</strong></td>
		<td class="turno"><strong>Turno</strong></td>
	</tr>	
<?php
foreach($informacion_general as $Datos_generales)
{
	//date('d-m-Y H:i:s', strtotime('- 2 hour', strtotime('2012-01-01 12:30:00')));
	$turno = 1;
	$fecha_hora = $this->fechas_m->fecha_subdiv($Datos_generales['inicio']);
	$horas = $this->fechas_m->minutos_a_hora($Datos_generales["tiempo_usuario"]);
	$fin = date('H:i', strtotime("+ ".$Datos_generales["tiempo_usuario"]." minutes", strtotime($Datos_generales['inicio'])));

	if($fecha_hora['hora'] < 8 || $fecha_hora["hora"] >= 17)
	{
		$turno = 2;
	}
?>
	<tr>
		<td><?=$Datos_generales['puesto']?> <?=$Datos_generales['usuario']?></td>
		<td><?=$fecha_hora['dia']?>-<?=$fecha_hora['mes']?> <?=$fecha_hora['anho']?></td>
		<td><?=$fecha_hora['hora']?>:<?=$fecha_hora['minuto']?></td>
		<td><?=$fin?></td>
		<td><?=$horas?></td>
		<td><?=$turno?></td>
	</tr>
<?php	
}	
?>
</table>
<br />
<div style="text-align:left"><strong>Lista de Materiales</strong></div>
<table>
	<tr>
		<th class="codigo_mat">Material</th>
		<th class="nombre_mat">Descripci&oacute;n</th>
		<th class="cantidad_mat">Cantidad</th>
		<th class="turno">unidad</th>
		<th>Reproceso</th>
	</tr>
<?php
if(count($informacion_materiales) != 0)
{
	foreach($informacion_materiales as $Datos_materiales)
	{
?>
	<tr>
		<td><?=$Datos_materiales['codigo_sap']?></td>
		<td><?=$Datos_materiales['nombre_material']?></td>
		<td><?=$Datos_materiales['cantidad']?></td>
		<td><?=$Datos_materiales['tipo']?></td>
<?php
	if($Datos_materiales['reproceso'] == 'on')
	{
?>
		<td>SI</td>
<?php
	}
	else
	{
?>
		<td></td>
<?php
	}
?>
	</tr>
<?php
	}
}
?>
</table>
	</div>

</body>
</html>