
<table>
	<tr>
		<td>Proceso:</td><th><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></th></tr>
	<tr>
		<td>Cliente:</td>
		<th><?=$Info_Proceso['nombre']?></th></tr>
	<tr>
		<td>Producto:</td>
		<th><?=$Info_Proceso['nombre_proceso']?></th>
	</tr>
</table>


<br />
<table style="width: 600px;" class="tabular">
	<tr>
		<th>Usuario</th>
		<th>Fecha</th>
		<th>Detalle</th>
	</tr>
<?
foreach($Historial as $Hoja)
{
?>
	<tr>
		<td><?=$Hoja['usuario']?></td>
		<td><?=$Hoja['fecha']?></td>
		<td><a href="/hojas_revision/historial/detalle/<?=$Id_Pedido?>/<?=$Hoja['id_revision_pedido']?>">Detalle</a></td>
	</tr>
<?
}
?>
</table>



