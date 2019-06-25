

<table style="width: 720px;">
	<tr>
		<td style="width: 65px;">Proceso:</td>
		<td><strong><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></strong> &nbsp; &nbsp; &nbsp; &nbsp; <a href="/pedidos/pedido_detalle/index/<?=$Id_Pedido?>">(Ver Detalle)</a></td>
	</tr>
	<tr>
		<td>Cliente:</td>
		<th><?=$Info_Proceso['nombre']?></th></tr>
	<tr>
		<td>Producto:</td>
		<th><?=$Info_Proceso['nombre_proceso']?></th>
	</tr>
</table>
<?php
if(is_array($Observaciones))
{
	$Ped_Anterior = 0;
	
	foreach($Observaciones as $fila)
	{
		$f = $this->fechas_m->fecha_subdiv($fila["fecha_hora"]);
		$fecha_hora = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
		if($Ped_Anterior != $fila['id_pedido'])
		{
			
			if(0 < $Ped_Anterior)
			{
?>
</table>
<br />
<?
			}
			
			$Ped_Anterior = $fila['id_pedido'];
?>
<br />
<table class="tabular obsr_tabla" style="width: 100%;" id="obsrv_normal">
	<tr>
		<th style="width: 157px;">Fecha:</th>
		<th style="width: 125px;">Usuario:</th>
		<th>Observaci&oacute;n:</th>
	</tr>
<?
		}
?>
	<tr>
		<td><?=$fecha_hora?></td>
		<td><?=$fila["usuario"]?></td>
		<td><?=nl2br($fila["observacion"])?></td>
	</tr>
<?
	}
}
?>
</table>


