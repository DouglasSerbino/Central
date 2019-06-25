

<script type="text/javascript">
$(function()
{
	$('#usu_vista').change(function(){ window.location = '/usuarios/listado/index/'+$(this).val(); });
});
</script>


Mostrando
<select id="usu_vista">
	<option value='s'>Activos</option>
	<option value='n'<? echo ('n'==$Activo)?' selected="selected"':''; ?>>Inactivos</option>
</select>

<br />


<?php
foreach($Usuarios as $Id_Dpto => $Listado)
{
	if(
		'Grupo Externo' == $Listado['dpto']//Esto es mas un problema ahora
		|| 29 == $Id_Dpto//No miren al de sistemas
	)
	{
		continue;
	}
?>

<strong style="font-size: 15px;"><?=$Listado['dpto']?></strong>
<table class="tabular" style="width:100%;">
	<!--tr>
		<th colspan="3" ><?=$Listado['dpto']?></th>
	</tr-->
	<tr>
		<th width="200px;">USUARIO</th>
		<th>NOMBRE</th>
		<th width="100px;">&nbsp;</th>
	</tr>
<?
	foreach ($Listado['usuarios'] as $Id_Usuario => $Usuario)
	{
		if(false !== strpos($Usuario['nombre'], '(Gen&eacute;rico'))
		{
			continue;
		}
?>
	<tr>
		<td><?=$Usuario['usuario']?></td>
		<td><?=$Usuario['nombre']?></td>
		<td>
			<a href="/usuarios/modificar/index/<?=$Id_Usuario?>" class="iconos ieditar toolder"><span>Modificar Usuario</span></a>
<?php
		if('s' == $Usuario['activo'])
		{
?>
			<a href="/usuarios/activar_desactivar/acciones/n/<?=$Id_Usuario?>" class="iconos ieliminar toolder"><span>Desactivar Usuario</span></a>
<?php
		}
		else
		{
?>
			<a href="/usuarios/activar_desactivar/acciones/s/<?=$Id_Usuario?>" class="iconos ireactivar toolder"><span>Reactivar Usuario</span></a>
<?php
		}
?>
		</td>
	</tr>
<?php
	}
?>
</table>
<br />

<?php
}
?>