<strong>Solicitud de Pedidos en Transito</strong>
<table class='tabular' style='width: 75%;'>
	<tr>
		<th>Nombre del Material</th>
		<th>Fecha de Solicitud</th>
		<th>Opciones</th>
	</tr>
	<tr>
<?php
	foreach($Solicitudes as $Datos)
	{
?>
	<tr>
		<td><?=$Datos['nombre_material']?></td>
		<td><?=$Datos['fecha']?></td>
		<td style='text-align: center;'><a href='/herramientas_sis/agregar_ped_tran/index/<?=$Datos['id_inventario_material']?>' class="iconos imas toolizq"><span>Agregar Pedido en Transito</span></a></td>
	</tr>
<?php
	}
?>
	</tr>
</table>
<?php
/*
 *Solicitudes de material que ingresan los usuarios de central-g.
*/
?>
<br /><br />
<strong>Solicitud de Material</strong>
<table class='tabular' style='width: 95%;'>
	<tr>
		<th>Codigo</th>
		<th>Nombre del Material</th>
		<th>Solicitud</th>
		<th>Cantidad</th>
		<th>Tipo</th>
		<th>Fecha</th>
		<th>Observaciones</th>
		<th>Opciones</th>
	</tr>
	<tr>
<?php
	$fecha = date('Y-m-d H:i:s');
	foreach($Solicitudes_Material as $Datos)
	{
?>
	<tr>
		<td><?=$Datos['codigo_sap']?></td>
		<td><?=$Datos['nombre_material']?></td>
		<td><?=$Datos['usuario']?></td>
		<td><?=$Datos['cantidad']?></td>
		<td><?=$Datos['tipo']?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha'])?></td>
		<td><?=$Datos['observaciones']?></td>
		<td style='text-align: center;'>
			<a href='/herramientas_sis/agregar_ped_tran/index/<?=$Datos['id_inventario_material']?>' class="iconos imas toolder"><span>Agregar Pedido en Transito</span></a>
			&nbsp;&nbsp;&nbsp;
			<a onclick="eliminar('<?=$Datos['id_inventario_material']?>', '<?=$Datos['id_solicitud']?>')"  class="iconos iterminado toolder"><span>Eliminar</span></a>
		</td>
	</tr>
<?php
	}
?>
	</tr>
</table>


<br /><br />
<a href='/herramientas_sis/sol_material'>Pedidos Procesados</a>


<script>
	function eliminar(id_material, id_solicitud)
	{
		if(confirm("Desea eliminar la solicitud?"))
		{
			window.location = "/herramientas_sis/lsol_pedido/eliminar/"+id_material+"/"+id_solicitud;
		}
	}
</script>