<script type="text/javascript" src="/html/js/inventario.js?n=1"></script>
<link rel="stylesheet" href="/html/css/pedido.css" />
<?php

$nombre_mat ='';
if(isset($info_material['nombre_material']))
{
	$nombre_mat = $info_material['nombre_material'];
}
?>
<label style='font-size:14px;'>Nombre del Material: <strong><?=$nombre_mat?></strong></label><br /><br />
<?php
foreach($Informacion_transito as $Datos)
{
?>
		<table>
			<tr>
				<th style='width: 120px;'>Numero de Orden</th>
				<th style='width: 120px;'>Cantidad Total</th>
				<th style='width: 120px;'>Fecha de Ingreso</th>
				<th style='width: 500px;'>Detalle</th>
			</tr>
			<tr>
				<td>
<?php
	if($Datos['finalizado'] == 'n')
	{
?>
					<a href='/herramientas_sis/agregar_ped_tran/index/<?=$Datos['id_inventario_material']?>/<?=$Datos['orden']?>/mod'><?=$Datos['orden']?></a>
<?php
	}
	else
	{
?>
					<?=$Datos['orden']?>
<?php
	}
?>
			</td>
	
				<td><?=$Datos['cantidad_solicitada']?></a> <?=$Datos['tipo']?></td>
				<td><?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha_ingreso'])?></td>
				<td><?=$Datos['detalle']?></td>
			</tr>
		</table>
			
<?php
	if(count($Datos['detalles']) != 0)
	{
?>
		<table class='tabular' style='width: 800px;'>
			<tr>
				<th style='width: 140px;'>Cantidad Anterior</th>
				<th style='width: 150px;'>Cantidad Ingresada</th>
				<th style='width: 140px;'>Cantidad Restante</th>
				<th style='width: 120px;'>Fecha de Ingreso</th>
			</tr>
<?php
		foreach($Datos['detalles'] as $Datos_cantidad)
		{
?>
			<tr>
				<td><?=$Datos_cantidad['cant_anterior']?> <?=$Datos['tipo']?></td>
				<td><?=$Datos_cantidad['cant_ingresar']?> <?=$Datos['tipo']?></td>
				<td><?=$Datos_cantidad['restante']?> <?=$Datos['tipo']?></td>
				<td><?=$this->fechas_m->fecha_ymd_dmy($Datos_cantidad['fecha'])?></td>
			</tr>
<?php
		}
?>
		</table>
	<br />
<?php
	}

}
?>
	</form>