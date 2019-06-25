
<style>
	.rut_Agregado, .rut_Asignado, .rut_Procesando, .rut_Pausado, .rut_Aprobacion, .rut_Terminado{
		padding: 2px 3px;
		margin: 0px;
		font-size: 11px;
		border-radius: 2px 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px;
	}
</style>

<table style="width: 100%;">
	<tr>
		<th colspan="5">Trabajos en Proceso (<?=count($TrabProc['trabajos'])?>)</th>
	</tr>
	<tr>
		<th style="width: 90px;">Proceso</th>
		<th style="width: 325px;">Trabajo</th>
		<th style="width: 120px;">Tipo</th>
		<th style="width: 90px;">Entrega</th>
		<th>Ruta</th>
	</tr>
<?
foreach($TrabProc['trabajos'] as $Trabajo)
{
?>
	<tr>
		<td><?=$Codigo_Cliente.'-'.$Trabajo['proce']?></td>
		<td><?=$Trabajo['prod']?></td>
		<td><?=(isset($MateProc[$Trabajo['id_pedido']]))?$MateProc[$Trabajo['id_pedido']]:''?></td>
		<td><?=$Trabajo['entre']?></td>
		<td>
<?
	if(isset($TrabProc['ruta'][$Trabajo['id_pedido']]))
	{
		foreach($TrabProc['ruta'][$Trabajo['id_pedido']] as $Ruta)
		{
			if('GR' != $Ruta['ini'])
			{
?>
			<span class="rut_<?=$Ruta['est']?> toolder"><?=$Ruta['ini']?><span><?=$Ruta['usu']?>: <?=$Ruta['est']?></span></span>
<?
			}
		}
	}
?>
		</td>
	</tr>
<?
}



if(isset($TrabFina))
{
?>
	<tr>
		<th colspan="5"><br />Trabajos Finalizado (<?=count($TrabFina)?>)</th>
	</tr>
<?
	foreach($TrabFina as $Trabajo)
	{
?>
	<tr>
		<td><?=$Codigo_Cliente.'-'.$Trabajo['proceso']?></td>
		<td><?=$Trabajo['nombre']?></td>
		<td><?=(isset($MateFina[$Trabajo['id_pedido']]))?$MateFina[$Trabajo['id_pedido']]:''?></td>
		<td><?=$Trabajo['fecha_reale']?></td>
		<td>&nbsp;</td>
	</tr>
<?
	}
	
}
?>

</table>
