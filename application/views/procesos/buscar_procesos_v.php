
<form method="POST" action="/procesos/modificar_procesos">
	<table>
		<tr>
			<td>
				<input type="text" name="codigo_cliente" id="codigo_cliente" size="4" class="requ" />-
				<input type="text" name="proceso" id="id_proceso" class="requ" />
				<input type="submit"value="Buscar Proceso" />
			</td>
		</tr>
	</table>
</form>

<script>
    $('#codigo_cliente').focus();
</script>
