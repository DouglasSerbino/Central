
<form action="/reportes/envio" method="post">
	<select name="cliente" id="cliente">
		<!--option value="todos">Todos</option-->
<?
foreach($Clientes as $Cliente)
{
?>
		<option value="<?=$Cliente['id_cliente']?>"<?=($Cliente['id_cliente']==$Id_Cliente)?' selected="selected"':''?>><?=$Cliente['codigo_cliente']?> - <?=$Cliente['nombre']?></option>
<?
}
?>
	</select>
	
	<input type="text" readonly="readonly" name="inicio" size="12" value="<?=$Inicio?>" />
	<input type="text" readonly="readonly" name="fin" size="12" value="<?=$Fin?>" />
	
	<input type="submit" value="Buscar" />
</form>

<script>
	$(function()
	{
		$("[name=inicio]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
		$("[name=fin]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
	})
</script>

<?
if(0 != $Id_Cliente)
{
	foreach($Envios['Notas'] as $Nota)
	{
?>

<br />
<table class="tabular" style="width:100%">
	<tr>
		<th colspan="2"><a href="javascript:ventana_externa('/notas/nota_ver/index/<?=$Nota['id_nota_env']?>');"><?=$Nota['correlativo']?></a></th>
	</tr>
<?
		foreach($Envios['Trabajos'][$Nota['id_nota_env']] as $Trabajo)
		{
?>
	<tr>
		<td style="width: 150px;"><?=$Trabajo['codigo_cliente']?>-<?=$Trabajo['proceso']?></td>
		<td><?=$Trabajo['nombre']?></td>
	</tr>
<?
		}
?>
</table>
<?
	}
}
?>
