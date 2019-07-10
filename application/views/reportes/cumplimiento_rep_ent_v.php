<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
$meses_v = array('01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<title>REPORTE GERENCIAL</title>
		<link rel="shortcut icon" href="/html/img/ico-cg.png" />
		<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
		<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
		<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
		<link rel="stylesheet" href="/html/css/estilo.002.css" />
		<link href="/html/css/extra.css" rel="stylesheet" type="text/css" media="all" />
	
	<style>
		img{ border: none; }
		body{ font-size: 11px;}
		table{ border-collapse: collapse; }
		tr:hover{ background: #eeeeee; }
		#titulo
		{
			font-size: 18px;
		}
	</style>
	
</head>
<body>
<div id="contenedor-pagina">

<div id="encabezado" class='soy_encabezado'><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>_blanco.png" width="125" alt="<?=$this->session->userdata('grupo')?>" id="cloneme" /></div>

<?php
	$Pedidos_v = array();
	$Promedio_Tiempo = 0;
	$a = 1;
	$tentregas = 0;
	$Conta = array('1' => 'ENTREGAS A TIEMPO', '2' => 'ENTREGAS ATRASADAS', '3' => 'REPROCESOS');
	
	foreach($Resultado_pedidos as $Datos)
	{
		if('tot' == $tipo)
		{
			if(4 == $Datos['id_tipo_trabajo'] and $Datos['fecha_reale'] != "0000-00-00")
			{
				$estado = 'REPROCESOS';
				$a++;
			}
			elseif($Datos['fecha_reale'] <= $Datos['fecha_entrega'] and $Datos['fecha_reale'] != "0000-00-00")
			{
				$estado = 'TRABAJOS A TIEMPO';
				$a++;
			}
			elseif($Datos['fecha_reale'] > $Datos['fecha_entrega'] and $Datos['fecha_reale'] != "0000-00-00")
			{
				$estado = 'TRABAJOS ATRASADOS';
			}
		}
		else
		{
			$estado = $Titulos_v[$tipo];
		}
		
		$Pedidos_v[$estado][$Datos['id_pedido']]['proceso'] = $Datos['codigo_cliente'].'-'.$Datos['proceso'];
		$Pedidos_v[$estado][$Datos['id_pedido']]['nombre'] = $Datos['nombre'];
		$Pedidos_v[$estado][$Datos['id_pedido']]['fecha_entrada'] = $Datos['fecha_entrada'];
		$Pedidos_v[$estado][$Datos['id_pedido']]['fecha_entrega'] = $Datos['fecha_entrega'];
		$Pedidos_v[$estado][$Datos['id_pedido']]['fecha_reale'] = $Datos['fecha_reale'];
		$Pedidos_v[$estado][$Datos['id_pedido']]['id_pedido'] = $Datos['id_pedido'];
		$Pedidos_v[$estado][$Datos['id_pedido']]['codigo_cliente'] = $Datos['codigo_cliente'];
		
		if('atr' == $tipo)
		{
			$FE = $this->fechas_m->fecha_subdiv($Datos['fecha_entrega'].' 00:00:01');
			$FR = $this->fechas_m->fecha_subdiv($Datos['fecha_reale'].' 00:00:01');
			$DiasAtrasado = (mktime(0,0,0,$FR['mes'],$FR['dia'],$FR['anho'])) - (mktime(0,0,0,$FE['mes'],$FE['dia'],$FE['anho']));
			$Promedio_Tiempo += ($DiasAtrasado / (60 * 60 * 24));
		}
	}
?>
<br /><br /><br />
<div id="titulo">
	<center>REPORTE GERENCIAL &nbsp; - &nbsp; <?=$Cliente['nombre']?> &nbsp; <?=$meses_v[$mes]?>-<?=$anho?></center>
</div>
<table style="width:950px; left: 180px; position: absolute">
	<caption>
		<strong>
			<?=(isset($Titulos_v[$tipo])?$Titulos_v[$tipo]:'')?>&nbsp; [ <?=count($Resultado_pedidos)?>]
<?php
if('atr' == $tipo)
{
?>
			<br />
			<?=(count($Resultado_pedidos) != 0)?(number_format(($Promedio_Tiempo / count($Resultado_pedidos)), 2)):'0.00'?> D&iacute;as Promedio
<?php
}
?>
		</strong>
	</caption>
<?php

foreach($Pedidos_v as $Opcion => $info)
{
	$a = 1;
?>
	<tr>
		<th colspan='7'>
			<br /><br /><?=$Opcion?> (<?=count($info)?>)
		</th>
	</tr>
	<tr>
		<th style="width:110px;">Proceso</th>
		<th style="width:400px;">Nombre</th>
		<th style="text-align:right;width:80px;">Ingreso</th>
		<th style="text-align:right;width:80px;">Estimada</th>
		<th style="text-align:right;width:90px;">Real</th>
	</tr>
<?php
	foreach($info as $Id_Pedido => $D_A)
	{
?>
	<tr>
		<td><a class="iconos iexterna toolizq" href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$Id_Pedido?>');"><span>Detalles del pedido</span></a>&nbsp;<?=$D_A['proceso']?></td>
		<td><?=$D_A['nombre']?></td>
		<td style="text-align: right;"><?=date('d-m-Y', strtotime($D_A['fecha_entrada']))?></td>
		<td style="text-align: right;"><?=date('d-m-Y', strtotime($D_A['fecha_entrega']))?></td>
		<td style="text-align: right;"><?=date('d-m-Y', strtotime($D_A['fecha_reale']))?></td>
	</tr>
<?php
$a++;
	}
}
?>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</div>
</body>
</html>
<?php
	$this->generar_cache_m->generar_cache($Cache);
}
?>