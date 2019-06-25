<script type="text/javascript" src="/html/js/extra.js?n=1"></script>
<link rel="stylesheet" type="text/css\" media="all" href="/html/css/extra_rep.css" />
<?
foreach($departamento as $Datos_dpto)
{
	$codigo = $Datos_dpto["codigo"];
	$dpto_nombre = $Datos_dpto["departamento"];
	
}
$meses_v = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");
?>

<div class="informacion">
	
	<a href="/extras/extra_rep/index/pre/<?=$mes.'/'.$anho?>" title="Regresar al Reporte"><strong>&laquo;&laquo; Regresar &laquo;&laquo;</strong></a> &nbsp; &nbsp;
	
	<br /><strong>Reporte de Horas Extras por Departamento &nbsp; - &nbsp; <?=$codigo?> [ <?=$dpto_nombre?> ]</strong>
	<br /><br />
	
<?
if(count($usuarios) > 0)
{
	foreach($proyecciones as $Datos_proyeccion)
	{
		$proyeccion_dpto = $Datos_proyeccion["proyeccion"];
	}
	$usuario_extra = array();
	$usuario_id = array();

	foreach($usuarios as $Datos_usuario)
	{
		if($Datos_usuario["usuario"] == "PLANCHAS")
		{
			continue;
		}
		
		$usuario_id[$Datos_usuario["id_usuario"]] = array( $Datos_usuario["usuario"] );
		$usuario_extra[$Datos_usuario["id_usuario"]] = array();
		$usuario_extra[$Datos_usuario["id_usuario"]]["usuario"] = $Datos_usuario["usuario"];
		
		$usuario_extra[$Datos_usuario["id_usuario"]]["total_h"] = $Datos_usuario["total_h"];
		$usuario_extra[$Datos_usuario["id_usuario"]]["total_m"] = $Datos_usuario["total_m"];
	}
?>
<div style="border-top:1px;"><strong style="text-decoration:underline;"><?=$meses_v[$mes].' - '.$anho?></strong></div>

<table>
	<tr>
		<td>
			<table style="text-align:right; width:150%;" class="tabular">
				<tr>
					<th style="width:25%;">Puesto</th>
					<th style="width:15%;">Horas</th>
					<th style="width:15%;">Costo</th>
					<th style="width:15%;">Porcentaje</th>
				</tr>
<?php

$total_tm = 0;
$filas_users = "";
$i = 0;
foreach($usuario_extra as $id_user => $datos_v)
{
	$porcentaje = 0; if($proyeccion_dpto > 0)
	{
		$porcentaje = number_format(($datos_v["total_m"] * 100) / $proyeccion_dpto, 2);
	}
	$total_h = number_format($datos_v["total_h"], 2);
	$total_m = number_format($datos_v["total_m"], 2);
	$total_tm += $datos_v["total_m"];
?>
				<tr>
					<td><?=$datos_v["usuario"]?></td>
					<td><?=$total_h?></td>
					<td>$ <?=$total_m?></td>
					<td><?=$porcentaje?> %</td>
				</tr>
<?php
	$i++;
	
}

$porcentaje_tt = 0;
if($proyeccion_dpto > 0)
{
	$porcentaje_tt = number_format(($total_tm * 100) / $proyeccion_dpto, 2);
}
?>
					<tr>
						<th>Proyecci&oacute;n: $ <?=number_format($proyeccion_dpto, 2)?></th>
						<th>Total</th><th>$ <?=number_format($total_tm, 2)?></th>
						<th><?=$porcentaje_tt?> %</th>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<?php
}
?>