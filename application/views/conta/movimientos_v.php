<style>
	#mov_agregar{
		border-collapse: collapse;
	}
	#mov_agregar td, #mov_agregar th{
		margin: 1px;
		padding: 1px;
	}
	#mov_agregar select{
		max-width: 260px;
	}
	#contiene_lineas_agregar{
		width: 75%;
		margin: 5px 7px;
		background: #e0e7ef;
	}
	.lineas_agregar:hover{
		background: #D5DBE2;
	}
	.lineas_agregar div{
		background: #f3f4f6;
	}
	.lineas_agregar div label:hover{
		color: #111111;
		background: #E3E6ED;
	}
	.fila_resaltada{
		color: #530000;
		background: #d49292;
	}
	.sin_valor{
		color: #530000;
		border: 1px solid #530000;
	}
	#mc_pago_div{
		top: 25px;
		display: none;
		position: absolute;
		margin-left: 250px;
	}
	#mc_pago_div div{
		width: 500px;
		padding: 10px 25px;
		background: #ffffff;
		margin-bottom: 60px;
		border: 3px solid #74BFB4;
	}
	#mc_pago_div label{
		width: 80px;
		display: inline-block;
	}
	.mc_confirma p:hover{
		color: #15423B;
		font-weight: bold;
	}
</style>


<?
$Lineas_Mostrar = array();
$Padres = array();
//Relajo de relajos
//Recorro uno a uno los menus padres con sus hijos
foreach($Lineas as $Id_Mc_Linea => $Principal)
{
	foreach($Principal as $Id_sMc_Linea => $SubMC)
	{
		//Guardo todas las lineas en este array, por si acaso son padres de lineas
		//que reciben movimientos
		$Padres[$Id_sMc_Linea][] = $SubMC['codigo'];
		$Padres[$Id_sMc_Linea][] = $SubMC['codigo'].' - '.$SubMC['linea'];
		
		//Si esta linea es de las que recibe movimiento
		if(!isset($Lineas[$Id_sMc_Linea]))
		{
			//Debo crear la estructura padre hijo
			if(!isset($Lineas_Mostrar[$Id_Mc_Linea]))
			{
				$Lineas_Mostrar[$Id_Mc_Linea] = array(
					'nom' => '',
					'cod' => '',
					'line' => array()
				);
			}
			$Lineas_Mostrar[$Id_Mc_Linea]['line'][$Id_sMc_Linea] = $SubMC['codigo'].' - '.$SubMC['linea'];
		}
	}
}
?>


<div>
	<strong><span class="toolder" id="mostrar_movi">Agregar Movimientos &nbsp; [+]<span>Mostrar formulario para agregar movimientos</span></span></strong>
	
	<div id="contiene_lineas_agregar">
<?
foreach($Lineas_Mostrar as $Index => $Datos)
{
	//Esto no lo logro capturar en el bucle anterior
	$Lineas_Mostrar[$Index]['cod'] = $Padres[$Index][0];
	$Lineas_Mostrar[$Index]['nom'] = $Padres[$Index][1];
	
?>
		<div class="lineas_agregar" style="display:none;">
			<span class="toolder">
				&raquo;<?=$Lineas_Mostrar[$Index]['nom']?><span>Ver/Ocultar L&iacute;neas</span>
			</span>
			<div style="display:none;">
<?
	foreach($Datos['line'] as $iLine => $nLine)
	{
?>
				<label>
					&nbsp; &nbsp; <input type="checkbox" valor="<?=$iLine?>" />
					<?=$nLine?><br />
				</label>
<?
	}
?>
			</div>
		</div>
<?
}
?>
		<div class="lineas_agregar" style="display:none;">
			<input type="button" id="btn_lineas_agregar" value="Agregar Detalle" />
			<input type="button" id="btn_lineas_cancelar" value="Cancelar" />
		</div>
	</div>
</div>




<div id="mov_agregar" style="display:none;">
	
	<form id="mc_agregar_movimiento" action="/conta/movimientos/agregar" method="post" onsubmit="return validar_movimiento();">
		
		
		<table style="width:100%;">
			<tr>
				<!--td>&nbsp;</td-->
				<td>L&iacute;nea</td>
				<td>Descripci&oacute;n</td>
				<td>Monto</td>
				<td>Factura</td>
				<td>Pedido</td>
				<td>Fecha de pago</td>
			</tr>
			<tbody id="mov_agregar_body"></tbody>
			<tr>
				<td colspan="7">
					<input type="submit" value="Guardar" />
					<input type="button" value="Cancelar" />
					<br />
				</td>
			</tr>
		</table>
		
	</form>
	
</div>






<div>
	<br />
	<strong>Mostrar detalle</strong>
	
	<br />
	<select id="mc_slc_mov_linea">
		<option value="todos">Todas las l&iacute;neas</option>
<?
foreach($Lineas_Mostrar as $Index => $Datos)
{
	//Esto no lo logro capturar en el bucle anterior
	$Lineas_Mostrar[$Index]['cod'] = $Padres[$Index][0];
	$Lineas_Mostrar[$Index]['nom'] = $Padres[$Index][1];
?>
		<option value="<?=$Index?>"><?=$Lineas_Mostrar[$Index]['nom']?></option>
<?
}
?>
	</select>
	
	<select id="mc_slc_mov_linea_h">
		<optgroup id="mc_opt_todos">
			<option value="todos">Todos</option>
		</optgroup>
<?
foreach($Lineas_Mostrar as $Index => $Datos)
{
?>
		<optgroup id="mc_opt_<?=$Index?>">
<?
	foreach($Datos['line'] as $iLine => $nLine)
	{
?>
			<option value="<?=$iLine?>"><?=$nLine?></option>
<?
	}
}
?>
		</optgroup>
	</select>
	
	<select id="mc_pagado">
		<option value="todos">Completo</option>
		<option value="canc"<?=('canc'==$MC_Pago)?' selected="selected"':''?>>Pagos confirmados</option>
		<option value="pend"<?=('pend'==$MC_Pago)?' selected="selected"':''?>>Pagos pendientes</option>
	</select>
	
	<input type="button" value="Ver Detalle" id="mc_btn_vdetalle" />
	
</div>




<?
$Lineas_Mostrar = array();
$Padres = array();
//Relajo de relajos
//Recorro uno a uno los menus padres con sus hijos
foreach($Lineas as $Id_Mc_Linea => $Principal)
{
	foreach($Principal as $Id_sMc_Linea => $SubMC)
	{
		//Guardo todas las lineas en este array, por si acaso son padres de lineas
		//que reciben movimientos
		$Padres[$Id_sMc_Linea][] = $SubMC['codigo'];
		$Padres[$Id_sMc_Linea][] = $SubMC['codigo'].' - '.$SubMC['linea'];
		
		//Si esta linea es de las que recibe movimiento
		if(!isset($Lineas[$Id_sMc_Linea]))
		{
			//Debo crear la estructura padre hijo
			if(!isset($Lineas_Mostrar[$Id_Mc_Linea]))
			{
				$Lineas_Mostrar[$Id_Mc_Linea] = array(
					'nom' => '',
					'cod' => '',
					'line' => array()
				);
			}
			$Lineas_Mostrar[$Id_Mc_Linea]['line'][$Id_sMc_Linea] = $SubMC['codigo'].' - '.$SubMC['linea'];
		}
	}
}
?>











<br />
<strong>Detalle de Movimientos</strong>





<!--select id="mc_lineas">
	<option value='todos'>Todas las L&iacute;neas</option>
<?
foreach($Lineas as $Index => $Datos)
{
	foreach($Datos as $Id_MC_sLinea => $Info)
	{
?>
	<option value='<?=$Id_MC_sLinea?>'><?=$Info['codigo'].' - '.$Info['linea']?></option>
<?
	}
}
?>
</select-->




<br />
<?=$Paginacion?>


<table class="tabular" style="width: 100%">
	<tr>
		<th style="width:80px;">Fecha</th>
		<th>Descripci&oacute;n</th>
		<th class="derecha" style="width:80px;">L&iacute;nea</th>
		<th class="derecha" style="width:75px;">Monto</th>
		<th class="derecha" style="width:85px;">Factura</th>
		<th class="derecha" style="width:90px;">Pedido</th>
		<th class="derecha" style="width:90px;">Confirmaci&oacute;n</th>
		<th style="width:30px;">&nbsp;</th>
	</tr>
<?
foreach($Movimientos as $Id_Mc_Movimiento => $Datos)
{
?>
	<tr>
		<td><?=date('d-m-Y', strtotime($Datos['fecha']))?></td>
		<td><?=$Datos['descripcion']?></td>
		<td class="derecha"><span class="toolder"><?=$Datos['codigo']?> [<?=$Datos['mas_menos']?>]<span><?=$Datos['linea']?></span></span></td>
		<td class="derecha">$<?=number_format($Datos['monto'], 2)?></td>
		<td class="derecha"><?=$Datos['factura']?></td>
		<td class="derecha"><?=$Datos['pedido']?></td>
		<td class="derecha">
<?
	if('' == $Datos['codigo_pago'])
	{
?>
			<span class="toolder mc_confirma" info="<?=$Id_Mc_Movimiento?>" desc="<?=$Datos['codigo']?> [<?=$Datos['mas_menos']?>] - <?=$Datos['descripcion']?>"><p>Confirmar</p><span>Ingresar el n&uacute;mero y tipo de la transacci&oacute;n realizada.</span></span>
<?
	}
	else
	{
?>
			<?=$Datos['codigo_pago']?> (<?=$Datos['tipo_pago']?>)
<?
	}
?></td>
		<td>
			<span info="<?=$Id_Mc_Movimiento?>" class="iconos ieliminar toolder"><span>Eliminar Movimiento</span></span>
		</td>
	</tr>
<?
}
?>
</table>


<?=$Paginacion?>
<br /><br />




<div id="mc_pago_div">
	<div>
		
		<form action="/conta/movimientos/confirmar" method="post" onsubmit="return mc_confirma_sub();">
			<strong>CONFIRMAR PAGO</strong>
			
			<br />
			<strong id="mc_pago_titulo"></strong>
			
			
			<input type="hidden" name="mc_pago_movi" id="mc_pago_movi" />
			
			<br /><br />
			<label>#Transacci&oacute;n:</label>
			<input type="text" name="mc_pago_codi" id="mc_pago_codi" />
			
			<br />
			<label>Tipo:</label>
			<select name="mc_pago_tipo" id="mc_pago_tipo">
				<option value="ch">Cheque</option>
				<option value="ef">Efectivo (Recibo)</option>
				<option value="tr">Transferencia Electr&oacute;nica</option>
			</select>
			
			<br /><br />
			<label>&nbsp;</label>
			<input type="button" id="mc_pago_ok" value="Confirmar" />
			<input type="button" id="mc_pago_ca" value="Cancelar" />
		</form>
		
	</div>
</div>



<script>
	
	$('#mostrar_movi').click(function()
	{
		$('.lineas_agregar').show();
		$('.lineas_agregar div').hide();
		$('#mov_agregar').hide();
	});
	
	$('#btn_lineas_cancelar').click(function()
	{
		$('.lineas_agregar').hide();
		$('.lineas_agregar div').hide();
	});
	
	
	$('#mov_agregar [type="button"]').click(function()
	{
		$('#mov_agregar').hide();
		$('#mov_agregar_body').empty();
	});
	
	
	var Lineas_Movimientos = {};
<?
foreach($Lineas_Mostrar as $Index => $Datos)
{
	foreach($Datos['line'] as $iLine => $nLine)
	{
?>
	Lineas_Movimientos["<?=$iLine?>"] = ["<?=$nLine?>", "<?=$Datos['cod']?>"];
<?
	}
}
?>
	
	
	$('.lineas_agregar span').click(function()
	{
		$(this).parent().children('div').toggle();
	});
	
	
	$('#btn_lineas_agregar').click(function()
	{
		
		$('#mov_agregar_body').empty();
		
		$('.lineas_agregar input').each(function()
		{
			if($(this).prop('checked'))
			{
				
				var Fila = '<tr id="mov_fila_'+$(this).attr('valor')+'">';
				Fila = Fila + '<td><input type="hidden" name="mov_linea[]" id="mov_linea_'+$(this).attr('valor')+'" value="'+$(this).attr('valor')+'" />'+Lineas_Movimientos[$(this).attr('valor')][0]+'</td>';
				Fila = Fila + '<td><input type="text" name="mov_descripcion_'+$(this).attr('valor')+'" id="mov_descripcion_'+$(this).attr('valor')+'" size="30" /></td>';
				Fila = Fila + '<td><input type="text" name="mov_monto_'+$(this).attr('valor')+'" id="mov_monto_'+$(this).attr('valor')+'" size="12" /></td>';
				Fila = Fila + '<td><input type="text" name="mov_factura_'+$(this).attr('valor')+'" id="mov_factura_'+$(this).attr('valor')+'" size="15" /></td>';
				
				var Fecha = new Date();
				Num_pedido = Fecha.getDate() + "" + (formatoMes(Fecha.getMonth())) + "" + formatoAnho(Fecha.getFullYear());
				Num_pedido = "30" + Lineas_Movimientos[$(this).attr('valor')][1] + Num_pedido;
				Fila = Fila + '<td><input type="text" name="mov_pedido_'+$(this).attr('valor')+'" id="mov_pedido_'+$(this).attr('valor')+'" size="12" readonly="readonly" value="'+Num_pedido+'" /></td>';
				Fila = Fila + '<td><input type="text" name="mov_fecha_'+$(this).attr('valor')+'" id="mov_fecha_'+$(this).attr('valor')+'" size="10" readonly="readonly" class="mov_fecha" /></td>';
				Fila = Fila + '</tr>';
				$('#mov_agregar_body').append(Fila);
				
				$('#mov_fecha_'+$(this).attr('valor')).datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
				
			}
		});
		
		$('.lineas_agregar input').prop('checked', false);
		$('#mov_agregar').show();
		$('.lineas_agregar').hide();
	});
	
	
	function formatoMes(Mes)
	{
		Mes++;
		return Mes < 10 ? '0' + Mes : '' + Mes;
	}
	function formatoAnho(Anho)
	{
		return Anho.toString().substr(2,2);
	}
	
	function validar_movimiento()
	{
		
		$('#mov_agregar input').removeClass('sin_valor');
		$('#mov_agregar tr').removeClass('fila_resaltada');
		
		$('#mc_agregar_movimiento [type="text"]').each(function()
		{
			$(this).val($.trim($(this).val()));
		});
		
		var Total_Mov = 0;
		var Guardar = true;
		
		$('#mov_agregar_body tr').each(function()
		{
			
			$(this).find('td input').each(function()
			{
				if('' == $(this).val())
				{
					Guardar = false;
					$(this).addClass('sin_valor');
					$(this).parent().parent().addClass('fila_resaltada');
				}
			});
			
			Total_Mov++;
			
		});
		
		
		
		
		if(
			Guardar
			&& 0 < Total_Mov
			&& confirm('La informacion sera almacenada. Desea continuar?')
		)
		{
			return true;
		}
		
		return false;
	}
	
	
	$('.ieliminar').click(function()
	{
		if(confirm('El movimiento sera eliminado. Desea continuar?'))
		{
			window.location = '/conta/movimientos/eliminar/'+$(this).attr('info');
		}
	});
	
	/*
	$('#mc_lineas, #mc_pagado').change(function()
	{
		window.location = '/conta/movimientos/index/'+$('#mc_lineas').val()+'/'+$('#mc_pagado').val();
	});
	*/
	
	
	
	$('.mc_confirma').click(function()
	{
		$('#mc_pago_titulo').empty().append($(this).attr('desc'));
		$('#mc_pago_movi').val($(this).attr('info'));
		$('#mc_pago_codi').val('');
		$('#mc_pago_tipo').val('ch');
		
		$('#mc_pago_div').show();
		$('#mc_pago_codi').focus();
	});
	
	
	$('#mc_pago_ca').click(function()
	{
		$('#mc_pago_div').hide();
	});
	
	
	$('#mc_pago_ok').click(function()
	{
		$('#mc_pago_ca').attr('disabled', true);
		$(this).attr('disabled', true);
		$(this).val('Confirmando...');
		
		$('#mc_pago_div form').submit();
	});
	
	function mc_confirma_sub()
	{
		alert('que pasa?');
		if(!confirm('Se realizara la confirmacion.\r\nDesea continuar?'))
		{
			$('#mc_pago_ca, #mc_pago_ok').attr('disabled', false);
			$('#mc_pago_ok').val('Confirmar');
			return false;
		}
		
		return true;
	}
	
	
	
	
	
	$('#mc_slc_mov_linea').change(function()
	{
		$('#mc_slc_mov_linea_h optgroup').hide();
		$('#mc_opt_'+$(this).val()).show();
		$('#mc_slc_mov_linea_h').val($('#mc_opt_'+$(this).val()+' option:first-child').val());
	});
	
	
	$('#mc_btn_vdetalle').click(function()
	{
		window.location = '/conta/movimientos/index/'+$('#mc_slc_mov_linea').val()+'/'+$('#mc_slc_mov_linea_h').val()+'/'+$('#mc_pagado').val()
	});
	
	
	$('#mc_slc_mov_linea').val('<?=$Id_Padre?>');
	$('#mc_slc_mov_linea').change();
	$('#mc_slc_mov_linea_h').val('<?=$Id_Linea?>');
	
</script>


