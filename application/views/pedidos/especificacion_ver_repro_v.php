


<!-- ***************** -->
<strong>Tipo de Impresi&oacute;n:</strong> &nbsp;
<?php
	if(0 == $Especs['general']['id_tipo_impresion'])
	{
		$Especs['general']['id_tipo_impresion'] = 1;
	}
	foreach($Tipos_Impresion as $Tipo)
	{
		if(
			$Tipo['id_tipo_impresion'] == $Especs['general']['id_tipo_impresion']
		)
		{
?>
<strong><?=$Tipo['tipo_impresion']?></strong>
<?php
			break;
		}
	}
?>




<br />

<!-- ***************** -->
<strong>Material Solicitado</strong>

<?php
foreach($Mat_Solicitado as $Material)
{
	if('&nbsp;' == $Material['material_solicitado'])
	{
		continue;
	}
	
	if(isset($Especs['matsolgru'][$Material['id_material_solicitado_grupo']]))
	{
?>
&nbsp; &nbsp; <strong>&raquo;</strong><?=$Material['material_solicitado']?>
<?php
	}
}
?>


<br /><br />

<table class="plani_colores plani_tablas" style="float:left;">
	<tr>
		<th>&nbsp;</th>
		<th>Colores</th>
		<th><span class="toolizq">S<span>Color Solicitado</span></span></th>
		<th>&Aacute;ngulo</th>
		<th>Lineaje</th>
		<th>Anilox</th>
		<th>BCM</th>
		<th>Sticky</th>
	</tr>
<?php
for($i = 1; $i <= 10; $i++)
{
	$Color = '';
	$Solicitado = '';
	$Angulo = '';
	$Lineaje = '';
	$Anilox = '';
	$BCM = '';
	$Sticky = '';
	if(isset($Especs['colores'][$i]['color']))
	{
		$Color = $Especs['colores'][$i]['color'];
		$Solicitado = $Especs['colores'][$i]['solicitado'];
		$Angulo = $Especs['colores'][$i]['angulo'];
		$Lineaje = $Especs['colores'][$i]['lineaje'];
		$Anilox = $Especs['colores'][$i]['anilox'];
		$BCM = $Especs['colores'][$i]['bcm'];
		$Sticky = $Especs['colores'][$i]['sticky'];
	}
?>
	<tr>
		<td><?=$i?></td>
		<td><?=$Color?></td>
		<td><input type="checkbox" name="solicitado_<?=$i?>" id="solicitado_<?=$i?>"<?=('on'==$Solicitado)?' checked="checked"':''?> onclick="pintar_caja('<?=$i?>')" disabled="disabled" /></td>
		<td><?=$Angulo?></td>
		<td><?=$Lineaje?></td>
		<td><?=$Anilox?></td>
		<td><?=$BCM?></td>
		<td><?=$Sticky?></td>
	</tr>
<?php
}
?>
</table>



<table style="float:left;" class="plani_maquina plani_tablas">
	<tr>
		<th>Info Impresi&oacute;n</th>
	</tr>
	<tr>
		<td>M&aacute;q Impresora</td>
	</tr>
	<tr>
		<td><?=$Especs['general']['maquina']?></td>
	</tr>
	<tr>
		<td>Lado a Imprimir</td>
	</tr>
	<tr>
		<td><?=$Especs['general']['lado_impresion']?></td>
	</tr>
	<tr>
		<th>Embob. Arte</th>
	</tr>
	<tr>
		<td>Cara: <?=$Especs['general']['embobinado_cara']?></td>
	</tr>
	<tr>
		<td>Dorso: <?=$Especs['general']['embobinado_dorso']?></td>
	</tr>
</table>



<table style="float:left;" class="plani_manga plani_tablas">
	<tr>
		<th colspan="4">Repeticiones (mm)</th>
	</tr>
	<tr>
		<td>Ancho</td>
		<td><?=$Especs['general']['repet_ancho']?></td>
		<td>De</td>
		<td><?=$Especs['general']['ancho_arte']?></td>
	</tr>
	<tr>
		<td>Circun</td>
		<td><?=$Especs['general']['repet_alto']?></td>
		<td>De</td>
		<td><?=$Especs['general']['alto_arte']?></td>
	</tr>
	<tr>
		<th colspan="4">Medidas Totales (mm)</th>
	</tr>
	<tr>
		<td>Ancho</td>
		<td><?=$Especs['general']['ancho_total']?></td>
		<td>Circun</td>
		<td><?=$Especs['general']['alto_total']?></td>
	</tr>
	<tr>
		<th colspan="4">Montaje Segmentado (Opcional)</th>
	</tr>
	<tr>
		<td>Desface</td>
		<td><?=$Especs['general']['codb_tipo']?></td>
		<td>in2</td>
		<td><?=$Especs['general']['codb_magni']?></td>
	</tr>
	<tr>
		<th colspan="4">Fotocelda</th>
	</tr>
	<tr>
		<td>Tama&ntilde;o</td>
		<td>
			<?=$Especs['general']['alto_fotocelda']?>
			x
			<?=$Especs['general']['ancho_fotocelda']?>
		</td>
		<td>Color</td>
		<td><?=$Especs['general']['color_fotocelda']?></td>
	</tr>
	<tr>
		<td colspan="4">
			C&oacute;digo de Barras
			<?=$Especs['general']['codb_num']?>
		</td>
	</tr>
</table>


<br style="clear:both;" /><br />

<table class="plani_distorsion plani_tablas">
	<tr>
		<th colspan="3">Distorsi&oacute;n</th>
	</tr>
	<tr>
		<td>
			Altura: <?=$Especs['distorsion']['polimero']?>
		</td>
		<td>
			StickyBack: <?=$Especs['distorsion']['stickyback']?>
		</td>
		<td>
			Distorsi&oacute;n
			<?=$Especs['distorsion']['dp']?>
		</td>
	</tr>
</table>




<br style="clear:both;" />


<style type="text/css">
	.ta100td25{
		width: 100%;
	}
	.ta100td25 td{
		width: 25%;
	}
</style>
