
<form action="/pedidos/buscar/proceso" method="post" name="miform">
	
	<input type="text" name="cliente" id="cliente" size="8" />
	<strong>-</strong>
	<input type="text" name="proceso" id="proceso" size="15" />
	
	<input type="submit" class="boton" value="Agregar Pedido" />
	
</form>

<script>
    $('#cliente').focus();
</script>
