<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<div class="informacion">
	<form name="miform" method="post" action="/planchas/plancha_tipo/agregar_proveedor" onsubmit="return validar_b();">
		
		Digite el nombre del Tipo de Planchas: &nbsp; 
		<input type="text" name="nombre" /> &nbsp; 
		<input type="submit" class="boton" value="Agregar" />
		
	</form>
	
	<hr width="80%" />
	
	<table class='tabular' style='margin-left: 100px; width: 40%;'>
		<tr>
			<th> &nbsp; Listado de Tipos de Planchas &nbsp; </th>
			<th>&nbsp;</th>
		</tr>
<?
//=============Mostrar proveedores existentes==================

$i = 1;

foreach($plancha_tipo as $Datos)
{
?>
		<tr>
			<td><?=$Datos["nombre_tipo"]?></td>
			<td>
				<!--a href="/planchas/plancha_tipo/index/<?=$Datos["cod_tipo"]?>" class="iconos ieditar toolder"><span>Modificar Tipo de Plancha</span></a-->
				<span codp="<?=$Datos["cod_tipo"]?>" class="iconos ieliminar toolder"><span>Eliminar Tipo de Plancha</span></span>
			</td>
		</tr>
<?php	
	$i++;
}
?>
	</table>
</div>


<script>
	$('.ieliminar').click(function()
	{
		if(confirm('Esta Tipo de Plancha sera eliminada y todo el historial relacionado a ella. Desea Continuar?'))
		{
			window.location = '/planchas/plancha_tipo/eliminar/'+$(this).attr('codp');
		}
	});
</script>