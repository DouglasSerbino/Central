<script>
	$(function()
	{
		$("[name=fecha_inicio]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
		$("[name=fecha_fin]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
	});
</script>
<div class="informacion" id='reporte_semanal'>
	<form name="form_venta" id="form_venta" action="/reportes/tiempos" method="post">
		
		<input type="text" name="fecha_inicio" id="fecha_inicio" size="15" value="<? echo $Fecha_Inicio; ?>" readonly="readonly" />
		<input type="text" name="fecha_fin" id="fecha_fin" size="15" value="<? echo $Fecha_Fin; ?>" readonly="readonly" />
		
		<select name="tipo_tiempo">
			<option value="pendiente"<?=('pendiente'==$Tipo_Tiempo)?' selected="selected"':''?>>Pendientes de Facturaci&oacute;n</option>
			<option value="cambios"<?=('cambios'==$Tipo_Tiempo)?' selected="selected"':''?>>Cambios sin costos</option>
			<option value="reprocesos"<?=('reprocesos'==$Tipo_Tiempo)?' selected="selected"':''?>>Reprocesos</option>
			<option value="todos"<?=('todos'==$Tipo_Tiempo)?' selected="selected"':''?>>Todos</option>
		</select>
		
		<br />
		
		<select name="cliente" id="cliente">
			<option value="--">Seleccionar Cliente</option>
<?
foreach($mostrar_clientes as $Cliente)
{
?>
			<option value="<?=$Cliente["id_cliente"]?>"<?=($Id_Cliente==$Cliente["id_cliente"])?' selected="selected"':''?>><?=$Cliente["codigo_cliente"]?> - <?=$Cliente["nombre"]?></option>
<?
}
?>
		</select>
		
		<input type="submit" value="Ver reporte" />
		
	</form>
</div>
<div class='informacion'>
<table style='width: 90%;'>	
<?php
if('' != $Id_Cliente)
{
$Cantidad_Total = array();
foreach($pedidos as $Datos_pedido)
{
?>
<table>
	<tr>
		<td><strong><?=$Datos_pedido["codigo_cliente"]."-".$Datos_pedido["proceso"]?></strong></td>
		<td></td>
		<td><strong><?=$Datos_pedido["nombre"]?></strong></td>
	</tr>
</table>
<table style='width: 90%;'>
	<tr>
		<td colspan='4' align='center'>
		<table style='width: 99%;'>
			<tr style='background-color: #c7c7c7;'>
				<th style='width: 25%;'>Puesto</th>
				<th>Fecha</th>
				<th>Inicio</th>
				<th>Fin</th>
				<th>Horas</th>
				<th>Turno</th>
			</tr>
<?php
foreach($informacion_usuarios as $Datos_general)
{
	foreach($Datos_general as $Datos)
	{
		$pedido_comparar = $Datos_pedido['id_pedido'];
		//and '132' == $this->session->userdata('id_usuario')
		
		if($pedido_comparar == $Datos['id_pedido'])
		{
			
			//date('d-m-Y H:i:s', strtotime('- 2 hour', strtotime('2012-01-01 12:30:00')));
			$turno = 1;
			$fecha_hora = $this->fechas_m->fecha_subdiv($Datos['inicio']);
			$horas = $this->fechas_m->minutos_a_hora($Datos["tiempo_usuario"]);
			$fin = date('H:i', strtotime("+ ".$Datos["tiempo_usuario"]." minutes", strtotime($Datos['inicio'])));
			
			if(!isset($Cantidad_Total['tiempos'][$Datos['id_usuario']]))
			{
				$Cantidad_Total['tiempos'][$Datos['id_usuario']]['puesto'] = $Datos['puesto'].' '.$Datos['usuario'];
				$Cantidad_Total['tiempos'][$Datos['id_usuario']]['horas'] = 0;
			}
			
			if(isset($Cantidad_Total['tiempos'][$Datos['id_usuario']]))
			{
				$Cantidad_Total['tiempos'][$Datos['id_usuario']]['horas'] += $Datos['tiempo_usuario'];
			}
			
			if($fecha_hora['hora'] < 8 || $fecha_hora["hora"] >= 17)
			{
				$turno = 2;
			}
?>
			<tr>
				<td><?=$Datos['puesto']?> <?=$Datos['usuario']?></td>
				<td><?=$fecha_hora['dia']?>-<?=$fecha_hora['mes']?> <?=$fecha_hora['anho']?></td>
				<td><?=$fecha_hora['hora']?>:<?=$fecha_hora['minuto']?></td>
				<td><?=$fin?></td>
				<td><?=$horas?></td>
				<td><?=$turno?></td>
		</tr>
<?php
			
		}
	}
}
?>
	</table>
	
<?php
$PTC_Totales = array();
$total = 0;
$cantidad = 0;
$producto = '';
$precios = array();

if(isset($productos[$Datos_pedido['id_pedido']]))
{
?>	
	<table style='width: 99%;'>
		<tr style='background-color: #c7c7c7;'>
			<th style='width: 50%;'><strong>Producto</strong></th>
			<th><strong>Cantidad</strong></th>
			<th><strong>Precio</strong></th>
			<th><strong>Total</strong></th>
		</tr>
<?php
	foreach($productos as $Datos_prod)
	{
		foreach($Datos_prod as $Datos_producto)
		{
			if($Datos_producto['id_pedido'] == $Datos_pedido['id_pedido'])
			{
?>
		<tr>
			<td><?=$Datos_producto['producto']?></td>
			<td><?=$Datos_producto['cantidad']?></td>
			<td>$<?=$Datos_producto['precio']?></td>
			<td>$<?=$Datos_producto['total']?><br /></td>
		</tr>
<?php
				if(!isset($Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]))
				{
					$Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]['producto'] = $Datos_producto['producto'];
					$Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]['precio'] = $Datos_producto['precio'];
					$Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]['total'] = floatval($Datos_producto['cantidad']) * floatval($Datos_producto['precio']);
					$Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]['cantidad'] = $Datos_producto['cantidad'];
				}
				else
				{
					$Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]['total'] += floatval($Datos_producto['cantidad']) * floatval($Datos_producto['precio']);
					$Cantidad_Total['cotizacion'][$Datos_producto['id_producto']]['cantidad'] += $Datos_producto['cantidad'];
				}	
			}
		}
	}
		
?>
			</table>
<?php
}

if(count($informacion_materiales) != 0)
{
	if(isset($informacion_materiales[$Datos_pedido['id_pedido']]))
	{
?>	
	<table style='width: 99%;'>
	<tr style='background-color: #c7c7c7;'>
			<th>Codigo</th>
			<th style='width: 50%;'>Nombre del Material</th>
			<th>Cantidad</th>
			<th>Tipo</th>
		</tr>
	<?php
		foreach($informacion_materiales as $Datos_material)
		{
			foreach($Datos_material as $materiales)
			{
				if($Datos_pedido['id_pedido'] == $materiales['id_pedido'])
				{
?>
		<tr>
			<td style='width: 10%;'><?=$materiales['codigo_sap']?></td>
			<td style='width: 30%;'><?=$materiales['nombre_material']?></td>
			<td style='width: 20%;'><?=$materiales['cantidad']?></td>
			<td style='width: 20%;'><?=$materiales['tipo']?><br /></td>
		</tr>
<?php

					if(!isset($Cantidad_Total['materiales'][$materiales['id_inventario_material']]))
					{
						$Cantidad_Total['materiales'][$materiales['id_inventario_material']]['material'] = $materiales['nombre_material'];
						$Cantidad_Total['materiales'][$materiales['id_inventario_material']]['codigo'] = $materiales['codigo_sap'];
						$Cantidad_Total['materiales'][$materiales['id_inventario_material']]['tipo'] = $materiales['tipo'];
						$Cantidad_Total['materiales'][$materiales['id_inventario_material']]['cantidad'] = intval($materiales['cantidad']);
					}
					else
					{
						$Cantidad_Total['materiales'][$materiales['id_inventario_material']]['cantidad'] += intval($materiales['cantidad']);
					}
				}
			}
		}
?>
			</table>
<?php
	}
}
?>
			<br>
			<br>
		</td>
	</tr>
<?php
}

?>
<table>
	<tr>
			<th colspan="5">TOTALES</th>
		</tr>
</table>
<table class='tabular' style='width: 30%;'>
		<tr>
			<th>Puesto</th><th>Horas</th>
		</tr>
<?
if(isset($Cantidad_Total['tiempos']))
{
	foreach($Cantidad_Total['tiempos'] as $Id_Usuario => $D_T)
	{
?>
		<tr>
			<td style='width: 15%;'><?=$D_T['puesto']?></td>
			<td style='width: 15%;'><?=$this->fechas_m->minutos_a_hora($D_T['horas'])?></td>
		</tr>
<?
	}
}
	
	
?>
</table>
<br />
<?php
if(isset($Cantidad_Total['cotizacion']))
{
?>
<table class='tabular' style='width: 60%;'>
		<tr>
			<th colspan="5">Producto</th><th>Cantidad</th><th>Precio</th><th>Total</th>
		</tr>
<?	
	
		foreach($Cantidad_Total['cotizacion'] as $Id_Producto => $D_C)
		{
?>
		<tr>
			<td colspan="5"><?=$D_C['producto']?></td>
			<td><?=$D_C['cantidad']?></td>
			<td>$<?=($D_C['total']/$D_C['cantidad'])?></td>
			<td>$<?=$D_C['total']?></td>
		</tr>
<?
		}
}

?>
</table>
<?php
	if(isset($Cantidad_Total['materiales']))
	{
	?>
<br />
<table class='tabular' style='width: 60%;'>
		<tr>
			<th>Material</th><th colspan="5">Descripci&oacute;n</th><th>Cantidad</th><th>Tipo</th>
		</tr>
<?
		foreach($Cantidad_Total['materiales'] as $Id_Material => $D_M)
		{
?>
		<tr>
			<td><?=$D_M['codigo']?></td>
			<td colspan="5"><?=$D_M['material']?></td>
			<td><?=$D_M['cantidad']?></td>
			<td><?=$D_M['tipo']?></td>
		</tr>
<?
		}
	}
?>
</table>
<?php
}
?>
</div>