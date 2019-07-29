<style>
	input[type="checkbox"]
	{
		visibility: hidden;
		margin-left: -15px;
	}
</style>
<form id="modificar_menu" action="/menu/modificar/menu/<?=$Informacion[0]['id_menu']?>" method="post">
	
	<table>
		<tr>
			<td>Etiqueta</td>
			<td><input type="text" name="etiqueta" value="<?=$Informacion[0]['etiqueta']?>" /></td>
		</tr>
		<tr>
			<td>Men&uacute;</td>
			<td>
				<select name="grupo">
					<option value="0">Principal</option>
<?php
foreach($Menu_Padre as $Menu)
{
?>
					<option value="<?=$Menu['id_menu']?>"<?php if($Menu['id_menu'] == $Informacion[0]['id_menu_padre']) echo ' selected="selected"'; ?>><?=$Menu['etiqueta']?></option>
<?php
}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Enlace</td>
			<td><input type="text" name="enlace" size="65" value="<?=$Informacion[0]['enlace']?>" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Modificar" /></td>
		</tr>
	</table>
	
</form>	
	
<style>
	#sortable { float: left; background: #f4f4f4; width: 290px; margin-right: 15px; padding-bottom: 10px; }
	#draggable { float: left; width: 610px; }
	#sortable li{ margin: 0px; padding: 2px; border: 1px solid #ddd; background: #f4f4f4; cursor: n-resize; }
	#draggable li { margin: 0px; padding: 2px; width: 194px; border: 1px solid #ddd; float: left; cursor: move; }
	#draggable span { display: none; }
	#sortable span { display: inline; cursor: pointer;margin-right: 2px; }
	select { font-size: 13px; }
</style>



<!--form action="/menu/modificar/ensamblar" method="post" id="formacceso">
	
	<input type="hidden" value="" name="ruta" />
	<input type='hidden' value='<?=$Informacion[0]['id_menu']?>' name='id_menu'>
	
	<input type="button" value="Almacenar Accesos" />
</form>

<div class="m10"></div>


<ul id="sortable">
	<li class="disabled"><strong>Agregar Elementos</strong></li>

<?phpphp
/*foreach($Extraer_accesos as $Datos)
{
?>
	<li class="dp_<?=$Datos['id_dpto']?>"><span>[x]</span><?=$Datos['departamento']?></li>
<?phpphp
}*/
?>
	
</ul>

<ul id="draggable">
<?php
foreach($Departamentos as $Dpto)
{
?>
	<li class="dp_<?=$Dpto['id_dpto']?>"><span>[x]</span><?=$Dpto['departamento']?></li>
<?php
}
?>
</ul-->
<div class="limpiar m10"></div>
</form>

<script>
$(function() {
	$("#sortable").sortable({
		revert: true,
		items: "li:not(.disabled)"
	});
	$("#draggable li").draggable({
		connectToSortable: "#sortable",
		helper: "clone",
		revert: "invalid"
	});
	$("ul, li").disableSelection();
	$("#sortable span").live('click', function(){ $(this).parent().remove(); })
	$("[type=button]").click(function(){ enviar_ruta(); });
});

function enviar_ruta()
{	
	var departamento = '';
	var clase = '';
	
	$('#sortable li').each(function()
	{
		if('disabled' != $(this).attr('class'))
		{
			if('' != departamento)
			{
				departamento += ',';
			}
			clase = $(this).attr('class');
			clase = clase.split(' ');
			departamento += clase[0];
		}
	})
	
	if(!confirm("La ruta ser\xe1 almacenada.\r\nDesea continuar?"))
	{
		return false;
	}
	
	$('[name=ruta]').val(departamento);
	
	alert(departamento);
	$('#formacceso').submit();
	
}
</script>
