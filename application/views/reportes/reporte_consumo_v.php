<script type="text/javascript" src="/html/js/inventario.js?n=1"></script>
<style>
	td, th{
		border-left: solid 1px;
		border-color: #808080;
	}
	th{
		border-bottom: solid 1px;
		border-top: solid 1px;
	}
	
</style>
<div class='contenedor'>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<strong>Filtros:</strong> &nbsp;
		
		<select name="codigo" id="codigo" onchange="inventario_filtro2()">
			<option value="todos">C&oacute;digos Comp.</option>
			<option value="20" <?=("20"==$Codigo)?' selected="selected"':''?>>Mat. Prima</option>
			<option value="50" <?=("50"==$Codigo)?' selected="selected"':''?>>Materiales</option>
		</select>
		
		<select name="proveedor" id="proveedor" onchange="inventario_filtro2()">
			<option value="--">Todos los Proveedores</option>
			<?php
				foreach($Mostrar_proveedor as $Datos_proveedor)
				{
					?>
						<option value="<?=$Datos_proveedor['id_inventario_proveedor']?>" <?=($Cod_Proveedor==$Datos_proveedor['id_inventario_proveedor'])?' selected="selected"':''?>><?=$Datos_proveedor['proveedor_nombre']?></option>
					<?php
				}
			?>
		</select>
		
		<select name="equipo" id="equipo" onchange="inventario_filtro2()">
			<option value="--">Equipo/Area</option>
				<?php
				foreach($Mostrar_Equipo as $Datos_equipo)
				{
					?>
						<option value="<?=$Datos_equipo['id_inventario_equipo']?>"<?=($Cod_Equipo==$Datos_equipo['id_inventario_equipo'])?' selected="selected"':''?>><?=$Datos_equipo['nombre_equipo']?></option>
					<?php
				}
			?>
		</select>
		
		<select name="ver_cantidad" id="ver_cantidad" onchange="inventario_filtro2()">
			<option value="todos">Existencias</option>
			<option value="con" <?=('con'==$Cantidad)?' selected="selected"':''?>>Con Existencias</option>
			<option value="sin" <?=('sin'==$Cantidad)?' selected="selected"':''?>>Sin Existencias</option>
		</select>

		<br /><br />

	<table class="tabular" style='margin-left: -10px; width: 100%; border-left-style: solid 5px;'>
		<tr style='font-size: 10px; background-color:#D0D0D0; text-align: center;'>
			<th style='width: 2%;'></th>
			<th style='width: 7%;'>C&oacute;digo</th>
			<th style='width: 31%;'>Material</th>
			<th style='width: 12%;'><label class="toolizq">Cantidad M<span>Cantidad del Material</span></label></th>
			<th style='width: 6%;'><label class="toolizq">Total<span>Total Placas &oacute; Pzas.</span></label></th>
			<th style='width: 5%;'><label class="toolizq">Total C<span>Total de Cajas</span></label></th>
			<th style='width: 4%;'><label class="toolizq">Stock<span>Dias de Stock</span></label></th>
			<th style='width: 8%;'><label class="toolizq">Consumo<span>Consumo (Mensual)</span></label></th>
			<th style='width: 7%;'><label class="toolizq">Compra S.<span>Compra Semestral</span></label></th>
			<th style='width: 9%;'><label class="toolizq">Ped. Transito<span>Pedidos en Transito</span></label></th>
			<th style='width: 6%;'><label class="toolizq">Costo<span>Costo de Inventario</span></label></th>
		</tr>
<?
$total_valor = 0;

//Verificamos si hay informacion para mostrar.
if(0 < count($Materiales))
{
	//Exploramos el array.
	foreach($Materiales as $Material)
	{
		//Asignamos la informacion a las variables.
		$id_material = $Material["id_inventario_material"];
		$cantidad = $Material["cantidad_unidad"];
		$valor = $Material["valor"];
		$nombre_material = $Material['nombre_material'];
		$existencias_v[] = $cantidad;
		$total_cajas = 0;
		$total_placas = 0;
		$estilo = 'style="background-color:#ffff40; color:#fffffff;"';
		$titulo = 'title="Debe de verificar las existencias"';
		$total_stock = 0;
		$Consumo_mensual = 0;
		
		if(0 < $Material['numero_individual'])
		{
			$total_placas = number_format(($Material['existencias'] / $Material['numero_individual']), 0);
			if(0 < $Material['numero_cajas'])
			{
				$total_cajas = number_format(($Material['existencias'] / $Material['numero_individual'] / $Material['numero_cajas']), 0);
				$total_cajas2 = ($Material['existencias'] / $Material['numero_individual'] / $Material['numero_cajas']);
			}
		}
		
		$mos_tipo = '';
		if($Material["tipo"] == 'IN2')
		{
			$mos_tipo = 'cajas';
		}
		elseif($Material["tipo"] == 'PZA')
		{
			$mos_tipo = 'cajas';
		}
		else
		{
			$mos_tipo = $Material["tipo"];
		}
				
		//Consumo mensual de cada material.
		if(isset($Consumo[$id_material]))
		{
			$Consumo_mensual = $Consumo[$id_material]['consumo_cajas'];
			
			if(0 < $Consumo_mensual)
			{
				$total_stock = number_format(($total_cajas2 * 24 / $Consumo_mensual), 0);
			}
			
			if($total_stock > 24 and $total_stock < 40)
			{
				$estilo = '';
				$titulo = 'title=""';
			}
			elseif($total_stock > 40)
			{
				$estilo = 'style="background-color:#ffac59; color:#fffffff;"';
				$titulo = 'title="No es necesario mas materiales"';
			}
		}
		
		if($total_stock <= 5)
		{
			$estilo = 'style="background-color:#ff5555; color: #fff;"';
			$titulo = 'title="Debe de realizar un pedido de materiales"';
		}
?>
			<td>
				<label class="iconos imas toolizq" onclick='colocar(<?=$id_material?>)'><span>Agregar Solicitud</span></label>
			<td><a href='/reportes/detalle_transito/index/<?=$id_material?>'><?=$Material['codigo_sap']?></a></td>
			<td><?=$nombre_material?></td>
			<? $total = number_format(($Material['existencias'] * $cantidad), 0); ?>
			<td><?=$total.' '.$Material["tipo"]?></td>
			<td><?=$total_placas?></td>
			<td><?=$total_cajas?></td>
		<?php
		if($Consumo_mensual == 0 and 0 < $total_placas )
		{
			$estilo = '';
			$mos_tipo = '';
		}
		?>
		<td <?=$estilo?>><label <?=$titulo?>><?=$total_stock?></label></td>
		<td><?=number_format($Consumo_mensual, 1)?> <?=$mos_tipo?></td>
		<?php $total_comprar = number_format(($Consumo_mensual * 6), 1); ?>
		<td><?=$total_comprar?></td>
		<td>
		<?php
		//Pedidos en Transito
		$info_pedido = '';
		if(isset($Pedido_transito[$id_material]) and 0 < $Pedido_transito[$id_material])
		{
		?>
			<a href='/herramientas_sis/info_transito/index/<?=$id_material?>/<?=$Pedido_transito[$id_material]['orden']?>'> <?=$Pedido_transito[$id_material]['cantidad']?> <?=$mos_tipo?></a>
		<?php
		}
			$total = $Material['existencias'] * $valor;
			$total_valor += $total;
		?>
		<td>$ <?=number_format($total, 2)?></td>
		</tr>
<?php
	}
?>
		<tr>
			<th colspan="9">&nbsp;</th>
			<th>Total</th>
			<th>$<?=number_format($total_valor, 2)?></th>
		</tr>
	</table>
</div>
</div>
<?php
}
?>
</div>
</div>