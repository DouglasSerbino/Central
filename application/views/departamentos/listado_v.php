<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<table id="departamentos_list" class="tabular table-condensed table-hover table table-bordered">
	<thead>
		
	
    <tr>
        <th><strong>Codigo</strong></th>
        <th><strong>Departamento</strong></th>
        <th><strong>Tipo de inventario</strong></th>
        <th><strong>Cantidad Mensual</strong></th>
        <th><strong>Iniciales</strong></th>
        <th style="text-align:center;"><strong>Opciones</strong></th>
    </tr>
    </thead>
   <tbody>
   	
 
<?php
foreach($Departamentos as $Departamento)
{
?>	
	<tr>
		<td><?=$Departamento["codigo"]?></td>
		<td><?=$Departamento["departamento"]?></td>
		<td><?=$Departamento["tipo_inv"]?></td>
		<td><?=$Departamento["cant_mensual"]?></td>
		<td><?=$Departamento["iniciales"]?></td>
		<td>
		<a href="/departamentos/modificar/mostrar_datos/<?=$Departamento["id_dpto"]?>" class="iconos ieditar toolder"><span>Modificar Departamento</span></a> &nbsp;&nbsp;
<?php
if($Departamento["activo"] == "s")
{
?>
		<a href="/departamentos/desactivar_activar/opcion/<?=$Departamento["id_dpto"]?>/n" class="iconos ieliminar toolder"><span>Desactivar Departamento</span></a>
<?php
}
elseif($Departamento["activo"] == "n")
{
?>
		<a href="/departamentos/desactivar_activar/opcion/<?=$Departamento["id_dpto"]?>/s" class="iconos ireactivar toolder"><span>Reactivar Departamento</span></a>
<?php
}
?>
		</td>
	</tr>
<?php
}
?>
  </tbody>
</table>

<script type="text/javascript">
	$(document).ready( function () {
		$('#departamentos_list').DataTable({
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
