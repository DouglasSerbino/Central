<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
?>
<style>
	.manita
	{
		cursor: pointer;
	}
</style>
<div class="informacion">
	<form action="/reportes/reprocesos/index" method="post" name="miform">
		
		<select name="mes" id='mes'>
<?php
foreach($Meses as $m => $Datos)
{
?>
		<option value='<?=$m?>' <?=($m == $mes)?'selected="selected"':''?>><?=$Datos?></option>
<?php
}
?>
		</select>&nbsp;&nbsp;
		<input type="text" name="anho" size="8" value="<?php echo $anho; ?>" /> &nbsp;
		<!--label for='anual'><strong>Reporte Anual</strong></label>
		<input type='checkbox' id='anual' name='anual' <?=($anual=='si')?' checked="checked"':''?>-->
		<select name='reproceso_razon' id='reproceso_razon'>
			<option value='todos' <?=('todos'==$Detalle_repro)?' selected="selected"':''?>>Todos</option>
<?php
/*
foreach($Detalle_reproceso as $Datos)
{
?>
			<option value='<?=$Datos['id_repro_deta']?>' <?=($Datos['id_repro_deta']==$Detalle_repro)?' selected="selected"':''?>><?=$Datos['detalle']?></option>
<?php
}
*/
?>
		</select>
		<input type="submit" class="boton" value="Generar Reporte" />&nbsp;&nbsp;
	</form>

<?php
if(count($Reprocesos) != 0)
{
	$subrepro = 0;
	$subped = 0;
?>
	<br />
		<table class="tabular" style='width: 50%;'>
		<tr>
			<th style='width: 20%;'>Cliente</th>
			<th style='width: 30%;'>Total Reprocesos</th>
			<th style='width: 25%;'>Total Pedidos</th>
			<th>Porcentaje</th>
		</tr>
<?php
	foreach($Reprocesos as $a => $Datos_reprocesos)
	{
		$subrepro = $subrepro + $Datos_reprocesos['total_reprocesos'];
		$subped = $subped + $Datos_reprocesos['total_pedidos'];
?>
	
		<tbody>
			<tr>
<?php
		if($anual == 'si')
		{
			$mes = 'anual';
		}
?>
				<td><a href="/reportes/reprocesos_det/index/<?=$mes?>/<?=$anho?>/<?=$Datos_reprocesos['id_cliente']?>/<?=$Detalle_repro?>" title="Ver detalle de Reproceso" target='_blanck'><strong><?=$Datos_reprocesos['codigo_cliente']?></strong></a></td>
				<td><?=$Datos_reprocesos['total_reprocesos']?></td>
				<td><?=$Datos_reprocesos['total_pedidos']?></td>
				<td><?=$Datos_reprocesos['porcentaje']?> %</td>
			</tr>
<?php
	}
	
	$porc = ($subrepro * 100) / $subped;
?>
		</tbody>
		<tr>
			<th><strong>Total</strong></th>
			<th><?=$subrepro?></th>
			<th><?=$subped?></th>
			<th><?=round($porc * 100) / 100?>%</th>
		</tr>
	</table>
<?php
}
else
{
	echo '<strong>No hay reprocesos para el mes seleccionado.</strong>';
}


	$this->generar_cache_m->generar_cache($Cache);
}
?>


<script>
	$('.span_mostrar').click(function()
	{
		$(this).parent().children('span').toggle();
		if('mos' == $(this).attr('acc'))
		{
			$('#tbl_'+$(this).attr('tipo')).show();
		}
		else
		{
			$('#tbl_'+$(this).attr('tipo')).hide();
		}
	})
	
	$(function()
	{
		if(6 >= $('[name=mes] option:selected').val()){ $('#reproceso_razon').hide();}
		$('[name=mes]').change(function()
		{
			//alert($('[name=mes] option:selected').val());
			if(6 >= $('[name=mes] option:selected').val())
			{
				$('#reproceso_razon').hide();
			}
			else
			{
				$('#reproceso_razon').show();
			}
		});
	});	
</script>