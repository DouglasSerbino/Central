<body>
<?
$cuerpo = "class=\"hojas-conf\" onload=\"menu_pagina_act('ventas');\"";
?>
<div class="informacion">
	
	<div class="informacion_top"><div></div></div>
	
	<div class="informacion_cont">
		
		<form name="miformcheckes" action="/pedidos/hoja_tiempo_consumo/reportar_tiempo" method="post">
<table>
<?

$suma = 0;
$checkes = 0;
$cmb_cliente = array();
//print_r($procesos);
$a = 0;

foreach($procesos as $Informacion)
{
	$id_cliente = $Informacion["id_cliente"];
	//$suma = 0;
	$suma_cli = 0;
		if($Id_Cliente != "" && $id_cliente != $Id_Cliente)
		{
			continue;
		}
?>
	
		<tr>
			<td colspan='4'>
				<strong><br />&raquo; <?=$Informacion['nombre_cliente']?></strong>
			</td>
		</tr>
<?php

		foreach($Informacion['procesos'] as $ventas)
		{
				//
				$venta = $ventas["venta"];
				$fecha = $ventas["fecha"];
				$fechas = explode('-',$fecha);
				$suma = $suma + $venta;
				$suma_cli = $suma_cli + $venta;
?>
			<tr>
				<td><?=$ventas["sap"]?></td>
				<td><a href="/pedidos/pedido_detalle/index/<?=$ventas["id_pedido"]?>" title="<?=$Informacion["nombre"]?>" target="_blank">[<?=$ventas["codigo_cliente"]."-".$ventas["proceso"]?>]</a></td>
				<td><?=$fechas[2].'-'.$fechas[1].'-'.$fechas[0]?></td>
				<td>$ <?=$venta?></td>
			</tr>
<?php
$a++;
		}
?>
		<tr>
			<td colspan="2">
				<strong>Total Cliente: &nbsp; </strong>
			</td>
			<td>
				<strong>$ <?=number_format($suma_cli, 2)?></strong>
			</td>
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

</body>