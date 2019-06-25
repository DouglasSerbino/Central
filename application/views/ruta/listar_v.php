

<input type="button" value="Crear Ruta" id="ruta_crear" />


<table class="tabular" style="width: 50%;">
	<tr>
		<!--th>Etiqueta</th-->
		<th>Cliente</th>
		<th>Elemento</th>
		<th class="derecha">Opciones &nbsp; </th>
	</tr>
<?
foreach($Listado as $Datos)
{
?>
	<tr>
		<!--td><?=$Datos['observacion']?></td-->
		<td><?=$Datos['nombre']?></td>
		<td><?=$Datos['elemento']?></td>
		<td class="derecha">
			<a href="/ruta/ruta_dinamica/modificar/<?=$Datos['id_ruta']?>" class="iconos ieditar toolder"><span>Modificar</span></a>
			<a href="/ruta/ruta_dinamica/eliminar/<?=$Datos['id_ruta']?>" class="iconos ieliminar toolder"><span>Eliminar</span></a> &nbsp; 
		</td>
<?
}
?>
	</tr>
</table>


<script>
	$('#ruta_crear').click(function()
	{
		window.location = '/ruta/ruta_dinamica/crear';
	});
</script>