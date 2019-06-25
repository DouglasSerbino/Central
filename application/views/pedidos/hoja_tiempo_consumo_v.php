<link rel="stylesheet" type="text/css" media="all" href="/html/css/detalle_fac.css" />
<script type="text/javascript" src="/html/js/detalle_fac.js?n=1"></script>

<body onload="javascript:setInterval(permanente,50);">
<div class='informacion'>
	<div class='informacion_cont'>
		<form name='miformcheckes' action='/pedidos/hoja_tiempo_consumo/reportar_tiempo' method='post'>
<table>
<?php
$suma = 0;
$a = 0;
foreach($procesos as $Informacion)
{
	$id_cliente = $Informacion['id_cliente'];
	$suma_cli = 0;
		if($Id_Cliente != '' && $id_cliente != $Id_Cliente)
		{
			continue;
		}
?>
	<tr>
		<td colspan='4'><strong><br />&raquo; <?=$Informacion['nombre_cliente']?></strong></td>
	</tr>
<?php
	foreach($Informacion['procesos'] as $ventas)
	{
			$venta = $ventas["venta"];
			$fecha = $ventas["fecha"];
			$fechas = explode('-',$fecha);
			$suma = $suma + $venta;
			$suma_cli = $suma_cli + $venta;
?>
	<tr>
		<td>
			<a href="/pedidos/tiempo_consumo/index/<?=$ventas["id_pedido"]?>"><img src="/html/img/impri.jpg" height='20px' width='25px' alt=""></a>
			<input type='checkbox' name='chk<?=$a?>' id='chk<?=$a?>'>
			<input type='hidden' name='iv<?=$a?>' value='<?=$ventas['id_pedido_sap']?>'>
			<label for='chk<?=$a?>'><?=$ventas["sap"]?></label>
		</td>
		<td><a href="/pedidos/pedido_detalle/index/<?=$ventas["id_pedido"]?>" title="<?=$Informacion["nombre"]?>" target="_blank">[<?=$ventas["codigo_cliente"]."-".$ventas["proceso"]?>]</a></td>
		<td><?=$fechas[2].'-'.$fechas[1].'-'.$fechas[0]?></td>
		<td>$ <?=$venta?></td>
	</tr>
<?php
$a++;
	}
?>
	<tr>
		<td colspan="2"><strong>Total Cliente: &nbsp; </strong></td>
		<td><strong>$ <?=number_format($suma_cli, 2)?></strong></td>
	</tr>
<?php
}
?>
	<tr>
		<td>
			<br><strong>Total: &nbsp; &nbsp; $ <?=number_format($suma, 2)?></strong>
		</td>
	</tr>
</table>
</div>
<div>
		<input type="hidden" name="cuantos_checkes" value="<?=$a?>" />
		<input type="hidden" name="hoj_id_cliente" value="<?=$Id_Cliente?>" />
		<div id="div_ver_cliente" style="top: 170px;">
			<strong>Filtrar resultados por Cliente:</strong><br />
			<select name="ver_cliente" id="ver_cliente" onchange="ver_hojas()">
				<option value="">Todos</option>
<?
foreach($procesos as $Informacion)
{
	$id_cliente = $Informacion['id_cliente'];
?>
				<option value="<?=$id_cliente?>" <?=($id_cliente == $Id_Cliente)?' selected="selected"':''?>><?=$Informacion['nombre_cliente']?></option>
<?php
}
?>
			</select>
			<br /><br />
			<strong>Reportar los pedidos seleccionados</strong><br />
			<input type="submit" value="Reportar" />
		</div>
		</form>
	</div>
</body>