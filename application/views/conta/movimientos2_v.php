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
	.fila_resaltada{
		color: #530000;
		background: #d49292;
	}
	.sin_valor{
		color: #530000;
		border: 1px solid #530000;
	}
</style>


<?php
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
<?php
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
<?php
	foreach($Datos['line'] as $iLine => $nLine)
	{
?>
			<label>
				&nbsp; &nbsp; <input type="checkbox" valor="<?=$iLine?>" />
				<?=$nLine?><br />
			</label>
<?php
	}
?>
		</div>
	</div>
<?php
}
?>
	<div class="lineas_agregar" style="display:none;">
		<input type="button" id="btn_lineas_agregar" value="Agregar Detalle" />
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


<br />
<strong>Detalle de Movimientos</strong>

<select id="mc_lineas">
	<option value='todos'>Todas las L&iacute;neas</option>
<?php
foreach($Lineas as $Index => $Datos)
{
	foreach($Datos as $Id_MC_sLinea => $Info)
	{
?>
	<option value='<?=$Id_MC_sLinea?>'><?=$Info['codigo'].' - '.$Info['linea']?></option>
<?php
	}
}
?>
</select>

<br />
<?=$Paginacion?>

<table class="tabular" style="width: 100%">
	<tr>
		<th style="width:10%;">Fecha</th>
		<th style="width:44%;">Descripci&oacute;n</th>
		<th>L&iacute;nea</th>
		<th style="width:10%;">Cantidad</th>
		<th style="width:10%;">Precio Unit.</th>
		<th style="width:10%;">Total</th>
		<th style="width:6%;">&nbsp;</th>
	</tr>
<?php
foreach($Movimientos as $Id_Mc_Movimiento => $Datos)
{
?>
	<tr>
		<td><?=date('d-m-Y', strtotime($Datos['fecha']))?></td>
		<td><?=$Datos['descripcion']?></td>
		<td><span class="toolder"><?=$Datos['codigo']?> [<?=$Datos['mas_menos']?>]<span><?=$Datos['linea']?></span></span></td>
		<td class="derecha"><?=number_format($Datos['cantidad'], 2)?></td>
		<td class="derecha">$<?=number_format($Datos['precio_unitario'], 2)?></td>
		<td class="derecha">$<?=number_format($Datos['total'], 2)?></td>
		<td>
			<span info="<?=$Id_Mc_Movimiento?>" class="iconos ieliminar toolder"><span>Eliminar Movimiento</span></span>
		</td>
	</tr>
<?php
}
?>
</table>


<?=$Paginacion?>
<br /><br />


<script>
	
	var Lineas_Movimientos = {};
<?php
foreach($Lineas_Mostrar as $Index => $Datos)
{
	foreach($Datos['line'] as $iLine => $nLine)
	{
?>
	Lineas_Movimientos["<?=$iLine?>"] = ["<?=$nLine?>", "<?=$Datos['cod']?>"];
<?php
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
				Fila = Fila + '<td><input type="hidden" name="mov_linea_'+$(this).attr('valor')+'" id="mov_linea_'+$(this).attr('valor')+'" value="'+$(this).attr('valor')+'" />'+Lineas_Movimientos[$(this).attr('valor')][0]+'</td>';
				Fila = Fila + '<td><input type="text" name="mov_descripcion_'+$(this).attr('valor')+'" id="mov_descripcion_'+$(this).attr('valor')+'" size="30" /></td>';
				Fila = Fila + '<td><input type="text" name="mov_monto_'+$(this).attr('valor')+'" id="mov_monto_'+$(this).attr('valor')+'" size="12" /></td>';
				Fila = Fila + '<td><input type="text" name="mov_factura_'+$(this).attr('valor')+'" id="mov_factura_'+$(this).attr('valor')+'" size="12" /></td>';
				
				var Num_pedido = new Date();
				Num_pedido = Num_pedido.getDate() + "" + (Num_pedido.getMonth() + 1) + "" + Num_pedido.getFullYear();
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
	
	
	$('#mostrar_movi').click(function()
	{
		$('.lineas_agregar').show();
		$('.lineas_agregar div').hide();
		$('#mov_agregar').hide();
	});
	
	
	$('#mov_agregar [type="button"]').click(function()
	{
		$('#mov_agregar').hide();
		$('#mov_agregar_body').empty();
	});
	
/*	
	$('#mov_agregar_body').on('blur', '.calcular', function()
	{
		
		var Fila = $(this).attr('info');
		$('#mov_total_'+Fila).val(0);
		
		if(
			'' == $.trim($('#mov_precio_'+Fila).val())
			|| '' == $.trim($('#mov_cantidad_'+Fila).val())
			|| isNaN($('#mov_precio_'+Fila).val())
			|| isNaN($('#mov_cantidad_'+Fila).val())
		)
		{
			return false;
		}
		
		var Precio = parseFloat($('#mov_precio_'+Fila).val());
		var Cantidad = parseFloat($('#mov_cantidad_'+Fila).val());
		
		var Total = Precio * Cantidad;
		
		$('#mov_total_'+Fila).val(Total.toFixed(2));
	});
*/
	
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
		
		for(i = 0; i < 5; i++)
		{
			if(
				'' != $('#mov_fecha_'+i).val()
				|| '' != $('#mov_descripcion_'+i).val()
				|| '' != $('#mov_linea_'+i).val()
				|| '' != $('#mov_cantidad_'+i).val()
				|| '' != $('#mov_precio_'+i).val()
			)
			{
				if(
					'' == $('#mov_fecha_'+i).val()
					|| '' == $('#mov_descripcion_'+i).val()
					|| '' == $('#mov_linea_'+i).val()
					|| '' == $('#mov_cantidad_'+i).val()
					|| '' == $('#mov_precio_'+i).val()
				)
				{
					Guardar = false;
					$('#mov_fila_'+i).addClass('fila_resaltada');
				}
				
				if('0' == $('#mov_total_'+i).val())
				{
					Guardar = false;
					$('#mov_total_'+i).addClass('sin_valor');
					$('#mov_fila_'+i).addClass('fila_resaltada');
				}
				
				Total_Mov++;
				
			}
			
		}
		
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
	
	
	$('#mc_lineas').change(function()
	{
		window.location = '/conta/movimientos/index/'+$(this).val()+'/1/0';
	});
	
</script>


