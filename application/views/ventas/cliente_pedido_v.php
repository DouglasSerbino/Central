

<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<div id="datos_vent_clie">


	<table id="table_list" class="tabular table table-hover table-bordered">
		<thead>
		<tr>
			<th style="width: 100px;">Proceso</th>
			<th>Trabajo</th>
			<th class="numero" style="width: 100px;">Venta</th>
			<th class="numero" style="width: 100px;">Factura</th>
		</tr>
		</thead>
		<tbody>
		
<?php
$Total_Venta = 0;
foreach($Vent_Clie as $Fila)
{
	$Total_Venta += $Fila['venta'];
?>
		<tr>
			<td><?=$Fila['codigo_cliente'].'-'.$Fila['proceso']?></td>
			<td><?=$Fila['nombre']?></td>
			<td class="numero vent100">$<?=number_format($Fila['venta'], 2)?></td>
			<td class="numero"><?=$Fila['es_factura']?></td>
		</tr>
<?php
}
?>
</tbody>
<tfoot>
		<tr>
			<th class="numero" colspan="2">TOTAL</th>
			<th class="numero vent100">$<?=number_format($Total_Venta, 2)?></th>
			<th>&nbsp;</th>
		</tr>
		</tfoot>
	</table>
	
</div>


<script type="text/javascript">
	$(document).ready( function () {
		$('#table_list').DataTable({
				"lengthMenu": [[ -1], [ "Todo"]],
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
</script>
