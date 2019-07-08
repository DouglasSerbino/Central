<script type="text/javascript" src="/html/js/detalle_fac.js?n=1"></script>
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<link rel="stylesheet" type="text/css" media="all" href="/html/css/detalle_fac.css" />
<style>
	input[type="checkbox"] {
		visibility: hidden;
		margin-left: -15px;
		}
</style>
<script>
	$(function()
	{
		$("[name=fecha_fac]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
		
	});
	function validarform()
	{
		if('' != $('#ver_cliente').val())
		{
			if('' != $('#factura').val())
			{
				$('#checkess input[type=checkbox]').each(function()
				{
					var checkbox = $(this);
					if(checkbox.is(':checked')== true)
					{
						$('#formul').submit();
					}
				});
			}
			else
			{
				alert('Ingrese el numero de Factura');
				$('#factura').focus();
			}
		}
		else
		{
			alert('Debe seleccionar un cliente');
			return false;
		}
	}
</script>
<body class="trabajo" onload="javascript:setInterval(permanente,50);">
<div class="informacion">
	
	<div class="informacion_cont">
	<form method='post' action='/facturacion/detalle_facturacion/facturar/' id='formul'>
		N&uacute;mero de Factura <input type='text' name='factura' id='factura'>
		<input type='text' name='fecha_fac' id='fecha_fac' value='<?=date('Y-m-d')?>' style='width: 100px;'>
		<input type="hidden" name="id_cliente_ver" value="<?=$Id_Cliente?>" />
		<br />
<?php 
//print_r($procesos);
$suma = 0;
foreach($procesos as $Informacion)
{
	$a = 0;
	$suma_cli = 0;
	$id_cliente = $Informacion["id_cliente"];
		if($Id_Cliente != "" && $id_cliente != $Id_Cliente)
		{
			continue;
		}
?>
	<table id="checkess" class="tbordes selecciones" style='width: 50%;'>
		<tr>
			<td colspan='4'>
				<strong><br />&raquo; <?=$Informacion['nombre_cliente']?></strong>
			</td>
		</tr>
<?php
		$a = 1;
			foreach($Informacion['procesos'] as $fila2)
			{
				$venta = $fila2["venta"];
				$fecha = date('d-m-Y', strtotime($fila2["fecha"]));
				$suma = $suma + $venta;
				$suma_cli = $suma_cli + $venta;
				$procesando = " (*)";
				if($fila2['fecha_reale'] != '0000-00-00')
				{
					$procesando = "";
				}
				$a++;
?>
		<tr id='fila_<?=$a?>'>
			<td style='width: 110px;'><?=$fila2['codigo_cliente']?>-<?=$fila2['proceso']?></td>
			<td style='width: 110px;'>
				<label for='check_<?=$a?>'><?=$fila2["sap"]?></label>
				<input type='checkbox' name='check_<?=$a?>' id='check_<?=$a?>' onclick="poner_color_fila('check_<?=$a?>','fila_<?=$a?>')" value='<?=$fila2["sap"]?>--<?=$fila2["id_pedido_sap"]?>'></a>
			</td>
			<td style='width: 100px;'><?=$fecha?></td>
			<td>$ <?=$venta?> <?=$procesando?></td>
		</tr>
<?php
	$a++;
			}
?>
		<tr>
			<td colspan="2"><strong>Total Cliente: &nbsp; </strong></td>
			<td><strong>$ <?=number_format($suma_cli, 2)?></strong></td>
		</tr>
	</table>	
<?php
}
?>
		<tr>
			<td colspan='4'><strong>Total: &nbsp; &nbsp; $ <?=number_format($suma, 2)?></strong></td>
		</tr>
	</table>
	<br />
	<input type='button' value='Reportar' onclick="validarform();">
</form>
		<br /><br />(*) Trabajos en proceso.
		
		<div id="div_ver_cliente" style="top: 170px;">
			<strong>Filtrar resultados por Cliente:</strong><br />
			<select name="ver_cliente" id="ver_cliente" onchange="ver_facturas()">
				<option value="">Todos</option>
<?php
foreach($procesos as $Informacion)
{
	$id_cliente = $Informacion['id_cliente'];
?>
				<option value="<?=$id_cliente?>" <?=($id_cliente == $Id_Cliente)?' selected="selected"':''?>><?=$Informacion['nombre_cliente']?></option>
<?php
}
?>
			</select>
		</div>
		
		<div id="formfac_div" style="top: 170px; visibility:hidden;display:none;">
			<form name="miformfac" id="miformfac" action="/facturacion/detalle_facturacion/facturar" method="POST">
				Pedido SAP: <input type="text" name="codigo_sap_fac" id="codigo_sap_fac" readonly="readonly" /><br />
				Fecha Fact.: <input type="text" size="11" name="fecha_fac" id="fecha_fac" value="<? echo date("Y-m-d"); ?>" /><br />
				<strong>Factura / IT:</strong><input type="text" name="factura" id="factura" /><br />
				<input type="button" value="Cancelar" class="boton" onclick="ocultar_fac()" />
				<input type="button" value="Guardar" class="boton" onclick="guardar_fac()" />
				<input type="hidden" value="" name="id_venta" id="id_venta" />
				<input type="hidden" name="id_cliente_ver" value="<?=$Id_Cliente?>" />
			</form>
		</div>
		
	</div>
	<script>
	$(function()
	{
		$('#checkess input[type=checkbox]').each(function()
		{
			var id_chk = $(this).attr('id');
			id_chk = id_chk.split('_');
			poner_color_fila('check_'+id_chk[1],'fila_'+id_chk[1]);
		})
		
	});
	
</script>
	<div class="informacion_bot"><div></div></div>
	
</div>
</body>