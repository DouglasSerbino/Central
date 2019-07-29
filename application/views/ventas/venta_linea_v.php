<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
?>
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<?php
$meses_v = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");//Listado de los meses con su respectivo numero util para mysql

$anho_inicial = 2006;
$anho_actual = date('Y');

$materiales_v = array();
$total_venta = 0;
//print_r($ventas_linea);
foreach($ventas_linea as $Datos)
{
	if('&nbsp;' == $Datos['material_solicitado'])
	{
		$Datos['material_solicitado'] = 'Otros';
	}
	
	if(!isset($materiales_v[$Datos['id_material_solicitado']]))
	{
		$materiales_v[$Datos['id_material_solicitado']]['material_solicitado'] = $Datos['material_solicitado'];
		$materiales_v[$Datos['id_material_solicitado']]['total'] = $Datos['total'];
		$materiales_v[$Datos['id_material_solicitado']]['pedidos'] = 1;
	}
	else
	{
		$materiales_v[$Datos['id_material_solicitado']]['total'] += $Datos['total'];
		$materiales_v[$Datos['id_material_solicitado']]['pedidos']++;
	}
	
	$total_venta += $Datos['total'];
	
}

foreach($porcentaje_linea as $Datos)
{
	if('&nbsp;' == $Datos['material_solicitado'])
	{
		$Datos['material_solicitado'] = 'Otros';
	}
	
	if(!isset($materiales_v[$Datos['id_material_solicitado']])){
		$materiales_v[$Datos['id_material_solicitado']]['material_solicitado'] = $Datos['material_solicitado'];
		$materiales_v[$Datos['id_material_solicitado']]['total'] = $Datos['total'];
		$materiales_v[$Datos['id_material_solicitado']]['pedidos'] = 1;
	}
	else
	{
		$materiales_v[$Datos['id_material_solicitado']]['total'] += $Datos['total'];
		$materiales_v[$Datos['id_material_solicitado']]['pedidos']++;
	}
	
	$total_venta += $Datos['total'];
}

?>

<div class="informacion">
	<strong>Ventas por L&iacute;nea</strong>
	<br />
	<form action="/ventas/venta_linea/index" method="post">
		
		<select name="mes" id="mes">
<?php
foreach($meses_v as $_mes => $nmes){
?>
			<option value="<?=$_mes?>"<?php echo ($_mes==$mes)?' selected="selected"':''; ?>><?=$nmes?></option>
<?php
}
?>
		</select>
		
		<select name="anho" id="anho">
<?php
for($i = $anho_inicial; $i <= $anho_actual; $i++){
?>
			<option value="<?=$i?>"<?php echo ($i==$anho)?' selected="selected"':''?>><?=$i?></option>
<?php
}
?>
		</select>
		
		<input type="submit" class="boton" value="Generar Reporte" />
	</form>

</div>

<?php
if(count($materiales_v) != 0)
{
?>

<div class="contenido">
	
	<br />
	<table style="width:55%; margin-left: 50px;">
		<tr>
			<th>L&iacute;nea</th>
			<th style="text-align: right;">Pedidos</th>
			<th style="text-align: right;">Venta</th>
		</tr>
<?php
//print_r($materiales_v);

$cien_redondo = 0;
foreach($materiales_v as $id_material => $material)
{
	$materiales_v[$id_material]['porcentaje'] = number_format(($material['total'] * 100) / $total_venta, 1);
	$cien_redondo += $materiales_v[$id_material]['porcentaje'];
?>
		<tr>
			<td><a class="iconos iexterna toolizq" href="/ventas/venta_linea_esp/index/<?=$id_material?>/<?=$anho?>/<?=$mes?>/<?=$Id_Cliente?>" target='_blanck'><span>Ver detalle en una ventana nueva</span></a>  <?=$material['material_solicitado']?></td>
			<td style="text-align: right;"><?=number_format($material['pedidos'], 0)?></td>
			<td style="text-align: right;">&nbsp; $<?=number_format($material['total'], 2)?></td>
		</tr>
<?php
}

//Necesario, sucio pero necesario
if($cien_redondo < 100)
{
	$materiales_v[$id_material]['porcentaje'] += (100 - $cien_redondo);
}
?>
		<tr>
			<th colspan="2">Total</th>
			<th style="text-align: right;">&nbsp; $<?=number_format($total_venta, 2)?></th>
		</tr>
	</table>
	<br />
	<div id="grafico-pastel" style="width:450px;height:250px;"></div>
	<script language='javascript' type='text/javascript'>
		$.plot($('#grafico-pastel'),
			[
<?php
foreach($materiales_v as $id_material => $material)
{
?>
				{
					label: '<?=$material['material_solicitado'].' '.$material['porcentaje'].'%'?>',  data: [[1,<?=$material['total']?>]]
				},
<?php
}
?>
			],
			{
				series:{ pie:{ show: true } },
				legend:{ show: true }
			}
		);
	</script>
</div>


<script>

var Clientes_v = {
<?php
foreach($Clientes as $Divi => $Cli)
{
?>
	'<?=$Divi?>': '<?php
	foreach($Cli as $Clie)
	{
?><option value="<?=$Clie['id_cliente']?>" <?=($Clie['id_cliente']==$Id_Cliente)?' selected="selected"':''?>><?=$Clie['cliente']?></option><?php
	}
?>',
<?php
}
?>
};

$('#idcliente').append('<option value="todos">Todos</option>');
<?php
if('' != $Divis)
{
?>
	$('#idcliente').append(Clientes_v.<?=$Divis?>);
<?php
}
?>
$('#division').change(function()
{
	$('#idcliente').empty();
	$('#idcliente').append('<option value="todos">Todos</option>');
	$('#idcliente').append(Clientes_v[$(this).val()]);
});

</script>

<?php
}

	$this->generar_cache_m->generar_cache($Cache);
}
?>