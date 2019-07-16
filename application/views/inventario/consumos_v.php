<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<form action="/inventario/consumos" method="post">
	<select name="mes" id="mes">
<?php
foreach($Meses as $IMes => $MNombre)
{
?>
		<option value="<?=$IMes?>"<?=($IMes==$Mes)?' selected="selected"':''?>><?=$MNombre?></option>
<?php
}
?>
	</select>
	<input type="text" name="anho" id="anho" size="8" value="<?=$Anho?>" />
	<select name="pais">
<?php
foreach($Paises_C as $pCod => $pNomb)
{
?>
		<option value="<?=$pCod?>"<?=($pCod==$Pais)?' selected="selected"':''?>><?=$pNomb?></option>
<?php
}
?>
	</select>
	<input type="submit" value="Cargar Consumos" />
</form>




<strong>CONSOLIDADO</strong>
<table id="consolidado_list" class="tabular table table-bordered table table-hover">
	<thead>
	<tr>
		<th>C&oacute;digo</th>
		<th>Material</th>
		<th>Consumo</th>
	</tr>
	</thead>
	<tbody>
		
	
<?php
foreach ($Consumos['Consolidado'] as $Material)
{
?>
	<tr>
		<td><?=$Material['codigo_sap']?></td>
		<td><?=$Material['nombre_material']?></td>
		<td class="derecha"><?=number_format($Material['total'], 0)?></td>
	</tr>
<?php
}
?>
</tbody>
</table>


<br /><br />
<strong>DETALLE</strong>
<table id="detalle_list" class="tabular table table-bordered table table-hover">
	<thead>
	<tr>
		<th>Proceso</th>
		<th>Trabajo</th>
		<th>C&oacute;digo</th>
		<!--th>Material</th-->
		<th>Consumo</th>
	</tr>
	</thead>
	<tbody>
<?php
foreach ($Consumos['Detalle'] as $Material)
{
?>
	<tr>
		<td><?=$Material['codigo_cliente'].'-'.$Material['proceso']?></td>
		<td><?=$Material['nombre']?></td>
		<td><?=$Material['codigo_sap']?></td>
		<!--td><?=$Material['nombre_material']?></td-->
		<td class="derecha"><?=number_format($Material['cantidad'], 0)?></td>
	</tr>
<?php
}
?>
</tbody>
</table>

<script type="text/javascript">
	$(document).ready( function () {
		$('#consolidado_list').DataTable({
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

	$(document).ready( function () {
		$('#detalle_list').DataTable({
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
</script>