
<style>
	.objetivos{
		width:100%;
		font-size:10px;
		margin-bottom: 2px;
	}
	.objetivos th, td{
		padding: 0px;
	}
	.objetivos th{
		background: #D0D0D0;
		border: 1px solid #999999;
	}
	.objetivos td{
		border: 1px solid #cccccc;
	}
	.objetivos .sobre:hover{
		cursor: pointer;
		text-decoration: underline;
	}
	.objetivos th.mes{
		text-align: center;
	}
	.objetivos td.mes{
		text-align: right;
		width: 45px;
	}
	.objetivos td.mes:hover{
		color: #000000;
	}
	.objetivos td.mes span{
		display: block;
	}
	.objetivos input{
		margin: 0px;
		width: 45px;
		height: 16px;
		padding: 0px;
		border: none;
		font-weight: normal;
		text-align: right;
		font-size: 10px;
	}
	.objetivos input:focus{
		border: none;
		color: #000000;
		background: #e6eaed;
	}
	.detalle{
		font-weight: bold;
	}
	#guarda_valores{
		margin-top: -15px;
	}
</style>



<div>
	
	Mostrando
	<select id="bsc_tipo">
		<option value="proyeccion"<?=('proyeccion'==$Tipo_Objetivo)?' selected="selected"':''?>>Proyectado</option>
		<option value="real"<?=('real'==$Tipo_Objetivo)?' selected="selected"':''?>>Real</option>
	</select>
	
	<input type="text" size="5" id="bsc_anho" value="<?=$Ver_Anho?>" />
	
	<input type="button" value="Cambiar" class="mostrar_info" />
	
</div>


	
<div id="tablas_objetivos">

<?php
foreach($Def_Objetivos as $Id_Perspectiva => $Perspectiva)
{
?>
<table class="objetivos tabla_<?=$Id_Perspectiva?>">
	<tr>
		<th colspan="15">
			<span obj="<?=$Id_Perspectiva?>" class="iconos ieditar toolizq modi_obje"<?=(0==$Id_Perspectiva)?' style="display:none;"':''?>><span>Modificar Objetivo</span></span>
			<span obj="<?=$Id_Perspectiva?>" class="iconos ieliminar toolizq eli_obje"<?=(0==$Id_Perspectiva)?' style="display:none;"':''?>><span>Eliminar Objetivo</span></span>
			<?=$Perspectiva['Nom']?> 
			<input type="hidden" value="+[co;0[co;<?=$Perspectiva['Nom']?>[co;" id="hi_<?=$Id_Perspectiva?>" />
		</th>
	</tr>
	<tr class="detalle">
		<td style="width: 190px;">Objetivos Estrat&eacute;gicos</td>
		<td>Indicadores</td>
<?php
	foreach($Meses as $iMes => $nMes)
	{
?>
		<td><?=$nMes?></td>
<?php
	}
?>
	</tr>
<?php
	foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
	{
?>
	<tr id="obj_<?=$Id_Bsc_Objetivo?>" obj="<?=$Id_Bsc_Objetivo?>">
		<td class="sobre">
			<span obj="<?=$Id_Bsc_Objetivo?>" class="iconos ieditar toolizq modi_obje"><span>Modificar Objetivo</span></span>
			<span obj="<?=$Id_Bsc_Objetivo?>" class="iconos ieliminar toolizq eli_obje"><span>Eliminar Objetivo</span></span>
			<a href="/balance/grafica/index/<?=$Id_Bsc_Objetivo?>/<?=$Ver_Anho?>" class="iconos iexterna toolizq" target="obj_graf"><span>Ver Gr&aacute;fica en ventana externa</span></a>
			<?=$Objetivo['Nom']?> 
			<input type="hidden" value="<?=$Objetivo['Con']?>[co;<?=$Id_Perspectiva?>[co;<?=$Objetivo['Nom']?>[co;<?=$Objetivo['Ind']?>" id="hi_<?=$Id_Bsc_Objetivo?>" />
		</td>
		<td>[<?=$Objetivo['Con']?>] <?=$Objetivo['Ind']?></td>
<?php
		foreach($Datos[$Id_Bsc_Objetivo] as $Dato)
		{
?>
		<td class="mes"><!--span><?=$Dato?></span--><input type="text" value="<?=$Dato?>" /></td>
<?php
		}
?>
	</tr>
<?php
	}
?>
</table>
<?php
}
?>

</div>


<br />
<input type="button" value="Guardar Informaci&oacute;n" id="guarda_valores" />




<div id="modifica_objetivo_bsc" style="display: none;">
<br /><br />
<strong>Modificar Objetivo</strong>
<table>
	<tr>
		<td>Condici&oacute;n:</td>
		<td>
			<select id="mod_condicion">
				<option value="+">+</option>
				<option value="-">-</option>
			</select>
			<input type="hidden" id="mod_i_objetivo" size="40" />
		</td>
	</tr>
	<tr>
		<td>Grupo:</td>
		<td>
			<select id="mod_pertenece">
				<option value="0">Principal</option>
<?php
foreach($Def_Objetivos as $Id_Bsc_Objetivo => $Perspectiva)
{
?>
				<option value="<?=$Id_Bsc_Objetivo?>"><?=$Perspectiva['Nom']?></option>
<?php
}
?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Objetivo:</td>
		<td><input type="text" id="mod_objetivo" size="40" /></td>
	</tr>
	<tr>
		<td>Indicador: &nbsp; &nbsp;</td>
		<td><input type="text" id="mod_indicador" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="button" value="Cancelar" onclick="$('#modifica_objetivo_bsc').hide()" />
			<input type="button" value="Modificar" onclick="modificar_objetivo()" />
		</td>
	</tr>
</table>
</div>








<br /><br />
<strong>Agregar Objetivo</strong>
<table>
	<tr>
		<td>Condici&oacute;n:</td>
		<td>
			<select id="condicion">
				<option value="+">+</option>
				<option value="-">-</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Grupo:</td>
		<td>
			<select id="pertenece">
				<option value="0">Principal</option>
<?php
foreach($Def_Objetivos as $Id_Bsc_Objetivo => $Perspectiva)
{
?>
				<option value="<?=$Id_Bsc_Objetivo?>"><?=$Perspectiva['Nom']?></option>
<?php
}
?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Objetivo:</td>
		<td><input type="text" id="objetivo" size="40" /></td>
	</tr>
	<tr>
		<td>Indicador: &nbsp; &nbsp;</td>
		<td><input type="text" id="indicador" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="button" value="Agregar" onclick="agregar_objetivo($(this))" /></td>
	</tr>
</table>




<script>
	
	$('.objetivos td .iconos, .objetivos th .iconos').hide();
	
	$('.objetivos th, .objetivos td').click(function()
	{
		$(this).children('.iconos').toggle();
	});
	
	
	$('.mostrar_info').click(function()
	{
		window.location = '/balance/objetivos/index/'+$('#bsc_tipo').val()+'/'+$('#bsc_anho').val();
	});
	
	$('.objetivos input').live('keypress', function(e)
	{
		if((e.which < 48 || e.which > 57) && 46 != e.which && 45 != e.which)
		{
			return false;
		}
	});
	
	$('.objetivos input').live('blur', function(e)
	{
		$(this).val(parseFloat($(this).val()));
	});
	
	
	
	function agregar_objetivo(botonillo)
	{
		
		botonillo.attr('disabled', true);
		botonillo.val('Procesando...');
		
		var objetivo = $('#objetivo').val();
		objetivo = objetivo.replace(/&/g,' ');
		var indicador = $('#indicador').val();
		indicador = indicador.replace(/&/g,' ');
		
		objetivo = $.trim(objetivo);
		indicador = $.trim(indicador);
		
		if('' == objetivo && '' == indicador)
		{
			alert('Favor complete todos los campos.');
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "/balance/objetivos/agregar",
			data: 'objetivo='+objetivo+'&indicador='+indicador+'&pertenece='+$('#pertenece').val()+'&condicion='+$('#condicion').val(),
			success: function(msg)
			{
				if(!isNaN(msg))
				{
					location.reload();
				}
				else
				{
					botonillo.attr('disabled', false);
					botonillo.val('Agregar');
					alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
				}
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	}
	
	
	
	
	
	
	
	$('.modi_obje').live('click', function()
	{
		$('#mod_i_objetivo').val($(this).attr('obj'));
		var objetivo_datos = $('#hi_'+$(this).attr('obj')).val();
		objetivo_datos = objetivo_datos.split('[co;');
		$('#mod_condicion option').attr('selected', false);
		$('#mod_condicion option[value="'+objetivo_datos[0]+'"]').attr('selected', true);
		$('#mod_pertenece option').attr('selected', false);
		$('#mod_pertenece option[value="'+objetivo_datos[1]+'"]').attr('selected', true);
		$('#mod_objetivo').val(objetivo_datos[2]);
		$('#mod_indicador').val(objetivo_datos[3]);
		$('#modifica_objetivo_bsc').show();
		$('#mod_objetivo').focus();
	});
	
	function modificar_objetivo()
	{
		
		var objetivo = $('#mod_objetivo').val();
		objetivo = objetivo.replace(/&/g,' ');
		var indicador = $('#mod_indicador').val();
		indicador = indicador.replace(/&/g,' ');
		
		objetivo = $.trim(objetivo);
		indicador = $.trim(indicador);
		
		if('' == objetivo && '' == indicador)
		{
			alert('Favor complete todos los campos.');
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "/balance/objetivos/modifica_objetivo/"+$('#mod_i_objetivo').val(),
			data: 'objetivo='+objetivo+'&indicador='+indicador+'&pertenece='+$('#mod_pertenece').val()+'&condicion='+$('#mod_condicion').val(),
			success: function(msg)
			{
				if('ok' == msg)
				{
					location.reload();
				}
				else{ alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	}
	
	
	
	$('.eli_obje').live('click', function()
	{
		
		if(confirm('El objetivo ser\xe3 eliminado.\r\nDesea continuar?'))
		{
			$.ajax({
				type: "POST",
				url: "/balance/objetivos/eliminar/"+$(this).attr('obj'),
				data: '',
				success: function(msg)
				{
					if('ok' == msg)
					{
						location.reload();
					}
					else{ alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
				},
				error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
			});
		}
		
	});
	
	
	
	
	
	$('#guarda_valores').click(function()
	{
		
		$(this).attr('disabled', true);
		$(this).val('Procesando...');
		
		var valores = '';
		$('tr').each(function()
		{
			
			if(undefined != $(this).attr('obj'))
			{
				
				if('' != valores)
				{
					valores = valores + ',';
				}
				
				var meses = '';
				
				$('#'+$(this).attr('id')+' .mes').each(function()
				{
					if('' != meses)
					{
						meses = meses + ',';
					}
					meses = meses + '"' + parseFloat($(this).children('input').val()) + '"';
				});
				
				valores = valores + '"'+$(this).attr('obj')+'":['+meses+']';
				
			}
			
		})
		
		valores = '{' + valores + '}';
		
		
		$.ajax({
			type: "POST",
			url: "/balance/objetivos/actualiza_datos/<?=$Tipo_Objetivo?>/<?=$Ver_Anho?>",
			data: 'valores='+valores,
			success: function(msg)
			{
				if('ok' == msg)
				{
					
					$('#guarda_valores').val('Guardar Informaci\xf3n');
					$('#guarda_valores').attr('disabled', false);
					
				}
				else{ alert("Ocurrio un error: "+msg+".\r\n Haga una captura de pantalla para realizar una verificacion del problema."); }
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".\r\n Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
		
	});
	
	
	
</script>

