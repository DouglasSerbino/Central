<script type="text/javascript" src="/html/js/inventario.js?n=1"></script>
<link rel="stylesheet" href="/html/css/pedido.css" />
<strong>Filtros:</strong> &nbsp;

<select name="codigo" id="codigo" onchange="inventario_filtro()">
	<option value="todos">C&oacute;digos Comp.</option>
	<option value="mp" <?=("mp"==$Codigo)?' selected="selected"':''?>>Mat. Prima</option>
	<option value="mt" <?=("mt"==$Codigo)?' selected="selected"':''?>>Materiales</option>
</select>

<select name="proveedor" id="proveedor" onchange="inventario_filtro()">
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

<select name="equipo" id="equipo" onchange="inventario_filtro()">
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

<select name="ver_cantidad" id="ver_cantidad" onchange="inventario_filtro()">
	<option value="todos">Existencias</option>
	<option value="con" <?=('con'==$Cantidad)?' selected="selected"':''?>>Con Existencias</option>
	<option value="sin" <?=('sin'==$Cantidad)?' selected="selected"':''?>>Sin Existencias</option>
</select>

<table class="tabular" style="width: 100%">
	<tr>
		<th>C&oacute;digo</th>
		<th>Material</th>
		<th>Cantidad</th>
		<th>Total</th>
		<th>UMB</th>
		<th>Valor</th>
	</tr>
<?php
$total_valor = 0;
$fila_v = array();
$existencias_v = array();

if($Materiales > 0)
{
	foreach($Materiales as $Material)
	{
	$id_material = $Material["id_inventario_material"];
	$cantidad = $Material["cantidad_unidad"];
	if('' == $cantidad)
	{
		$cantidad = 0;
	}
	$mat_pais = $Material['mat_pais'];
	$existencias = $Material['existencias'];
	if('' == $existencias)
	{
		$existencias = 0;
	}
	$tipo = $Material["tipo"];
	$valor = $Material["valor"];
	if('' == $valor)
	{
		$valor = 0;
	}
	$codigo_sap = $Material['codigo_sap'];
	$nombre_material = $Material['nombre_material'];
	//echo $codigo_sap.'-'.$Material['numero_individual'].'**<br>';
	$existencias_v[] = $cantidad;
	if('IN2' == $tipo)
	{
		if(0 < $Material['numero_individual'] && 0 < $Material['numero_cajas'])
		{
			$cajas = number_format(($existencias / ($Material['numero_individual'] * $Material['numero_cajas'])) , 0);
		}
	}
	else
	{
		$cajas = $existencias;
	}
	
	$fila_tr = "			<tr>\n";
	$fila_tr .= "       <td>";
	//<a href=\"/herramientas_sis/agregar_ped_tran/index/$id_material\" class=\"iconos imas toolizq\"><span>Agregar Pedido en Transito</span></a>
	$fila_tr .= "       <a href=\"/inventario/modificar_material/index/$id_material\" class=\"iconos ieditar toolizq\"><span>Modificar Material</span></a>";
	$fila_tr .= "        <a href=\"/inventario/inventario_lot/index/$id_material/0/0\" class=\"toolizq\">$codigo_sap<span>Ver Detalle</span></a>";
	$fila_tr .= "<td><strong>[".$mat_pais."]</strong> $nombre_material</td>";
	$fila_tr .= "				<td class=\"derecha\">$cajas</td>\n";
	$total = number_format(($existencias * $cantidad), 0);
	$fila_tr .= "				<td class=\"derecha\">$total</td>\n";
	$fila_tr .= "				<td>$tipo</td>\n";
	$total = ($existencias * $valor);
	$total_valor += $total;
	$fila_tr .= "				<td class=\"derecha\">$".number_format($total, 2)."</td>\n";
	
	$fila_tr .= "			</tr>\n";
	$fila_v[] = $fila_tr;
}


foreach ($existencias_v as $index => $valor)
{
	if($Cantidad != "todos")
	{
		if($Cantidad == "con")
		{
			if($valor > 0)
			{
				echo $fila_v[$index];
			}
		}
		else
		{
				echo $fila_v[$index];
		}
	}
	else
	{
		echo $fila_v[$index];
	}
}
?>
		<tr>
			<th colspan="4">&nbsp;</th>
			<th>Total</th>
			<th>$<?php echo number_format($total_valor, 2); ?></th>
		</tr>
	
	</table>
	
</div>

<?php
}
?>