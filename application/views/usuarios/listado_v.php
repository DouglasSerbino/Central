

<script type="text/javascript">
$(function()
{
	$('#usu_vista').change(function(){ window.location = '/usuarios/listado/index/'+$(this).val(); });
});
</script>
<style type="text/css">
	.btn{
		border-radius: 8px;
		background-color: #bc933b;
		color: white;
		padding: 5px 15px;
		text-align: center;
		text-decoration: none;
	
	}
	.btn:hover, .btn:active {
  	background-color: lightblue;
	}
	.pull-right{
		float: right;
	}
	a:link
	{
		text-decoration:none;
	} 
</style>


Mostrando
<select id="usu_vista">
	<option value='s'>Activos</option>
	<option value='n'<?php echo ('n'==$Activo)?' selected="selected"':''; ?>>Inactivos</option>
</select>
<a class="btn pull-right" href="/usuarios/crear">Agregar Usuario</a>

<br />

<br>
<?php
foreach($Usuarios as $Id_Dpto => $Listado)
{
	//DSerbino: Lo que esta comentariado abajo lo escribio el programador anterior
	// if(
	// 	'Grupo Externo' == $Listado['dpto']//Esto es mas un problema ahora
	// 	|| 29 == $Id_Dpto//No miren al de sistemas
	// )
	// {
	// 	continue;
	// }
?>

<strong style="font-size: 15px;"><?php=$Listado['dpto']?></strong>
<table class="tabular" style="width:100%;">
	<!--tr>
		<th colspan="3" ><?=$Listado['dpto']?></th>
	</tr-->
	<tr>
		<th width="200px;">USUARIO</th>
		<th>NOMBRE</th>
		<th width="100px;">&nbsp;</th>
	</tr>
<?php
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