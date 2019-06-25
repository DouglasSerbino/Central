<?
foreach($nota_envio as $Datos_envio)
{
	$correlativo = $Datos_envio["correlativo"];
	$f = $this->fechas_m->fecha_subdiv($Datos_envio["fecha"]);
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>NOTA DE ENVIO</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
	<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
	<link href="/html/css/extra.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>

<div id="contenedor-pagina">

<div id="encabezado" class='soy_encabezado'><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" width="125" alt="Logo CentralGraphics" /></div>
	<div id="titulo" class='soy_titulo'>
		NOTA DE ENVIO <strong>N&deg; <? echo $correlativo; ?></strong>
		<div>CENTRAL GRAPHICS: <? echo $f["dia"]."-".$f["mes"]."-".$f["anho"]; ?></div>
	</div>



<?
$cliente = '';
$id_cliente = '';
if(count($nota_cliente) != 0)
{
	foreach($nota_cliente as $Datos_cliente)
	{
		$cliente = $Datos_cliente["cliente"];
		$id_cliente = $Datos_cliente["id_cliente"];
	}
}
?>
<div class="informacion">
	<strong>Sr.(es): <?=$cliente?> &nbsp; </strong><br />
Estamos remitiendo lo siguiente:
<?php
$i = 0;

foreach($nota_materiales as $Datos_materiales => $Informacion)
{
	foreach($Informacion as $Datos)
	{
		$nota_mat_ca[] = $Datos["cantidad"];
		$nota_mat_ti[] = $Datos["tipo"];
		$nota_mat_id[] = $Datos["id_material"];
		$nota_mat_ot[] = $Datos["otro_mat"];
		$id_pedido2[] = $Datos['id_pedido'];
	}
}

foreach($especificacion as $Datos_especificacion)
{
		$id_especificacion_general = $Datos_especificacion["id_especificacion_general"];
		$id_pedido = $Datos_especificacion['id_pedido'];
		
?>
	<table class="con_margen" style="width: 100%;">
		<tr>
			<td valign="top" style="width: 150px;">
				<strong><?=$Datos_especificacion["codigo_cliente"]?> - <?=$Datos_especificacion["proceso"]?></strong>
				<input type='hidden' name='oculto[<?=$i?>]' id='oculto<?=$i?>' value='0' />
			</td>
			<td><strong><?=$Datos_especificacion["nombre"]?></strong></td>
		</tr>
		<tr>
			<td><strong><span>Cantidad</span></strong></td>
			<td><strong><span>Descripcci&oacute;n</span></strong></td>
		</tr>
<?php

		if($id_especificacion_general != "")
		{
			foreach($Datos_especificacion['material_recibido'] as $Id_material => $material_rec)
			{
				$a = 0;
				foreach($nota_mat_ti as $index => $tipo)
				{
					if($tipo == "mr" && $nota_mat_id[$index] == $Id_material)
					{
						if($id_pedido2[$a] == $id_pedido)
						{
?>
			<tr>
				<td align="center"><span><?=$nota_mat_ca[$index]?>&nbsp;</span</td>
				<td><span><?=$material_rec?></span></td>
			</tr>
<?php
						}
					}
					$a++;
				}
			}
		}
		
		foreach($Datos_especificacion['material_rec_otro'] as $Id_material_rec => $Material_rec_otro)
		{
			if($Material_rec_otro != "")
			{
				$index = array_search("rp", $nota_mat_ti);
?>
			<tr>
				<td align="center"><span><?=$nota_mat_ca[$index]?>&nbsp;</span></td>
				<td><span><?=$Material_rec_otro?></span></td>
			</tr>
<?php
			}
		}
		
		foreach($Datos_especificacion['material_sol_otro'] as $Id_material_sol_otro => $Material_sol_otro)
		{
			if($Material_sol_otro != "")
			{
				$index = array_search("sp", $nota_mat_ti);
?>
			<tr>
				<td align="center"><span><?=$nota_mat_ca[$index]?>&nbsp;</span></td>
				<td><span><?=$Material_sol_otro?></span><br /></td>
			</tr>
<?php	
			}
		}
		
		if($id_especificacion_general != "")
		{
			
			foreach($Datos_especificacion['material_solicitado'] as $Id_material_sol => $material_sol)
			{
				$a = 0;
				foreach($nota_mat_ti as $index => $tipo)
				{
					if($tipo == "ms" and $nota_mat_id[$index] == $Id_material_sol)
					{
						if($id_pedido2[$a] == $id_pedido)
						{
?>
			<tr>
				<td align="center"><span><?=$nota_mat_ca[$index]?>&nbsp;</span></td>
				<td><span><?=$material_sol?></span></td>
			</tr>	
<?php
						}
					}
					$a++;
				}
			}
		}
		$a = 0;
		foreach($nota_mat_ti as $index => $tipo)
		{
			if($tipo == "ot")
			{
					if($id_pedido2[$a] == $id_pedido)
					{
?>
			<tr>
				<td align="center"><span><?=$nota_mat_ca[$index]?>&nbsp;</span></td>
				<td><span><?=$nota_mat_ot[$index]?></span></td>
			</tr>
<?
				}
			}
			$a++;
		}
?>
		</tr>
	</table>
<?php
	$i++;
	}
?>
		<br />
		<table style="width:95%;" class="credito">
			<tr>
				<td style="text-align:center;">
					<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u><br />
					<strong>ENTREGADO</strong>
					<br /><br />
				</td>
				<td>
					<br /><br /><br />
					<strong style="text-align:left;">NOMBRE:</strong> <u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</u><br /><br />
					<strong style="text-align:left;">FIRMA:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u><br /<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u><br />
					<strong style="text-align:left;">HORA:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u><br /<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u>
				</td>
				<td style="text-align:center;">
					<u> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </u><br />
					<strong>AUTORIZADO</strong>
					<br /><br />
				</td>
			</tr>
		</table>
</div>
</div>
</body>

</html>