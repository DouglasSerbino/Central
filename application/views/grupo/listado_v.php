<div>
<table class="tabular">
	<tr>
		<th><strong>Nombre del Grupo &nbsp;&nbsp;</strong></th>
		<th><strong>Abreviatura del Grupo &nbsp;&nbsp;</strong></th>
		<th><strong>Tipo del Grupo &nbsp;&nbsp;</strong></th>
		<th><strong>Opciones</strong></th>
	</tr>
<?php
foreach($Grupos as $Grupo)
{
?>	
	<tr>
		<td><?=$Grupo["nombre_grupo"]?></td>
		<td><?=$Grupo["abreviatura"]?></td>
		<td><?=$Grupo["tipo_grupo"]?></td>
		<td>
			<a href="modificar/datos/<?=$Grupo["abreviatura"]?>" class="iconos ieditar toolder"><span>Modificar Grupo</span></a> &nbsp;&nbsp;
<?php
			if($Grupo["activo"] == "s")
			{
?>
			<a href="desactivar_activar/desactivar_grp/<?=$Grupo["id_grupo"]?>" class="iconos ieliminar toolder"><span>Desactivar Grupo</span></a></td>
<?php
			}
			else
			{
?>
			<a href="desactivar_activar/activar_grp/<?=$Grupo["id_grupo"]?>" class="iconos ireactivar toolder"><span>Reactivar Grupo</span></a></td>
<?php
			}
?>
	</tr>
<?php
}
?>
</table>			
	<br style="clear:both;" />
</div>
		