
<?

foreach($Menu as $Id_Menu => $Principal)
{
	$Direccion = 'desactivar';
	$Opcion_icono = ' class="iconos ieliminar toolder"><span>Desactivar Men&uacute;</span>';
	if('n' == $Principal['activo'])
	{
		$Direccion = 'activar';
		$Opcion_icono = ' class="iconos ireactivar toolder"><span>Reactivar Men&uacute;</span>';
	}
?>
<table class="tabular">
	<tr>
		<th style="width:300px;"><?=$Principal['etiqueta']?></th>
		<td style="width:300px;"><?=$Principal['enlace']?></td>
		<td>
<?
	/****************************/
	if('listado' == $Pagina)
	{//Este es el default, muestra el listados de los menus al acceder desde el menu :)
?>
			<a href="/menu/modificar/index/<?=$Id_Menu?>" class="iconos ieditar toolder"><span>Modificar Men&uacute;</span></a>
			<a href="/menu/activar_desactivar/<?=$Direccion?>/<?=$Id_Menu?>"<?=$Opcion_icono?></a>
<?
	}
	
	/****************************/
	if('asignar_departamento' == $Pagina)
	{//Muestra opciones para activar o desactivar los menus para un departamento dado
		if(isset($menu_activo[$Id_Menu]))
		{
			?>
			<a name="<?=$Id_Menu?>" href="/departamentos/modificar/desactivar_menu/<?=$Codigo.'/'.$Id_Menu?>" class="iconos ieliminar toolder"><span>Desactivar Me&uacute;</span></a>
			<?php
		}
		else
		{
			?>
			<a name="<?=$Id_Menu?>" href="/departamentos/modificar/asignar_menu/<?=$Codigo.'/'.$Id_Menu?>" class="iconos ireactivar toolder"><span>Reactivar Men&uacute;</span></a>
			<?php
		}
	}
	
	/****************************/
	if('usuario' == $Pagina)
	{//Muestra opciones para habilitar_deshabilitar menus en el usuario
		if(!isset($Menu_Dpto[$Id_Menu]))
		{//Solamente los menus que no correspondan han sido asignados a su departamento
			//le pueden ser habilitados o deshabilitados.
			
			if(isset($Menu_Usu[$Id_Menu]))
			{//Si este menu ya fue asignado al usuario, muestro opcion para deshabilitar
?>
			<a name="<?=$Id_Menu?>" href="/usuarios/modificar/desactivar_menu/<?=$Id_Usuario?>/<?=$Id_Menu?>" class="iconos ieliminar toolder"><span>Desactivar Me&uacute;</span></a>
<?
			}
			else
			{//Si este menu no esta asignado al usuario, muestro opcion para habilitar
?>
			<a name="<?=$Id_Menu?>" href="/usuarios/modificar/activar_menu/<?=$Id_Usuario?>/<?=$Id_Menu?>" class="iconos ireactivar toolder"><span>Reactivar Me&uacute;</span></a>
<?
			}
		}
		else
		{//Si este menu ya fue asignado a su departamento no es posible deshabilitarlo
			//para este usuario
		}
	}
	
?>
		</td>
	</tr>
<?
	foreach($Principal['submenu'] as $Id_sMenu => $SubMenu)
	{
		$Direccion = 'desactivar';
		$Opcion_icono = ' class="iconos ieliminar toolder"><span>Desactivar Men&uacute;</span>';
		if('n' == $SubMenu['activo'])
		{
			$Direccion = 'activar';
			$Opcion_icono = ' class="iconos ireactivar toolder"><span>Reactivar Men&uacute;</span>';
		}
?>
	<tr>
		<td><?=$SubMenu['etiqueta']?></td>
		<td><?=$SubMenu['enlace']?></td>
		<td>
<?
		/****************************/
		if('listado' == $Pagina)
		{//Este es el default, muestra el listados de los menus al acceder desde el menu :)
?>
			<a href="/menu/modificar/index/<?=$Id_sMenu?>" class="iconos ieditar toolder"><span>Modificar Me&uacute;</span></a>
			<a href="/menu/activar_desactivar/<?=$Direccion?>/<?=$Id_sMenu?>"<?=$Opcion_icono?></a>
<?
		}
		
		/****************************/
		if('asignar_departamento' == $Pagina)
		{//Muestra opciones para activar o desactivar los menus para un departamento dado
				if(isset($menu_activo[$Id_sMenu]))
				{
			?>
			<a name="<?=$Id_sMenu?>" href="/departamentos/modificar/desactivar_menu/<?=$Codigo.'/'.$Id_sMenu?>" class="iconos ieliminar toolder"><span>Desactivar Me&uacute;</span></a>
			<?php
				}
				else
				{
					?>
			<a name="<?=$Id_sMenu?>" href="/departamentos/modificar/asignar_menu/<?=$Codigo.'/'.$Id_sMenu?>" class="iconos ireactivar toolder"><span>Reactivar Me&uacute;</span></a>
					<?php
				}
		}
		
		/****************************/
		if('usuario' == $Pagina)
		{//Muestra opciones para habilitar_deshabilitar menus en el usuario
			if(!isset($Menu_Dpto[$Id_sMenu]))
			{//Solamente los menus que no correspondan han sido asignados a su departamento
				//le pueden ser habilitados o deshabilitados.
				
				if(isset($Menu_Usu[$Id_sMenu]))
				{//Si este menu ya fue asignado al usuario, muestro opcion para deshabilitar
?>
			<a name="<?=$Id_sMenu?>" href="/usuarios/modificar/desactivar_menu/<?=$Id_Usuario?>/<?=$Id_sMenu?>" class="iconos ieliminar toolder"><span>Desactivar Me&uacute;</span></a>
<?
				}
				else
				{//Si este menu no esta asignado al usuario, muestro opcion para habilitar
?>
			<a name="<?=$Id_sMenu?>" href="/usuarios/modificar/activar_menu/<?=$Id_Usuario?>/<?=$Id_sMenu?>" class="iconos ireactivar toolder"><span>Reactivar Me&uacute;</span></a>
<?
				}
			}
			else
			{//Si este menu ya fue asignado a su departamento no es posible deshabilitarlo
				//para este usuario
			}
		}
?>
		</td>
	</tr>
<?
	}
?>
</table>
<br />
<?
}
?>
