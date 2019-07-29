<form method='post' action='/herramientas_sis/entregas_cliente/index'>
	Codigo de Cliente<input type='text' name='cod_cliente' style='width: 45px;' value='<?=$Cliente?>'>
	<select name="mes" id='mes'>
<?php
foreach($Meses as $iMes => $nMes)
{
?>
		<option value="<?=$iMes?>"<?=($Mes==$iMes)?' selected="selected"':''; ?>><?=$nMes?></option>
<?php
}
?>
	</select>
	<input type='submit' value='Buscar'>
</form>
<?php
if(0 < count($Prueba))
{
	$Total = 0;
	foreach($Prueba as $dia => $Datos)
	{
?>
		<table>
			<th>&raquo;<?=$dia?>-<?=$Mes?>-<?=date('Y')?></th>
<?php
			foreach($Datos['proceso'] as $id_pedido => $info)
			{
?>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$id_pedido?>');" class="toolizq"><span>Ver detalle</span>
			&nbsp; <?=strtoupper($info)?></a></td>
			</tr>
<?php
			}
?>
			<tr>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total <?=$Datos['total']?></th>
			</tr>
<?php
		$Total += $Datos['total'];
?>
	</table>
		<br />
<?php
	}
?>
	Total de Trabajos <strong><?=$Total?></strong>
<?php
}
?>