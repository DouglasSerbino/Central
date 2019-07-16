<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<table id="pedidos_list" class="tabular table table-bordered table-hover">
	<thead>
	<tr>
		<th style="width: 95px;">Proceso</th>
		<th style="width: 80px;">&nbsp;</th>
		<th>Trabajo</th>
		<th style="width: 85px;">F. Ingreso</th>
		<th style="width: 120px;">F. Solicitado</th>
		<th style="width: 80px;">&nbsp;</th>
	</tr>
	</thead>
	<tbody>
<?php
if(isset($Pedidos[0]))
{
	foreach($Pedidos[0] as $Index => $Datos)
	{
		$conta = count($Datos);
?>
	<tr>
		<td colspan='7' style='font-size: 13px;'><strong><?=$Index?>&nbsp;(<?=$conta?>)</strong></td>
	</tr>
<?php
	
		foreach($Datos as $Pedido)
		{
?>
	<tr>
		<td>
			<a href="/pedidos/pedido_detalle/index/<?=$Pedido['id_pedido']?>" class="toolizq">
				<?=$Pedido['codc']?>-<?=$Pedido['proceso']?>
				<span>Ver detalle del Trabajo</span>
			</a>
		</td>
		<td>
<?php
			if('Ventas' != $this->session->userdata('codigo'))
			{
?>
			<a href="/pedidos/modificar/index/<?=$Pedido['id_pedido']?>" class="iconos iruta toolizq">
				<span>Ingresar Pedido</span>
			</a>
<?php
			}
?>
		</td>
		<td><?=$Pedido['proceso_nombre']?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Pedido['fecha_entrada'])?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Pedido['fecha_entrega'])?></td>
		<td>
<?php
			if('Ventas' == $this->session->userdata('codigo'))
			{
?>
			<a href="/pedidos/especificacion/index/<?=$Pedido["id_pedido"]?>/m" class="iconos idocumento toolder"><span>Modificar Hoja de Planificaci&oacute;n</span></a>
			<a href="/pedidos/tiempo/accion/finalizar/<?=$Pedido['id_pedido']?>/<?=$Pedido['id_peus']?>" class="iconos iterminado toolder"><span>Dar por Terminado</span></a>
<?php
			}
			else
			{
				if('Ventas' == $Pedido['codigo'])
				{
?>
		<strong>&nbsp;&nbsp;*</strong>
<?php
				}
				else
				{
?>
		<a href="javascript:rechazar('<?=$Pedido['id_pedido']?>','<?=$Pedido['id_peus']?>');" class="iconos irechazar toolder"><span>Rechazar Trabajo</span></a>
<?php
				}
			}
?>
		</td>
	</tr>
<?php
		}
	}
}
?>
</tbody>
</table>

<br />

<?php

$this->load->view('pedidos/rechazo_v.php');


?>

<script type="text/javascript">
	$(document).ready( function () {
		$('#pedidos_list').DataTable({
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
