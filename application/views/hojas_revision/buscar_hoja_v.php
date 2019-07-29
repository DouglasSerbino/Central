<?phpphp
if($limpiar == 'no')
{
	?>
<div class="informacion">
	<form action="/hojas_revision/buscar_hoja/index" method="post" name="miform" onsubmit="return validar_busq();">
		<strong>Ingrese el N&uacute;mero de Proceso</strong><br />
		<input type="text" name="codigo_cliente" size="8" />&nbsp;<strong>-</strong>&nbsp;
		<input type="text" name="proceso" size="15" />&nbsp;
		<input type="submit" class="boton" value="Buscar" />
	</form>
</div>
<?php
}
if(count($Cliente_Procesos) != 0)
{
	$contador = 0;
	foreach($Cliente_Procesos as $Datos)
	{
		$cliente = $Datos["cliente"];
		$id_proceso = $Datos["id_proceso"];
		$codigo_cliente = strtoupper($Datos["codigo_cliente"]);
		$producto = $Datos['nombre'];
		$contador++;
	}
?>
<div class="informacion">
	<div class="inf_titulo"><strong><?=$codigo_cliente.'-'.$proceso?></strong></div>
		Cliente: <strong><?=$cliente?></strong><br />
		Producto: <strong><?=$producto?></strong><br /><br />
		
		<strong>Listado</strong><br />
		
		<table class="tabular">
			<tr>
				<th>Hoja</th>
				<th>Fecha de Revisi&oacute;n</th>
			</tr>
<?php
foreach($Hojas_revision as $Datos)
{
	$fecha_entrada = $this->fechas_m->fecha_ymd_dmy($Datos["fecha"]);
?>
			<tr>
				<td style='width: 100px;'> &nbsp;
					<strong>
						<a href="/hojas_revision/hoja_impr/index/<?=$Datos["id_pedido"]?>/<?=$Datos['tipo_hoja']?>">Ver Hoja</a>
					</strong>
				</td>
				<td><?=$fecha_entrada?></td>
			</tr>
<?phpphp
}
?>
		</table>
</div>

<?phpphp
}
else
{
	if($limpiar != 'no')
	{
?>
	No se encontro hoja de revision para el proceso <strong><?=$codigo_cliente.'-'.$proceso?></strong>
<?phpphp
	}
}
?>