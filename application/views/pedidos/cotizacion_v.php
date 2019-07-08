
<style>
	.coti_btn
	{
		width: 25px;
		height: 25px;
		color: #ffffff;
		cursor: pointer;
		font-weight: bold;
		border-radius: 50%;
		text-align: center;
		background: #ff0000;
		display: inline-block;
		vertical-align: middle;
	}
	.tabular th input{
		border: none;
		color: #fdfdfd;
		background: none;
	}
</style>

<?php
//Mostrar o no la lista de productos.
//Si es agregar, aparecera deshabilitada; si es modificar se muestra segun condicion
$Mostrar_datos = ' style="display:none;"';
$Checkbox = '';

if(isset($Cotizacion))
{
	//Si existe la cotizacion, es un trabajo para modificar
	if(0 < count($Cotizacion))
	{
		//Si tiene datos la cotizacion, enctonces se muestra el listado y selecciona
		//el checkbox
		$Mostrar_datos = '';
		$Checkbox = '	checked="checked"';
	}
}
?>

<br class="no_imprime" />




<?php
//******************************************************//
//     o        o                                                     
// __     __        _  _  _     _   __   ,_  _|_  __,   _  _  _|_  _  
///  \_| /  \_  |  / |/ |/ |  |/ \_/  \_/  |  |  /  |  / |/ |  |  |/  
//\__/ |/\__/   |_/  |  |  |_/|__/ \__/    |_/|_/\_/|_/  |  |_/|_/|__/
//    /|                     /|                                       
//    \|                     \|                                       
//Si modificas los elementos de la cotizacion tambien debes modificar
//pedido/ingresar_cotizacion_m
//******************************************************//
?>


<table class="tabular" style="width: 50%;">
	<tr>
		<th colspan="4">
			<input type="checkbox" name="cotizacion" id="cotizacion" onclick="ver_cotizacion()"<?=$Checkbox?> />
			<label for="cotizacion">AGREGAR COTIZACI&Oacute;N</label>
		</th>
	</tr>
	<tbody<?=$Mostrar_datos?> id="coti_trabajo">
<?php

$Artes_Items = array(
	5 => 'Arte Cambio de Formato',
	8 => 'Arte Desarrollo',
	7 => 'Arte Cambio de Textos',
	99 => 'Impresi&oacute;n'
);

$ArteT = 0;
foreach($Artes_Items as $iArte => $nArte)
{
	if(isset($Cotizacion[$iArte]['total']))
	{
		$ArteT += $Cotizacion[$iArte]['total'];
	}
}
?>
		<tr>
			<td colspan="2">Arte</td>
			<td class="numero"><span id="coti_arte">$<?=number_format($ArteT, 2)?></span></td>
			<td class="numero"><span class="coti_btn no_imprime" id="coti_agr_arte">+</span></td>
		</tr>
		<tr>
			<td>Negativo</td>
			<td class="numero"><span id="pulgas_negativo">0</span> in2</td>
			<td class="numero"><span id="coti_negativo">$<?=(isset($Cotizacion[39]['total']))?number_format($Cotizacion[39]['total'], 2):'0.00'?></span></td>
			<td class="numero"><span class="coti_btn no_imprime" id="coti_agr_negativo">+</span></td>
		</tr>
		<tr>
			<td>Plancha Fotopol&iacute;mera</td>
			<td class="numero"><span id="pulgas_plancha">0</span> in2</td>
			<td class="numero"><span id="coti_plancha">$<?=(isset($Cotizacion[29]['total']))?number_format($Cotizacion[29]['total'], 2):'0.00'?></span></td>
			<td class="numero"><span class="coti_btn no_imprime" id="coti_agr_plancha">+</span></td>
		</tr>
		<tr>
			<td colspan="2">Prueba de color</td>
			<td class="numero"><span id="coti_prueba">$<?=(isset($Cotizacion[73]['total']))?number_format($Cotizacion[73]['total'], 2):'0.00'?></span></td>
			<td class="numero"><span class="coti_btn no_imprime" id="coti_agr_prueba">+</span></td>
		</tr>
		<tr>
			<td colspan="2">Otros</td>
			<td class="numero"><span id="coti_otros">$<?=(isset($Cotizacion[54]['total']))?number_format($Cotizacion[54]['total'], 2):'0.00'?></span></td>
			<td class="numero"><span class="coti_btn no_imprime" id="coti_agr_otros">+</span></td>
		</tr>
		<tr>
			<th colspan="2">Total</th>
			<th class="numero"><span id="coti_total">$0.00</span></th>
			<th class="numero">&nbsp;</th>
		</tr>
	</tbody>
</table>

<br />


<div id="contiene_cotis">

	<table class="tabular coti_agr_arte" info="coti_arte" style="width: 75%; display: none;">
		<tr>
			<th>Detalle Arte <span class="toolizq">[ - ]<span>Minimizar Detalle</span></span></th>
			<th style="width: 100px;">Cantidad</th>
			<th style="width: 100px;">Precio</th>
			<th style="width: 100px;">Sub Total</th>
		</tr>
<?php
foreach($Artes_Items as $iArte => $nArte)
{
?>
		<tr info="<?=$iArte?>">
			<td>
				<?=$nArte?>
				<input type="hidden" name="cant_<?=$iArte?>" size="6" value="1" />
				<input type="hidden" name="anch_<?=$iArte?>" size="6" value="1" />
				<input type="hidden" name="alto_<?=$iArte?>" size="7" value="1" />
			</td>
			<td><input type="text" name="pulg_<?=$iArte?>" size="6" value="<?=isset($Cotizacion[$iArte][0]['pulgadas'])?$Cotizacion[$iArte][0]['pulgadas']:''?>" /></td>
<?php
$Precio = '';
if(isset($Cotizacion[$iArte][0]['precio']))
{
	$Precio = $Cotizacion[$iArte][0]['precio'];
}
else
{
	if(isset($Productos[$iArte]))
	{
		$Precio = $Productos[$iArte]['precio'];
	}
}
?>
			<td>$<input type="text" name="prec_<?=$iArte?>" size="6" value="<?=$Precio?>" /></td>
<?php
$Total = 0;
if(isset($Cotizacion[$iArte][0]['precio']) && isset($Cotizacion[$iArte][0]['pulgadas']))
{
	$Total = $Cotizacion[$iArte][0]['precio'] * $Cotizacion[$iArte][0]['pulgadas'];
}
?>
			<td>$<input type="text" name="subt_<?=$iArte?>" size="7" readonly="readonly" value="<?=$Total?>" /></td>
		</tr>
<?php
}
?>
		<tr>
			<th colspan="3">Total</th>
			<th style="width: 100px;">$<input type="text" name="" size="7" class="total" readonly="readonly"></th>
		</tr>
	</table>



	<table class="tabular coti_agr_negativo" info="coti_negativo" style="width: 75%; display: none;">
		<tr>
			<th>Detalle <span class="toolizq">[ - ]<span>Minimizar Detalle</span></span></th>
			<th style="width: 80px;">Cantidad</th>
			<th style="width: 80px;">Ancho</th>
			<th style="width: 80px;">Alto</th>
			<th style="width: 80px;">Pulgadas</th>
			<th style="width: 80px;">Precio</th>
			<th style="width: 80px;">Sub Total</th>
		</tr>
<?php
	$Pulgadas_Total_Neg = 0;
	for($i = 0; $i < 4; $i++)
	{
		$Coti = array(
			'cantidad' => '',
			'ancho' => '',
			'alto' => '',
			'pulgas' => '',
			'precio' => '',
			'total' => 0
		);

		if(isset($Cotizacion[39][$i]))
		{
			$Coti = array(
				'cantidad' => $Cotizacion[39][$i]['cantidad'],
				'ancho' => $Cotizacion[39][$i]['ancho'],
				'alto' => $Cotizacion[39][$i]['alto'],
				'pulgas' => $Cotizacion[39][$i]['pulgadas'],
				'precio' => $Cotizacion[39][$i]['precio'],
				'total' => $Cotizacion[39][$i]['pulgadas'] * $Cotizacion[39][$i]['precio']
			);
			$Pulgadas_Total_Neg += $Cotizacion[39][$i]['pulgadas'];
		}
		else
		{
			if(isset($Productos[39]))
			{
				$Coti['precio'] = $Productos[39]['precio'];
			}
			elseif(isset($Productos[34]))
			{
				$Coti['precio'] = $Productos[34]['precio'];
			}
		}
?>
		<tr info="39_<?=$i?>" pulgas="si">
			<td>Montaje Negativos</td>
			<td><input type="text" name="cant_39_<?=$i?>" value="<?=$Coti['cantidad']?>" size="6"></td>
			<td><input type="text" name="anch_39_<?=$i?>" value="<?=$Coti['ancho']?>" size="6"></td>
			<td><input type="text" name="alto_39_<?=$i?>" value="<?=$Coti['alto']?>" size="7"></td>
			<td><input type="text" name="pulg_39_<?=$i?>" value="<?=$Coti['pulgas']?>" size="6"></td>
			<td>$<input type="text" name="prec_39_<?=$i?>" value="<?=$Coti['precio']?>" size="6"></td>
			<td>$<input type="text" name="subt_39_<?=$i?>" value="<?=number_format($Coti['total'], 2)?>" size="7"></td>
		</tr>
	<?php
	}
	?>
		<tr>
			<th colspan="6">Total</th>
			<th style="width: 100px;">$<input type="text" name="" size="7" class="total" readonly="readonly"></th>
		</tr>
	</table>



	<table class="tabular coti_agr_plancha" info="coti_plancha" style="width: 75%; display: none;">
		<tr>
			<th>Detalle <span class="toolizq">[ - ]<span>Minimizar Detalle</span></span></th>
			<th style="width: 80px;">Cantidad</th>
			<th style="width: 80px;">Ancho</th>
			<th style="width: 80px;">Alto</th>
			<th style="width: 80px;">Pulgadas</th>
			<th style="width: 80px;">Precio</th>
			<th style="width: 80px;">Sub Total</th>
		</tr>
	<?php
	$Pulgadas_Total_Pla = 0;
	for($i = 0; $i < 4; $i++)
	{
		$Coti = array(
			'cantidad' => '',
			'ancho' => '',
			'alto' => '',
			'pulgas' => '',
			'precio' => '',
			'total' => 0
		);

		if(isset($Cotizacion[29][$i]))
		{
			$Coti = array(
				'cantidad' => $Cotizacion[29][$i]['cantidad'],
				'ancho' => $Cotizacion[29][$i]['ancho'],
				'alto' => $Cotizacion[29][$i]['alto'],
				'pulgas' => $Cotizacion[29][$i]['pulgadas'],
				'precio' => $Cotizacion[29][$i]['precio'],
				'total' => $Cotizacion[29][$i]['pulgadas'] * $Cotizacion[29][$i]['precio']
			);
			$Pulgadas_Total_Pla += $Cotizacion[29][$i]['pulgadas'];
		}
		else
		{
			if(isset($Productos[29]))
			{
				$Coti['precio'] = $Productos[29]['precio'];
			}
			elseif(isset($Productos[18]))
			{
				$Coti['precio'] = $Productos[18]['precio'];
			}
		}
	?>
		<tr info="29_<?=$i?>" pulgas="si">
			<td>Montaje Planchas</td>
			<td><input type="text" name="cant_29_<?=$i?>" value="<?=$Coti['cantidad']?>" size="6"></td>
			<td><input type="text" name="anch_29_<?=$i?>" value="<?=$Coti['ancho']?>" size="6"></td>
			<td><input type="text" name="alto_29_<?=$i?>" value="<?=$Coti['alto']?>" size="7"></td>
			<td><input type="text" name="pulg_29_<?=$i?>" value="<?=$Coti['pulgas']?>" size="6"></td>
			<td>$<input type="text" name="prec_29_<?=$i?>" value="<?=$Coti['precio']?>" size="6"></td>
			<td>$<input type="text" name="subt_29_<?=$i?>" value="<?=number_format($Coti['total'], 2)?>" size="7"></td>
		</tr>
	<?php
	}
	?>
		<tr>
			<th colspan="6">Total</th>
			<th style="width: 100px;">$<input type="text" name="" size="7" class="total" readonly="readonly"></th>
		</tr>
	</table>



	<table class="tabular coti_agr_prueba" info="coti_prueba" style="width: 75%; display: none;">
		<tr>
			<th>Detalle <span class="toolizq">[ - ]<span>Minimizar Detalle</span></span></th>
			<th style="width: 100px;">Cantidad</th>
			<th style="width: 100px;">Precio</th>
			<th style="width: 100px;">Sub Total</th>
		</tr>
		<tr info="73">
			<td>
				Prueba de Color
				<input type="hidden" name="cant_73" size="6" value="1" />
				<input type="hidden" name="anch_73" size="6" value="1" />
				<input type="hidden" name="alto_73" size="7" value="1" />
			</td>
			<td><input type="text" name="pulg_73" size="6" value="<?=isset($Cotizacion[73][0]['pulgadas'])?$Cotizacion[73][0]['pulgadas']:''?>" /></td>
<?php
$Precio = '';
if(isset($Cotizacion[73][0]['precio']))
{
	$Precio = $Cotizacion[73][0]['precio'];
}
else
{
	if(isset($Productos[73]))
	{
		$Precio = $Productos[73]['precio'];
	}
}
?>
			<td>$<input type="text" name="prec_73" size="6" value="<?=$Precio?>" /></td>
<?php
$Total = 0;
if(isset($Cotizacion[73][0]['precio']) && isset($Cotizacion[73][0]['pulgadas']))
{
	$Total = $Cotizacion[73][0]['precio'] * $Cotizacion[73][0]['pulgadas'];
}
?>
			<td>$<input type="text" name="subt_73" size="7" readonly="readonly" value="<?=$Total?>" /></td>
		</tr>
		<tr>
			<th colspan="3">Total</th>
			<th style="width: 100px;">$<input type="text" name="" size="7" class="total" readonly="readonly"></th>
		</tr>
	</table>



	<table class="tabular coti_agr_otros" info="coti_otros" style="width: 75%; display: none;">
		<tr>
			<th>Detalle <span class="toolizq">[ - ]<span>Minimizar Detalle</span></span></th>
			<th style="width: 100px;">Cantidad</th>
			<th style="width: 100px;">Precio</th>
			<th style="width: 100px;">Sub Total</th>
		</tr>
		<tr info="54">
			<td>
				Flete
				<input type="hidden" name="cant_54" size="6" value="1" />
				<input type="hidden" name="anch_54" size="6" value="1" />
				<input type="hidden" name="alto_54" size="7" value="1" />
			</td>
			<td><input type="text" name="pulg_54" size="6" value="<?=isset($Cotizacion[54][0]['pulgadas'])?$Cotizacion[54][0]['pulgadas']:''?>" /></td>
<?php
$Precio = '';
if(isset($Cotizacion[54][0]['precio']))
{
	$Precio = $Cotizacion[54][0]['precio'];
}
else
{
	if(isset($Productos[54]))
	{
		$Precio = $Productos[54]['precio'];
	}
}
?>
			<td>$<input type="text" name="prec_54" size="6" value="<?=$Precio?>" /></td>
<?php
$Total = 0;
if(isset($Cotizacion[54][0]['precio']) && isset($Cotizacion[54][0]['pulgadas']))
{
	$Total = $Cotizacion[54][0]['precio'] * $Cotizacion[54][0]['pulgadas'];
}
?>
			<td>$<input type="text" name="subt_54" size="7" readonly="readonly" value="<?=$Total?>" /></td>
		</tr>
		<tr info="103">
			<td>
				Destilado de Solvente
				<input type="hidden" name="cant_103" size="6" value="1" />
				<input type="hidden" name="anch_103" size="6" value="1" />
				<input type="hidden" name="alto_103" size="7" value="1" />
			</td>
			<td><input type="text" name="pulg_103" size="6" value="<?=isset($Cotizacion[103][0]['pulgadas'])?$Cotizacion[103][0]['pulgadas']:''?>" /></td>
<?php
$Precio = '';
if(isset($Cotizacion[103][0]['precio']))
{
	$Precio = $Cotizacion[103][0]['precio'];
}
else
{
	if(isset($Productos[103]))
	{
		$Precio = $Productos[103]['precio'];
	}
}
?>
			<td>$<input type="text" name="prec_103" size="6" value="<?=$Precio?>" /></td>
<?php
$Total = 0;
if(isset($Cotizacion[103][0]['precio']) && isset($Cotizacion[103][0]['pulgadas']))
{
	$Total = $Cotizacion[103][0]['precio'] * $Cotizacion[103][0]['pulgadas'];
}
?>
			<td>$<input type="text" name="subt_103" size="7" readonly="readonly" value="<?=$Total?>" /></td>
		</tr>
		<tr>
			<th colspan="3">Total</th>
			<th style="width: 100px;">$<input type="text" name="" size="7" class="total" readonly="readonly"></th>
		</tr>	
	</table>

</div>




<script>

	$('#pulgas_negativo').empty().append('<?=$Pulgadas_Total_Neg?>');
	$('#pulgas_plancha').empty().append('<?=$Pulgadas_Total_Pla?>');

	$('#coti_trabajo .coti_btn').click(function()
	{
		$('.'+$(this).attr('id')).show();
	});

	
	$('#contiene_cotis input').blur(function()
	{
		var Fila = $(this).parent().parent().attr('info');
		var Pulgas = $(this).parent().parent().attr('pulgas');
		var Pulgadas = $('[name="pulg_'+Fila+'"]').val();
		
		if('si' == Pulgas)
		{
			var Ancho = parseFloat($('[name="anch_'+Fila+'"]').val());
			var Alto = parseFloat($('[name="alto_'+Fila+'"]').val());
			var Cantidad = parseFloat($('[name="cant_'+Fila+'"]').val());
			Pulgadas = Ancho * Alto * Cantidad;

			if(isNaN(Pulgadas))
			{
				Pulgadas = 0;
			}

			$('[name="pulg_'+Fila+'"]').val(Pulgadas.toFixed(2));
		}


		var Precio = parseFloat($('[name="prec_'+Fila+'"]').val());
		var SubTotal = Pulgadas * Precio;

		if(isNaN(SubTotal))
		{
			SubTotal = 0;
		}
		$('[name="subt_'+Fila+'"]').val(SubTotal.toFixed(2));


		//sacar total
		var Tabla = $(this).parent().parent().parent();
		var Total = 0;
		var Pulguitas = 0;
		Tabla.find('tr').each(function()
		{
			var SubTotal = parseFloat($('[name="subt_'+$(this).attr('info')+'"]').val());
			if(!isNaN(SubTotal))
			{
				Total = Total + SubTotal;
			}
			var SubPulga = parseFloat($('[name="pulg_'+$(this).attr('info')+'"]').val());
			if(!isNaN(SubPulga))
			{
				Pulguitas = Pulguitas + SubPulga;
			}
		});

		Tabla.find('.total').val(Total.toFixed(2));

		var Info = Tabla.attr('info');
		if(undefined == Info)
		{
			Info = Tabla.parent().attr('info');
		}
		$('#'+Info).empty().append('$'+Total.toFixed(2));
		console.log(Info);
		if('coti_negativo' == Info)
		{
			$('#pulgas_negativo').empty().append(Pulguitas);
		}
		if('coti_plancha' == Info)
		{
			$('#pulgas_plancha').empty().append(Pulguitas);
		}
		

	});



	$('#contiene_cotis span').click(function()
	{
		var Info = $(this).parent().parent().parent().attr('info');
		if(undefined == Info)
		{
			$(this).parent().parent().parent().parent().hide();
		}
		else
		{
			$(this).parent().parent().parent().hide();
		}

		var Total = 0;
		$('[name^=subt_]').each(function()
		{
			var miniTotal = parseFloat($(this).val());
			if(!isNaN(miniTotal))
			{
				Total = Total + miniTotal;
			}
		});

		$('#coti_total').empty().append('$'+Total.toFixed(2));

	});


	//calcula el total cuando se carga la pagina
	function calcula_total_trampa()
	{
		
		var Total = 0;
		$('[name^=subt_]').each(function()
		{
			var miniTotal = parseFloat($(this).val());
			if(!isNaN(miniTotal))
			{
				Total = Total + miniTotal;
			}
		});

		$('#coti_total').empty().append('$'+Total.toFixed(2));

	}

	calcula_total_trampa();
</script>