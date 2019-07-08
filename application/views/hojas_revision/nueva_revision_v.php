

<table>
	<tr>
		<td>Proceso:</td>
		<th><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></th>
	</tr>
	<tr>
		<td>Cliente:</td>
		<th><?=$Info_Proceso['nombre']?></th>
	</tr>
	<tr>
		<td>Producto</td>
		<th><?=$Info_Proceso['nombre_proceso']?></th>
	</tr>
</table>




<form name="revisa-form" id="revisa-form" action="/hojas_revision/nueva_revision/revisar/<?=$Id_Pedido?>" method="post">


<?php
foreach ($Items_Revision as $Index => $Item)
{
?>
<br />
<table style="width: 75%;" class="tabular">
	<tr>
		<th style="width: 80%;"><?=$Item['item']?></th>
		<th>Revisado</th>
	</tr>
<?php
	foreach ($Item['sub_item'] as $SubIndex => $Sub_Item)
	{
?>
	<tr>
		<td><?=$Sub_Item['sub_item']?></td>
		<td>
			<select name="item_<?=$SubIndex?>">
				<option value="--">--</option>
				<option value="OK">OK</option>
				<option value="N/A">N/A</option>
			</select>
		</td>
	</tr>
<?php
	}
?>
</table>
<?php
}
?>


<br />


<strong>OBSERVACIONES</strong>
<br />
<textarea name="rev_observaciones" cols="85" rows="10"></textarea>

<br />
<input type="button" value="Finalizar Revisi&oacute;n" onclick="validar_hoja()" />

</form>


<br />




<script>
	
	function validar_hoja(){
		
		var continuar = true;
		$('select').each(function()
		{
			if('--' == $(this).val())
			{
				continuar = false;
			}
		});
		
		
		if(!continuar)
		{
			alert('Aun hay items sin revisar');
			return false;
		}
		
		
		
		if(confirm('La hoja de revision sera guardada.\n\rDesea continuar?'))
		{
			$('#revisa-form').submit();
		}
		
	}
	
</script>


