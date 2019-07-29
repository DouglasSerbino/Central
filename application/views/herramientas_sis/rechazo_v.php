<table class='tabular' style='width: 90%;'>
	<tr>
		<th>Usuario</th>
		<th>Fecha</th>
		<th>Razon del Rechazo</th>
		<th>Explicaci&oacute;n</th>
		<th>Opciones</th>
	</tr>
	
<?php
foreach($Rechazos as $Datos)
{
	$Fecha = explode(' ', $Datos['fecha']);
?>
	<tr>
		<td><?=$Datos['usuario']?></td>
		<td><?=$Fecha[0]?></td>
		<td><?=$Datos['rechazo_razon']?></td>
		<td style='width: 40%;'><?=$Datos['explicacion']?></td>
		<td><a href='/herramientas_sis/rechazo/quitar/<?=$Datos['id_pedido']?>/<?=$Datos['id_usuario']?>' class="iconos ieliminar toolder"><span>Quitar Rechazo</span></a></td>
	</tr>
<?php
}
?>
	
</table>