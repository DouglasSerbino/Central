<div class="informacion">
<table class='tabular' style='width: 90%;'>
 <tr>
	<th>#</th>
	<th>Proceso</th>
	<th>Fecha Finalizado</th>
 </tr>
<?
$i = 1;
foreach($pedidos_sap as $Informacion)
{
?>
 <tr>
	<td colspan='3'><strong><?=$Informacion['nombre_cli']?> - <?=count($Informacion['inform'])?></strong></td>
 </tr>
<?php
 foreach($Informacion['inform'] as $Datos)
 {
 $id_pedido = $Datos['id_pedido'];
?>
 <tr>
	<td style='width: 4%;'><?=$i?></td>
	<td style='width: 65%;'>
	 <a class="iconos iexterna toolizq" href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$id_pedido?>');">
		<span>Detalle del Pedido</span></a>&nbsp;<?=$Datos['proceso']?> -  <?=$Datos['nombre']?></td>
	<td style='width: 31%;'><?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha_reale'])?></td>
 </tr>
<?
	$i++;
 }
?>
 <tr><td colspan="3"><br /></td></tr>
<?php
}
?>
</table>
</div>