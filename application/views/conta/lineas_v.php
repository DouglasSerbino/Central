
<style>
	/*background: #EACFB9;
	background: #E8BAB9;
	background: #E8E6B9;
	background: #BFE8B9;
	background: #B9B9E8;
	background: #E8B9E5;
	background: #e8efe2;
	background: #999999;
	background: #aaaaaa;
	background: #bbbbbb;*/
</style>


<div style="height: 125px;">

<form id="mc_agregar_linea" action="/conta/lineas/agregar" method="post" onsubmit="return validar_mc_linea();">
	<strong>Agregar L&iacute;nea</strong>
	<table>
		<tr>
			<td>C&oacute;digo</td>
			<td>L&iacute;nea</td>
			<td>Anidamiento</td>
			<td>Tipo</td>
		</tr>
		<tr>
			<td><input type="text" name="mc_codigo" id="mc_codigo" size="4" /></td>
			<td><input type="text" name="mc_linea" id="mc_linea" size="30" /></td>
			<td>
				<input type="text" id="mc_nivel_ac" agr_mod="" value="Principal" />
				<input type="hidden" name="mc_nivel" id="mc_nivel" value="0" />
			</td>
			<td>
				<select name="mc_tipo" id="mc_tipo">
					<option value="+">+</option>
					<option value="-">-</option>
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" value="Guardar" />
	<input type="button" value="Limpiar" />
</form>






<form id="mc_form_modificar_linea" action="/conta/lineas/modificar" method="post" onsubmit="return validar('mc_form_modificar_linea');" style="display: none;">
	<strong>Modificar L&iacute;nea</strong>
	<table>
		<tr>
			<td>C&oacute;digo</td>
			<td>L&iacute;nea</td>
			<td class="lin_padre_mod">Anidamiento</td>
			<td>Tipo</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="mc_modificar_codigo" id="mc_modificar_codigo" size="4" class="requ" />
				<input type="hidden" name="mc_modificar_id" id="mc_modificar_id" />
			</td>
			<td><input type="text" name="mc_modificar_linea" id="mc_modificar_linea" size="30" class="requ" /></td>
			<td class="lin_padre_mod">
				<input type="text" id="mc_modificar_nivel_ac" agr_mod="modificar_" />
				<input type="hidden" name="mc_modificar_nivel" id="mc_modificar_nivel" />
			</td>
			<td>
				<select name="mc_modificar_tipo" id="mc_modificar_tipo">
					<option value="+">+</option>
					<option value="-">-</option>
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" value="Modificar" />
	<input type="button" value="Cancelar" />
</form>


</div>




<strong>Listado de L&iacute;neas</strong>
<table class="tabular" id="mc_listado_lineas" style="width: 75%">
	<tr>
		<th style="width: 15%;">C&Oacute;DIGO</th>
		<th>L&Iacute;NEA</th>
		<th style="width: 10%;">&nbsp;</th>
	</tr>
<?
$Lineas_JS = array();
$Lineas_Autocomplete = array();


recorrer_lineas($Lineas[0], 0, $Lineas, $Lineas_JS, $Lineas_Autocomplete, 0);

function recorrer_lineas(
	$Recorrer,
	$Id_Padre,
	array & $Lineas,
	array & $Lineas_JS,
	array & $Lineas_Autocomplete,
	$Gris
)
{
	foreach($Recorrer as $Id_Mc_Linea => $Datos)
	{
		
		$Hijos = false;
		if(isset($Lineas[$Id_Mc_Linea]))
		{
			
			$Hijos = true;
			
			recorrer_lineas(
				$Lineas[$Id_Mc_Linea],
				$Id_Mc_Linea,
				$Lineas,
				$Lineas_JS,
				$Lineas_Autocomplete,
				($Gris + 1)
			);
			
		}
		
		poner_datos($Id_Padre, $Id_Mc_Linea, $Datos, $Gris, $Hijos);
		
		$Lineas_JS[$Id_Mc_Linea] = $Datos['codigo'].' - '.$Datos['linea'];
		$Lineas_Autocomplete[] = array(
			'value' => $Id_Mc_Linea,
			'label' => $Datos['codigo'].' - '.$Datos['linea']
		);
		
	}
}




function poner_datos($Id_Padre, $Id_Mc_Linea, $Datos, $Gris, $Hijos)
{
	$Grises = array(
		'CCCCCC',
		'EACFB9',
		'E8BAB9',
		'E8E6B9',
		'BFE8B9',
		'B9B9E8',
		'E8B9E5',
		'E8B9E5'
	);
	
	$Background = $Grises[$Gris];
	
	if(!$Hijos)
	{
		$Background = 'FFFFFF';
	}
?>
	<tr style="background: #<?=$Background?>">
		<td><?=$Datos['codigo']?></td>
		<td>[<?=$Datos['mas_menos']?>] <?=$Datos['linea']?></td>
		<td>
			<span info="<?=$Id_Mc_Linea?>" class="iconos ieditar toolder"><span>Modificar L&iacute;nea</span></span>
			<span info="<?=$Id_Mc_Linea?>" class="iconos ieliminar toolder"><span>Desactivar L&iacute;nea</span></span>
			<input type="hidden" id="info-<?=$Id_Mc_Linea?>" value="<?=$Id_Padre.'[-]'.$Id_Mc_Linea.'[-]'.$Datos['codigo'].'[-]'.$Datos['linea'].'[-]'.$Datos['mas_menos']?>" />
		</td>
	</tr>
<?
}
?>
</table>
<br />




<script>
	
	var Agr_Mod = '';
	var Id_Anterior = '';
	var Texto_Anterior = '';
	var Lineas_JS = <?=json_encode($Lineas_JS)?>;
	var Lineas_AutoComplete = <?=json_encode($Lineas_Autocomplete)?>;
	
	Lineas_JS[0] = 'Principal';
	Lineas_AutoComplete.push({"value":0,"label":"Principal"});
	
	$('#mc_nivel_ac').on('focus', function()
	{
		Id_Anterior = $('#mc_nivel').val();
		Texto_Anterior = $(this).val();
	});
	
	$('#mc_nivel_ac').on('blur', function()
	{
		restablece_autocompleta('');
	});
	
	$('#mc_nivel_ac').keyup(function(e)
	{
		if(27 == e.which)
		{
			restablece_autocompleta('');
		}
	});
	
	function restablece_autocompleta(Tipo)
	{
		if('--' == $('#mc_'+Tipo+'nivel').val())
		{
			$('#mc_'+Tipo+'nivel').val(Id_Anterior);
			$('#mc_'+Tipo+'nivel_ac').val(Texto_Anterior);
		}
	}
	
	$('#mc_nivel_ac').autocomplete({//, '#mc_modificar_nivel_ac'
		source: Lineas_AutoComplete,
		select: function(event, ui)
		{
			$('#mc_nivel').val(ui.item.value);
			$('#mc_nivel_ac').val($('#atrapa').empty().append(ui.item.label).text());
			Id_Anterior = $('#mc_nivel').val();
			Texto_Anterior = $('#mc_nivel_ac').val();
			return false;
		},
		search: function(event, ui)
		{
			$('#mc_nivel').val('--');
		},
		focus: function(event, ui)
		{
			$('#mc_nivel_ac').val($('#atrapa').empty().append(ui.item.label).text());
			return false;
		}
	}).data('autocomplete')._renderItem = function(ul, item)
	{
		return $('<li></li>')
		.data('item.autocomplete', item)
		.append('<a>'+item.label+'</a>')
		.appendTo(ul);
	};
	
	
	
	
	
	
	
	$('#mc_modificar_nivel_ac').on('focus', function()
	{
		Id_Anterior = $('#mc_modificar_nivel').val();
		Texto_Anterior = $(this).val();
	});
	
	$('#mc_modificar_nivel_ac').on('blur', function()
	{
		restablece_autocompleta('modificar_');
	});
	
	$('#mc_modificar_nivel_ac').keyup(function(e)
	{
		if(27 == e.which)
		{
			restablece_autocompleta('modificar_');
		}
	});
	
	$('#mc_modificar_nivel_ac').autocomplete({//, '#'
		source: Lineas_AutoComplete,
		select: function(event, ui)
		{
			$('#mc_modificar_nivel').val(ui.item.value);
			$('#mc_modificar_nivel_ac').val($('#atrapa').empty().append(ui.item.label).text());
			Id_Anterior = $('#mc_modificar_nivel').val();
			Texto_Anterior = $('#mc_modificar_nivel_ac').val();
			return false;
		},
		search: function(event, ui)
		{
			$('#mc_modificar_nivel').val('--');
		},
		focus: function(event, ui)
		{
			$('#mc_modificar_nivel_ac').val($('#atrapa').empty().append(ui.item.label).text());
			return false;
		}
	}).data('autocomplete')._renderItem = function(ul, item)
	{
		return $('<li></li>')
		.data('item.autocomplete', item)
		.append('<a>'+item.label+'</a>')
		.appendTo(ul);
	};
	
	function validar_mc_linea()
	{
		
		$('#mc_codigo').val($.trim($('#mc_codigo').val()));
		$('#mc_linea').val($.trim($('#mc_linea').val()));
		
		if('' == $('#mc_codigo').val() || '' == $('#mc_linea').val())
		{
			alert('Por favor completar todos los campos');
			return false;
		}
		
		if(confirm('La informacion sera almacenada. Desea continuar?'))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	
	$('#mc_agregar_linea [type="button"]').click(function()
	{
		limpiar_linea();
	});
	
	
	
	$('#mc_listado_lineas .ieliminar').click(function()
	{
		if(0 < $('.imcl_'+$(this).attr('info')).length)
		{
			alert('Esta linea no puede eliminarse porque tiene otras lineas anidadas');
			return false;
		}
		
		if(confirm('Esta seguro que desea eliminar esta linea?'))
		{
			window.location = '/conta/lineas/eliminar/'+$(this).attr('info');
		}
	});
	
	
	$('#mc_listado_lineas .ieditar').click(function()
	{
		
		Agr_Mod = 'modificar_';
		
		$('#mc_agregar_linea').hide();
		$('#mc_form_modificar_linea').show('blind');
		$('#mc_modificar_codigo').focus();
		
		var Info_Linea = $('#info-'+$(this).attr('info')).val();
		Info_Linea = Info_Linea.split('[-]');
		
		
		$('#mc_modificar_nivel').val(Info_Linea[0]);
		$('#mc_modificar_nivel_ac').val($('#atrapa').empty().append(Lineas_JS[Info_Linea[0]]).text());
		$('#mc_modificar_id').val(Info_Linea[1]);
		$('#mc_modificar_codigo').val(Info_Linea[2]);
		$('#mc_modificar_linea').val(Info_Linea[3]);
		$('#mc_modificar_tipo').val(Info_Linea[4]);
		
	});
	
	
	$('#mc_form_modificar_linea [type="button"]').click(function()
	{
		Agr_Mod = '';
		$('#mc_form_modificar_linea').hide();
		$('#mc_agregar_linea').show('blind');
		limpiar_linea();
	});
	
	function limpiar_linea()
	{
		$('#mc_agregar_linea [type="text"]').val('');
		$('#mc_nivel').val(0);
		$('#mc_nivel_ac').val('Principal');
		$('#mc_tipo').val('+');
		$('#mc_codigo').focus();
	}
	
	
	$('#mc_codigo').focus();
	
</script>


