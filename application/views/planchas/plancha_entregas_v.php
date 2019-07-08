<div class="informacion">
	
	<strong>Listado de trabajos pendientes ordenados por fecha de entrega</strong><br />
	
<?php
$pedidos_aprobacion_v = array();
foreach($Aprobacion as $Datos)
{
	$pedidos_aprobacion_v[$Datos['id_pedido']] = true;
}

$fecha_ant = '';
foreach($Pedidos as $Datos)
{
	foreach($Datos['datos'] as $Informacion)
	{
		if(!isset($pedidos_aprobacion_v[$Informacion['id_pedido']]))
		{
			if($fecha_ant != $Datos['fecha_entrega'])
			{
				$fecha_ant = $Datos['fecha_entrega'];
?>
				<strong><?=$this->fechas_m->fecha_ymd_dmy($fecha_ant)?></strong>   <br />
<?php	
			}

?>
			<p style='margin-left: 4em'>&raquo;  <?=strtoupper($Informacion['codigo_cliente'])?> - <?=$Informacion['proceso']?>  <?=$Informacion['nombre']?></p>
<?php
		}
	}
}
?>
	
</div>