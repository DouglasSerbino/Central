<table class="tabular" style="width:100%;">
    <tr>
        <th><strong>Codigo</strong></th>
        <th><strong>Departamento</strong></th>
        <th><strong>Tipo de inventario</strong></th>
        <th><strong>Cantidad Mensual</strong></th>
        <th><strong>Iniciales</strong></th>
        <th style="text-align:center;"><strong>Opciones</strong></th>
    </tr>
<?
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
<?
}
?>
</table>
