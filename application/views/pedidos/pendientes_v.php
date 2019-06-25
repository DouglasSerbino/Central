<script type="text/javascript" src="/html/js/thickbox.js"></script>
<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />

<style>
	.estaAgregado{
		color: #777777;
	}
	.oculto
	{
		display: none;
	}
	.pointer
	{
		cursor:pointer; cursor: hand
	}
</style>


<?php
for($i = 0; $i < 2; $i++)
{
	$oculto = '';
?>
<strong>
<?php
if(0==$i){
	?>
		TRABAJOS EN CARGA
<?php
}
else
{
?>
	TRABAJOS EN RUTA
<?php
}
?>
</strong>
<table class="tabular <?=$oculto?>" style="width: 100%">
	<tr>
		<th style="width: 55px;"></th>
		<th style="width: 95px;">Proceso</th>
		<th>Trabajo</th>
		<th style="width: 75px;">Estado</th>
		<th style="width: 80px;">Asignado</th>
		<th style="width: 90px;">Entrega</th>
		<th style="width: 120px;">Producto</th>
	</tr>
<?
foreach($Pedidos as $Id_Pedido => $Pedido)
{
	
	if('Agregado' == $Pedido['estado'] && $i == 0)
	{
		continue;
	}
	if('Agregado' != $Pedido['estado'] && $i == 1)
	{
		continue;
	}
	
 	if('Asignado' == $Pedido['estado'])
	{
		$Pedido['estado'] = 'Sin Inicio';
	}
	if(
		'N/A' == $Pedido['tiempo_asignado']
		|| 'NaN' == $Pedido['tiempo_asignado']
	)
	{
		$Pedido['tiempo_asignado'] = '';
	}
	else
	{
		$Pedido['tiempo_asignado'] = ' ['.$this->fechas_m->minutos_a_hora($Pedido['tiempo_asignado']).']';
	}
	
	if($Pedido['fecha_entrega'] == date('Y-m-d'))
	{
		$Estado_Fecha = 'est_verde';
	}
	elseif(strtotime($Pedido['fecha_entrega'].' 00:00:01') > strtotime(date('Y-m-d H:i:s')))
	{
		$Estado_Fecha = 'est_azul';
	}
	else
	{
		$Estado_Fecha = 'est_rojo';
	}
	
?>
	<tr class="esta<?=$Pedido['estado']?>">
			<td>
<?php
	if($Pedido['url'] != '')
	{
?>
			<a href="<?=$Pedido['url']?>" class="thickbox" title='' >
				<img width='40px' height='30px' src="<?=$Pedido['url']?>" title="<?=$Pedido['nombre_adjunto']?>" />
			</a>
<?php
	}
?>
		</td>
		<td><?
if('Agregado' != $Pedido['estado'])
{
?><a href="/pedidos/detalle_activo/index/<?=$Id_Pedido?>"><?=$Pedido['codigo_cliente']?>-<?=$Pedido['proceso']?></a><?
}
else
{
?><a href="/pedidos/pedido_detalle/index/<?=$Id_Pedido?>"><?=$Pedido['codigo_cliente']?>-<?=$Pedido['proceso']?></a><?
}
?></td>
		<td><?=$Pedido['proceso_nombre'].$Pedido['tiempo_asignado']?></td>
		<td><?=('Agregado' == $Pedido['estado'])?'En Ruta':$Pedido['estado']?></td>
		<td><?=date(('d-m-Y H:i:s'), strtotime($Pedido['fecha_asignado']))?></td>
		<td class='<?=$Estado_Fecha?>'><?=('0000-00-00'!=$Pedido['fecha_entrega'])?date('d-m-Y', strtotime($Pedido['fecha_entrega'])):'0000-00-00'?></td>
		<td><?=$Pedido['producto']?></td>
	</tr>
<?
}
?>
</table>
<br />
<?
}
?>
<script>
	$("#mostrarTra").toggle(
  function () {
    $('.oculto').css('display', 'block');
		$('#signo').text('[-]');
  },
  function () {
    $('.oculto').css('display', 'none');
		$('#signo').text('[+]');
  }
);
</script>