<script>
	$(function()
	{
		$("[name=fecha_inicio]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
		$("[name=fecha_fin]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
	});
</script>
<div class="informacion" id='reporte_semanal'>
	<form method='post' action='/herramientas_sis/revision_inventario'>
		
		<input type="text" name="fecha_inicio" id="fecha_inicio" size="15" value="<? echo $Fecha_Inicio; ?>" readonly="readonly" />
		<input type="text" name="fecha_fin" id="fecha_fin" size="15" value="<? echo $Fecha_Fin; ?>" readonly="readonly" />
		Codigo Sap <input type='text' name='sap' value='<?=$sap?>'>
		<br />
		<select name="cliente" id="cliente">
			<option value="--">Seleccionar Cliente</option>
<?
foreach($mostrar_clientes as $Cliente)
{
?>
			<option value="<?=$Cliente["id_cliente"]?>"<?=($Id_Cliente==$Cliente["id_cliente"])?' selected="selected"':''?>><?=$Cliente["codigo_cliente"]?> - <?=$Cliente["nombre"]?></option>
<?
}
?>
		</select>
		<input type="submit" value="Ver reporte" />
		
	</form>
</div>
<br />
<table class='tabular' style='float: left; width: 60%;'>
	<tr>
		<th>Proceso</th>
		<th># Orden</th>
		<th>Consumo</th>
		<th>Codigo Sap</th>
		<th>Fecha de Terminado</th>
	</tr>
<?
if('' != $sap)
{
	if(0 < count($Listado))
	{
?>
	<strong><?=strtoupper($Listado[0]['nombre_material'])?></strong>
	<br />&nbsp;
<?php
	}
}
foreach($Listado as $Datos)
{
	$Orden = explode(',', $Datos['orden']);
?>
	<tr>
		<td><?=$Datos['codigo_cliente']?>-<?=$Datos['proceso']?></td>
		<td>
		<?php
		foreach($Orden as $Ord)
		{
			if('--' != $Ord)
			{
				echo $Ord.'<br>';
			}
		}
		?>
		</td>
		<td><?=$Datos['cantidad']?></td>
		<td><?=$Datos['codigo_sap']?></td>
		<td><?=date('d-m-Y', strtotime($Datos['fecha_reale']))?></td>
	</tr>
<?php
}
?>
</table>
<table class='tabular' style='float: right; margin-right: 180px; width: 12%;'>
	<tr>
		<th># Orden</th>
	</tr>
<?
foreach($Listado as $Datos)
{
	$Orden = explode(',', $Datos['orden']);
?>
	<tr>
		<td>
		<?php
		foreach($Orden as $Ord)
		{
			if('--' != $Ord)
			{
				echo $Ord.'<br>';
			}
		}
		?>
		</td>
	</tr>
<?php
}
?>
</table>
<br style="clear:both;" /><br />