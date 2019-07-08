<table class="tabular" style="width:45%;">
    <tr>
				<th><strong>Grupo</strong></th>
        <th><strong>Codigo</strong></th>
        <th><strong>Departamento</strong></th>
        <th style="text-align:center;"><strong>Opcion</strong></th>
    </tr>

<?php
foreach($Departamentos as $Departamento)
{
?>
    <tr>
	<td colspan='4'><strong><?=$Departamento["nombre_grupo"]?></strong></td>
    </tr>
<?php
    foreach($Departamento['dptos'] as $Datos)
    {
	if($Datos['id_usu_preferencia'] == '')
	{
	    $Datos['id_usu_preferencia'] = 0;
	}
?>
    <tr>
	<td colspan='2'><p style='margin-left: 4em'><?=$Datos["codigo"]?></p></td>
	<td><?=$Datos["departamento"]?></td>
	<td>
<?php
if($Datos["activo"] == "s")
{
?>
	    <a href="/usuarios/preferencias/cotizacion/desactivar/<?=$Datos["id_dpto"]?>/<?=$Datos['id_grupo']?>/<?=$Datos['id_usu_preferencia']?>" class="iconos ieliminar toolder"><span>Desactivar Cotizacion</span></a>
<?php
}
elseif($Datos["activo"] == "n" or $Datos["activo"] == "")
{
?>
			<a href="/usuarios/preferencias/cotizacion/activar/<?=$Datos["id_dpto"]?>/<?=$Datos['id_grupo']?>/<?=$Datos['id_usu_preferencia']?>" class="iconos ireactivar toolder"><span>Reactivar Cotizacion</span></a>
<?php
}
?>
		</td>
	</tr>
<?php
		}
}
?>
</table>