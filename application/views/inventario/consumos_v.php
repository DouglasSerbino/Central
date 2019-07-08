
<form action="/inventario/consumos" method="post">
	<select name="mes" id="mes">
<?php
foreach($Meses as $IMes => $MNombre)
{
?>
		<option value="<?=$IMes?>"<?=($IMes==$Mes)?' selected="selected"':''?>><?=$MNombre?></option>
<?php
}
?>
	</select>
	<input type="text" name="anho" id="anho" size="8" value="<?=$Anho?>" />
	<select name="pais">
<?php
foreach($Paises_C as $pCod => $pNomb)
{
?>
		<option value="<?=$pCod?>"<?=($pCod==$Pais)?' selected="selected"':''?>><?=$pNomb?></option>
<?php
}
?>
	</select>
	<input type="submit" value="Cargar Consumos" />
</form>




<strong>CONSOLIDADO</strong>
<table class="tabular" style="width:80%">
	<tr>
		<th>C&oacute;digo</th>
		<th>Material</th>
		<th>Consumo</th>
	</tr>
<?php
foreach ($Consumos['Consolidado'] as $Material)
{
?>
	<tr>
		<td><?=$Material['codigo_sap']?></td>
		<td><?=$Material['nombre_material']?></td>
		<td class="derecha"><?=number_format($Material['total'], 0)?></td>
	</tr>
<?php
}
?>
</table>


<br /><br />
<strong>DETALLE</strong>
<table class="tabular" style="width:100%">
	<tr>
		<th>Proceso</th>
		<th>Trabajo</th>
		<th>C&oacute;digo</th>
		<!--th>Material</th-->
		<th>Consumo</th>
	</tr>
<?php
foreach ($Consumos['Detalle'] as $Material)
{
?>
	<tr>
		<td><?=$Material['codigo_cliente'].'-'.$Material['proceso']?></td>
		<td><?=$Material['nombre']?></td>
		<td><?=$Material['codigo_sap']?></td>
		<!--td><?=$Material['nombre_material']?></td-->
		<td class="derecha"><?=number_format($Material['cantidad'], 0)?></td>
	</tr>
<?php
}
?>
</table>