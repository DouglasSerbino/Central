
<style>
	.rut_Agregado, .rut_Asignado, .rut_Procesando, .rut_Pausado, .rut_Aprobacion, .rut_Terminado{
		padding: 2px 3px;
		margin: 0px;
		font-size: 11px;
		border-radius: 2px 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px;
	}
</style>

<br />

<?php
foreach($Mat_Solicitado as $Material)
{
	if('&nbsp;' == $Material['material_solicitado'])
	{
		continue;
	}
	
	if(!isset($Pedidos['Mat_Lis'][$Material['id_material_solicitado_grupo']]))
	{
		continue;
	}
	
?>

<strong><?=$Material['material_solicitado']?></strong><br />


<table class="tabular" style="width: 100%;">
	<tr>
		<th style="width: 100px;">Proceso</th>
		<th style="width: 400px;">Trabajo</th>
		<th style="width: 95px;">Entrega</th>
		<th>Ruta</th>
	</tr>
<?php
	
	
	
	foreach($Pedidos['Trabajos'] as $Trabajo)
	{
		if(!isset($Pedidos['Materiales'][$Trabajo['id_pedido']][$Material['id_material_solicitado_grupo']]))
		{
			continue;
		}
		
?>
	<tr>
		<td><?=$Trabajo['codigo_cliente'].'-'.$Trabajo['proceso']?></td>
		<td><?=$Trabajo['nombre']?></td>
		<td><?=$Trabajo['fecha_entrega']?></td>
		<td>
<?php
		if(isset($Pedidos['Ruta'][$Trabajo['id_pedido']]))
		{
			foreach($Pedidos['Ruta'][$Trabajo['id_pedido']] as $Ruta)
			{
?>
			<span class="rut_<?=$Ruta['est']?> toolder"><?=$Ruta['ini']?><span><strong><?=$Ruta['usu']?></strong>: <?=$Ruta['est']?>  </span></span>
<?php
			}
		}
?>
		</td>
	</tr>
<?php
		
	}
?>
</table>
<br />

<?php	
}
?>

