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



<strong>Detalle de Movimientos</strong>
<br />

<select id="mc_anho_cambia">
<?php
$Fecha_Fin = date('Y') + 2;
for($i = 2015; $i <= $Fecha_Fin; $i++)
{
?>
	<option value="<?=$i?>"<?=($i==$Anho)?' selected="selected"':''?>><?=$i?></option>
<?php
}
?>
</select>

<select id="mc_mes_inicio">
<?php
foreach($Meses_v as $iMes => $nMes)
{
?>
			<option value="<?=$iMes?>"<?=($iMes==$Mes)?' selected="selected"':''?>><?=$nMes?></td>
<?php
}
?>
</select>


&nbsp;
<select name="mc_linea" id="mc_linea">
	<option value="">--</option>
<?php
	foreach($Lineas as $Id_Mc_Linea => $Principal)
	{
		foreach($Principal as $Id_sMc_Linea => $SubMC)
		{
			if(!isset($Lineas[$Id_sMc_Linea]))
			{
?>
	<option value="<?=$Id_sMc_Linea?>"<?=($Id_Linea==$Id_sMc_Linea)?' selected="selected"':''?>><?=$SubMC['codigo'].' - '.$SubMC['linea']?></option>
<?php
			}
		}
	}
?>
</select>


&nbsp;
<input type="button" id="mc_cambia_detalle" value="Ver Detalle" />



<table class="tabular" style="width: 100%">
	<tr>
		<th style="width:10%;">Fecha</th>
		<th style="width:44%;">Descripci&oacute;n</th>
		<th>L&iacute;nea</th>
		<th class="derecha" style="width:10%;">Monto</th>
		<th class="derecha" style="width:10%;">Factura</th>
		<th class="derecha" style="width:10%;">Pedido</th>
		<th style="width:6%;">&nbsp;</th>
	</tr>
<?php
foreach($Movimientos as $Id_Mc_Movimiento => $Datos)
{
?>
	<tr>
		<td><?=date('d-m-Y', strtotime($Datos['fecha']))?></td>
		<td><?=$Datos['descripcion']?></td>
<?php
	if('101' == $Datos['linea'] || '109' == $Datos['linea'])
	{
?>
		<td><a href="/pedidos/pedido_detalle/index/<?=$Datos['mas_menos']?>" class="toolder" target="_blank"><?=$Datos['codigo']?><span>Ver Detalle</span></a></td>
<?php
	}
	else
	{
?>
		<td><span class="toolder"><?=$Datos['codigo']?> [<?=$Datos['mas_menos']?>]<span><?=$Datos['linea']?></span></span></td>
<?php
	}
	/*
		<td class="derecha"><?=number_format($Datos['cantidad'], 2)?></td>
		<td class="derecha">$<?=number_format($Datos['precio_unitario'], 2)?></td>
	*/
?>
		<td class="derecha">$<?=number_format($Datos['monto'], 2)?></td>
		<td class="derecha"><?=$Datos['factura']?></td>
		<td class="derecha"><?=$Datos['pedido']?></td>
		<td>
<?php
	if('101' == $Datos['linea'] || '109' == $Datos['linea'])
	{
?>
			&nbsp;
<?php
	}
	else
	{
?>
			<span info="<?=$Id_Mc_Movimiento?>" class="iconos ieliminar toolder"><span>Eliminar Movimiento</span></span>
<?php
	}
?>
		</td>
	</tr>
<?php
}
?>
</table>


<br /><br />


<script>
	
	$('.mov_fecha').datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
	
	
	$('#mostrar_movi').click(function()
	{
		$('#mov_agregar').show();
	});
	
	$('#mov_agregar [type="button"]').click(function()
	{
		$('#mov_agregar').hide();
		for(i = 0; i < 1; i++)
		{
			$('#mov_fila_'+i+' input').val('');
			$('#mov_fila_'+i+' select').val('');
			$('#mov_total_'+i).val('0');
		}
	});
	
	
	$('.calcular').blur(function()
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
	
	
	$('#mc_cambia_detalle').click(function()
	{
		window.location = '/conta/movimientos/detalle/'+$('#mc_linea').val()+'/'+$('#mc_anho_cambia').val()+'/'+$('#mc_mes_inicio').val();
	});
	
</script>


