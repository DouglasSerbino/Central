<div class="informacion">
	<form name="miform" id='agregar_prov' action="/inventario/agregar_prov/agregar_proveedor" method="post" onsubmit="return validar('agregar_prov');">
		<strong>Agregar Proveedor</strong><br />
		Nombre: <input type="text" name="proveedor" size="40" class='requ' />*&nbsp; 
		<input type="submit" class="boton" value="Guardar" />
	</form>
	<br /><strong>Listado de Proveedores</strong><br />
	<table class="tabular" style="width:600px;">
		<tr>
			<th style='width: 17%;'>Usuario</th>
			<th style='width: 17%;'>Password</th>
			<th>Proveedor</th>
			<th style='width: 17%;'>&nbsp;</th>
		</tr>
<?
foreach($Mostrar_proveedor as $Datos_proveedor)
{
?>
	<tr>
		<td><?=$Datos_proveedor["usuario"]?></td>
		<td><?=$Datos_proveedor["contrasena"]?></td>
		<td><?=$Datos_proveedor["proveedor_nombre"]?></td>
		<td><span info="<?=$Datos_proveedor['id_inventario_proveedor']?>" class="iconos ieliminar toolder"><span>Eliminar Proveedor</span></span></td>
	</tr>
<?php
}
?>
	</table>
</div>


<script>
	$('.ieliminar').click(function()
	{
		if(confirm('El Proveedor sera eliminado del sistema. Desea continuar?'))
		{
			window.location = '/inventario/agregar_prov/eliminar/'+$(this).attr('info');
		}
	});
</script>