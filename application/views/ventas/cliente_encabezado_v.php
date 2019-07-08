

<style>
	.vent250{
		width: 250px;
	}
	.vent100{
		width: 100px;
	}
	#datos_vent_clie{
		position: absolute;
		padding-right: 25px;
		padding-bottom: 50px;
	}
	#datos_vent_clie td{
		border: 1px solid #cccccc;
	}
	#datos_vent_clie th{
		border: 1px solid #aaaaaa;
	}
	.manita
	{
		cursor: pointer;
	}
	.ven_cli_porcen{
		color: #444444;
		background: #e9e9e9;
	}
</style>


<div style="line-height:33px;">
	
	<form name="miform" id="miform" action="/ventas/cliente" method="post">
		
		<input type="checkbox" name="rango_fecha" id="rango_fecha"<?php echo ('on'==$multiple)?' checked="checked"':''; ?> /><label for="rango_fecha"><strong>Multiple</strong></label>
		
		<select name="mes" id="mes">
<?php
foreach($meses_v as $Index => $D_A){
?>
			<option value="<?php echo $Index; ?>"<?php echo ($imes==$Index)?' selected="selected"':''; ?>><?php echo $D_A; ?></option>
<?php
}
?>
		</select>
		
		<select name="anho" id="anho">
<?php
for($i = $anho_inicio; $i <= $anho_fin; $i++){
?>
			<option value="<?php echo $i; ?>"<?php echo ($ianho==$i)?' selected="selected"':''; ?>><?php echo $i; ?></option>
<?php
}
?>
		</select>
		
		<span id="fecha_multiple"<?php if('' == $multiple) echo ' style="display:none;"'; ?>>
			&laquo;&raquo;
			
			<select name="mes2" id="mes2">
<?php
foreach($meses_v as $Index => $D_A){
?>
			<option value="<?php echo $Index; ?>"<?php echo ($fmes==$Index)?' selected="selected"':''; ?>><?php echo $D_A; ?></option>
<?php
}
?>
			</select>
			
			<select name="anho2" id="anho2">
<?php
for($i = $anho_inicio; $i <= $anho_fin; $i++){
?>
			<option value="<?php echo $i; ?>"<?php echo ($fanho==$i)?' selected="selected"':''; ?>><?php echo $i; ?></option>
<?php
}
?>
			</select>
		</span>
		
		<select name="vista" id="vista">
			<option value="todo">Completo</option>
			<option value="venta"<?php if('venta' == $vista) echo ' selected="selected"'; ?>>Ventas</option>
			<option value="proyeccion"<?php if('proyeccion' == $vista) echo ' selected="selected"'; ?>>Proyecciones</option>
		</select>
		

		<select name="mostrar">
			<option value='clie'>Vista Cliente</option>
			<option value='pedi'<?=('pedi'==$filtro_mostrar)?' selected="selected"':'';?>>Vista Pedido</option>
			<!--option value='fact'<?=('fact'==$filtro_mostrar)?' selected="selected"':'';?>>Vista Factura</option-->
		</select>

		
		<input type="button" value="Ver Reporte" onclick="javascript:$('#miform').submit();" class="boton" />
		
		<br />
		
		<!-- strong>Filtrar por:</strong-->
		
		<select name="tipo_vt" style="display: none;">
			<option value='todos'>Ventas</option>
			<option value='con'<?=('con'==$filtro_venta)?' selected="selected"':'';?>>Con Ventas</option>
			<option value='sin'<?=('sin'==$filtro_venta)?' selected="selected"':'';?>>Sin Ventas</option>
		</select>
		
		<select name="vendedor" style="display: none;">
			<option value='todos'>Vendedor</option>
<?php
/*
foreach($vendedores as $Vendedor)
{
	$Vendedores = $Vendedor['usuario'];
?>
			<option value='<?=$Vendedor['id_usuario'];?>'<?=($Vendedor['id_usuario']==$filtro_vendedor)?' selected="selected"':'';?>><?=$Vendedores?></option>
<?php
}
*/
?>
		</select>
		
	</form>
	
</div>



<script>
	$(function()
	{
		$('#rango_fecha').click(function()
		{
			if($(this).attr('checked'))
			{
				$('#fecha_multiple').show();
			}
			else
			{
				$('#fecha_multiple').hide();
			}
		})
	})
	
	$('.span_mostrar').click(function()
	{
		$(this).parent().children('span').toggle();
		if('mos' == $(this).attr('acc'))
		{
			$('.tbody_'+$(this).attr('tipo')).show();
		}
		else
		{
			$('.tbody_'+$(this).attr('tipo')).hide();
		}
	})
</script>

