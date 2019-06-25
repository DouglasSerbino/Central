
<div id="agregar_item">

	<strong>AGREGAR ITEM</strong>
	<br />
	Item <input type="text" id="revision_item" size="30" />

	&nbsp; &nbsp;
	Nivel
	<select id="revision_nivel">
		<option value="0">Principal</option>
	<?
	foreach ($Items_Revision as $Index => $Item)
	{
	?>
		<option value="<?=$Index?>"><?=$Item['item']?></option>
	<?
	}
	?>
	</select>

	&nbsp; &nbsp;
	<input type="button" value="Agregar" id="revision_agregar" />

</div>



<div id="modificar_item" style="display:none;">
	
	<strong>MODIFICAR ITEM</strong>
	<br />
	Item <input type="text" id="modit_item" size="50" />
	<input type="hidden" id="modit_id" />
	<input type="hidden" id="modit_tipo" />
	<input type="hidden" id="modit_dpto" value="<?=$Id_Dpto?>" />

	&nbsp; &nbsp;
	<input type="button" value="Modificar" id="revision_modificar" />
	<input type="button" value="Cancelar" id="revision_cancelar" />

</div>




<br /><br />
<strong>HOJA DE REVISI&Oacute;N</strong>
<br />

<?
foreach ($Items_Revision as $Index => $Item)
{

?>
<br />
<table>
	<tr>
		<td><strong>&raquo; <?=$Item['item']?></strong></td>
		<td>
			&nbsp; <span tipo="item" id="<?=$Index?>" texto="<?=$Item['item']?>" class="iconos ieditar toolder"><span>Modificar Item</span></span>
<?
	if(0 == count($Item['sub_item']))
	{
?>
			&nbsp; <a href="/hojas_revision/nueva_revision/eliminar/item/<?=$Index?>/<?=$Id_Dpto?>" class="iconos ieliminar toolder"><span>Eliminar Item</span></a>
<?
	}

?>
		</td>
	</tr>
<?
	foreach ($Item['sub_item'] as $SubIndex => $Sub_Item)
	{
?>
	<tr>
		<td>&nbsp; &bullet; <?=$Sub_Item['sub_item']?></td>
		<td>
			&nbsp; <span tipo="sub" id="<?=$SubIndex?>" texto="<?=$Sub_Item['sub_item']?>" class="iconos ieditar toolder"><span>Modificar Item</span></span>
			&nbsp; <a href="/hojas_revision/nueva_revision/eliminar/sub/<?=$SubIndex?>/<?=$Id_Dpto?>" class="iconos ieliminar toolder"><span>Eliminar Item</span></a>
		</td>
	</tr>
<?
	}
?>
</table>
<?

}
?>


<br />


<script>
	
	$('#revision_agregar').click(function()
	{

		$.ajax({
			type: "POST",
			url: "/hojas_revision/nueva_revision/agregar",
			data: 'dpto=<?=$Id_Dpto?>&item='+$('#revision_item').val()+'&nivel='+$('#revision_nivel').val(),
			success: function(msg)
			{
				if('ok' == msg)
				{
					location.reload();
				}
				else{ alert("Ocurrio un error: "+msg+".\r\n Haga una captura de pantalla para realizar una verificacion del problema."); }
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".\r\n Haga una captura de pantalla para realizar una verificacion del problema."); }
		});

	});


	$('.ieditar').click(function()
	{
		$('#modit_id').val($(this).attr('id'));
		$('#modit_tipo').val($(this).attr('tipo'));
		$('#modit_item').val($(this).attr('texto'));

		$('#agregar_item').hide();
		$('#modificar_item').show();

		$('#modit_item').focus();
	});

	$('#revision_cancelar').click(function()
	{
		$('#agregar_item').show();
		$('#modificar_item').hide();
	});

	$('#revision_modificar').click(function()
	{
		$.ajax({
			type: "POST",
			url: "/hojas_revision/nueva_revision/modificar",
			data: 'id='+$('#modit_id').val()+'&tipo='+$('#modit_tipo').val()+'&item='+$('#modit_item').val(),
			success: function(msg)
			{
				if('ok' == msg)
				{
					location.reload();
				}
				else{ alert("Ocurrio un error: "+msg+".\r\n Haga una captura de pantalla para realizar una verificacion del problema."); }
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".\r\n Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	});

</script>





