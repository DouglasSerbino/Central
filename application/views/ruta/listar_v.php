<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<input class="btn btn-warning" type="button" value="Crear Ruta" id="ruta_crear" />
<br>
<br>
<div class="row">
<div class="container">
<table id="rutas" class="tabular table table-bordered table-responsive">
	<thead>
		<tr>
			<!--th>Etiqueta</th-->
			<th>Cliente</th>
			<th>Elemento</th>
			<th class="derecha">Opciones &nbsp; </th>
		</tr>
	</thead>
	<tbody>
		
	
	<?php
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
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
</div>
</div>

<script>
	$(document).ready( function () {
		$('#rutas').DataTable({
				"lengthMenu": [[ 10, 25, 35, 50, -1], [ 10, 25, 35, 50, "Todo"]],
                "columnDefs": [
                                { "width": "50%", "targets": 0 },
                                { "width": "10%", "targets": 1 },
                                { "width": "10%", "targets": 2 }
                               
                              ],
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

	$('#ruta_crear').click(function()
	{
		window.location = '/ruta/ruta_dinamica/crear';
	});
</script>