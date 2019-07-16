<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<div class="informacion">
	<form name="miform" id='agregar_prov' action="/inventario/agregar_prov/agregar_proveedor" method="post" onsubmit="return validar('agregar_prov');">
		<strong>Agregar Proveedor</strong><br />
		Nombre: <input type="text" name="proveedor" size="40" class='requ' />*&nbsp; 
		<input type="submit" class="boton" value="Guardar" />
	</form>
	<br /><strong>Listado de Proveedores</strong><br />
	<table id="proveedor_list" class="tabular table table-hover table-bordered">
		<thead>
			
		
		<tr>
			<th style='width: 17%;'>Usuario</th>
			<th style='width: 17%;'>Password</th>
			<th>Proveedor</th>
			<th style='width: 17%;'>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
<?php
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
	</tbody>
</div>


<script>
	$(document).ready( function () {
		$('#proveedor_list').DataTable({
				"lengthMenu": [[ 10, 25, 35, 50, -1], [ 10, 25, 35, 50, "Todo"]],
                // "columnDefs": [
                //                 { "width": "50%", "targets": 0 },
                //                 { "width": "10%", "targets": 1 },
                //                 { "width": "10%", "targets": 2 }
                               
                //               ],
			    "language": {
			    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "decimal": "",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "emptyTable": "No hay información",
                "thousands": ",",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                  "next": "Siguiente",
                  "previous": "Anterior"
                }
            },
		});
	});

	$('.ieliminar').click(function()
	{
		if(confirm('El Proveedor sera eliminado del sistema. Desea continuar?'))
		{
			window.location = '/inventario/agregar_prov/eliminar/'+$(this).attr('info');
		}
	});
</script>