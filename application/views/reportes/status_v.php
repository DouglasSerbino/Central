

<select name="mes" id="mes">
<?
foreach($Meses as $iMes => $nMes){
?>
	<option value="<? echo $iMes; ?>"<? echo (date('m')==$iMes)?' selected="selected"':''; ?>><? echo $nMes; ?></option>
<?
}
?>
</select> &nbsp; 
<input type="text" name="anho" id="anho" size="5" value="<?=date('Y')?>" />

&nbsp; Incluir Finalizados:
<input type="radio" name="finalizados" id="finno" value="no" checked="checked" />
<label for="finno">No</label>
<input type="radio" name="finalizados" id="finsi" value="si" />
<label for="finsi">S&iacute;</label>


<br />


<select name="cliente" id="cliente">
<?
foreach($Clientes as $Cliente)
{
?>
	<option value="<?=$Cliente['codigo_cliente']?>"><?=$Cliente['codigo_cliente']?> - <?=$Cliente['nombre']?></option>
<?
}
?>
</select>

<input type="button" value="Ver Reporte" onclick="reporte_status()" />



<script type="text/javascript">
	
	function reporte_status()
	{
		window.open('/reportes/status/ver/'+$('#cliente').val()+'/'+$('#mes').val()+'/'+$('#anho').val()+'/'+$('[name=finalizados]:checked').val());
	}
	
</script>



