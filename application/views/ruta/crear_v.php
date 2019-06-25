
<style>
	#sortable { float: left; background: #f4f4f4; width: 290px; margin-right: 15px; padding-bottom: 10px; }
	#draggable { float: left; width: 610px; }
	#sortable li{ margin: 0px; padding: 2px; border: 1px solid #ddd; background: #f4f4f4; cursor: n-resize; }
	#draggable li { margin: 0px; padding: 2px; width: 194px; border: 1px solid #ddd; float: left; cursor: move; }
	#draggable span { display: none; }
	#sortable span { display: inline; cursor: pointer;margin-right: 2px; }
	select { font-size: 13px; }
</style>

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
	<? /*
	ESTE METODO FUNCIONA PARA UNIR CELDAS EN VIVO
	$('.td2').click(function(){ $('.td4').remove(); $(this).attr('rowspan', '2'); })
	*/ ?>
});

function enviar_ruta()
{
	
	if('0' == $('[name=sl_grupos]').val())
	{
		alert("Favor especificar el grupo al que ser\xe1 asignada esta ruta de trabajo.");
		return false;
	}
	
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
	
	if('' == departamento)
	{
		alert("No ha agregado elementos al tablero.\r\nFavor seleccionar los departamentos que interactuan en esta ruta de trabajo.");
		return false;
	}
	
	if(!confirm("La ruta ser\xe1 almacenada.\r\nDesea continuar?"))
	{
		return false;
	}
	
	$('[name=ruta]').val(departamento);
	
	$('#fr_ruta').submit();
	
}
</script>


<?
/*
ESTE METODO FUNCIONA PARA UNIR CELDAS EN VIVO
<table>
	<tr>
		<td class="td1">asd</td>
		<td class="td2">fsda fsda fsda<br />fsda fsda fsda fsda fsda fsda</td>
	</tr>
	<tr>
		<td class="td3">asd</td>
		<td class="td4">fas</td>
	</tr>
</table>
*/?>


<form action="/ruta/crear/ensamblar" method="post" id="fr_ruta">
	
	<select name="sl_grupos">
		<option value="0">Seleccionar Grupo</option>
<?
foreach($Grupos as $Grupo)
{
?>
		<option value="<?=$Grupo['id_grupo']?>"<? echo (isset($Ruta_Grupo[$Grupo['id_grupo']]))?' disabled="disabled"':''; ?>><?=$Grupo['nombre_grupo']?></option>
<?
}
?>
	</select>
	
	<input type="hidden" value="" name="ruta" />
	
	<input type="button" value="Guardar Ruta" />
	
</form>

<div class="m10"></div>


<ul id="sortable">
	<li class="disabled"><strong>Agregar Elementos</strong></li>
</ul>


<ul id="draggable">
<?
foreach($Departamentos as $Dpto)
{
?>
	<li class="dp_<?=$Dpto['id_dpto']?>"><span>[x]</span><?=$Dpto['departamento']?></li>
<?
}
?>
</ul>



<div class="limpiar m10"></div>



