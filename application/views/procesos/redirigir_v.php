<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
<form method="post" action="/carga/seguimiento" id='mandar' name='mandar'>
	<input type="hidden" name="dia1" id="dia1" size="5" value="<?=$Fechas['dia1']?>" />
	<input type="hidden" name="mes1" id="mes1" size="8" value="<?=$Fechas['mes1']?>" />
	<input type="hidden" name="anho1" id="anho1" size="8" value="<?=$Fechas['anho1']?>" />
	<input type="hidden" name="dia2" id="dia2" size="5" value="<?=$Fechas['dia2']?>" />
	<input type="hidden" name="mes2" id="mes2" size="8" value="<?=$Fechas['mes2']?>" />
	<input type="hidden" name="anho2" id="anho2" size="8" value="<?=$Fechas['anho2']?>" />
	<input type="hidden" name="cliente" id="cliente" size="8" value="<?=$Id_Cliente?>" />
	<input type="hidden" name="puesto" id="puesto" size="8" value="<?=$Puesto?>" />
	<input type="hidden" name="trabajo" id="trabajo" value="<?=$Trabajo?>" />
	<input type="hidden" name="bus_proceso" id="bus_proceso" value="" />
	<input type="hidden" name="cliente_tipo" id="cliente_tipo"  value="<?=$cliente_tipo?>" />
</form>

<script>
	$('#mandar').submit();
</script>