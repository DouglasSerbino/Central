<style>
	.normal th, .normal td {
  border: 1px solid #000;
}
</style>

<!-- ***************** -->
<strong style="font-size:17px;"><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?>:</strong>
&nbsp; <strong><?=$Info_Proceso['nombre_proceso']?></strong>
<br />
<?=$Info_Proceso['nombre']?>

<br /><br />

<form name="form_espec_repro" id="form_espec_repro" action="/pedidos/especificacion/modificar/<?=$Id_Pedido?>/<?=$tipo?>" method="post">




<!-- ***************** -->
<strong>Tipo de Impresi&oacute;n:</strong>
<?php
if(0 == $Especs['general']['id_tipo_impresion'])
{
	$Especs['general']['id_tipo_impresion'] = 1;
}
foreach($Tipos_Impresion as $Tipo)
{
?>
<input type="radio" name="id_tipo_impresion" id="iti_<?=$Tipo['id_tipo_impresion']?>" value="<?=$Tipo['id_tipo_impresion']?>"<?php
if(
	$Tipo['id_tipo_impresion'] == $Especs['general']['id_tipo_impresion']
)
{
?> checked="checked"<?php
}
?> />
<label for="iti_<?=$Tipo['id_tipo_impresion']?>"><?=$Tipo['tipo_impresion']?></label>
<?php
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
?>
&nbsp; &nbsp; <input type="checkbox" name="mat_solicitado_<?=$Material['id_material_solicitado_grupo']?>" id="mat_solicitado_<?=$Material['id_material_solicitado_grupo']?>"<?php
	if(isset($Especs['matsolgru'][$Material['id_material_solicitado_grupo']]))
	{
?> checked="checked"<?php
	}
?> />
		<label for="mat_solicitado_<?=$Material['id_material_solicitado_grupo']?>"><?=$Material['material_solicitado']?></label>
<?php
}
?>



<br /><br />


<!-- INICIO COLORES -->
<table id="list_colores" class="plani_colores plani_tablas" style="float:left;">
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
		<td><input type="text" size="10" name="color_<?=$i?>" id="color_<?=$i?>" value="<?=$Color?>" onblur="poner_angulos('<?=$i?>')" /></td>
		<td><input type="checkbox" name="solicitado_<?=$i?>" id="solicitado_<?=$i?>"<?=('on'==$Solicitado)?' checked="checked"':''?> info="<?=$i?>" /></td>
		<td><input type="text" size="4" name="angulo_<?=$i?>" id="angulo_<?=$i?>" value="<?=$Angulo?>" /></td>
		<td><input type="text" size="4" name="lineaje_<?=$i?>" id="lineaje_<?=$i?>" value="<?=$Lineaje?>" /></td>
		<td><input type="text" size="4" name="anilox_<?=$i?>" id="resolucion_<?=$i?>" value="<?=$Anilox?>" /></td>
		<td><input type="text" size="4" name="bcm_<?=$i?>" id="resolucion_<?=$i?>" value="<?=$BCM?>" /></td>
		<td><input type="text" size="4" name="sticky_<?=$i?>" id="resolucion_<?=$i?>" value="<?=$Sticky?>" /></td>
	</tr>
<?php
}
?>
</table>
<!-- FIN COLORES -->



<!-- INICIO IMPRESION -->
<table style="float:left;" class="plani_maquina plani_tablas">
	<tr>
		<th colspan="2">Info Impresi&oacute;n</th>
	</tr>
	<tr>
		<td colspan="2">M&aacute;q Impresora</td>
	</tr>
	<tr>
		<td colspan="2">
			<select name="maquina">
				<option value="">--</option>
<?php
foreach($Maquinas as $Maquina)
{
?>
				<option value="<?=$Maquina?>"<?=($Maquina==$Especs['general']['maquina'])?' selected="selected"':''?>><?=$Maquina?></option>
<?php
}
?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">Lado a Imprimir</td>
	</tr>
	<tr>
		<td colspan="2"><input type="text" size="10" name="lado_impresion" value="<?=$Especs['general']['lado_impresion']?>" placeholder="[Cara|Dorso]" /></td>
	</tr>
	<tr>
		<th colspan="2">Embob. Arte</th>
	</tr>
	<tr>
		<td>Cara:</td>
		<td><input type="text" name="embobinado_cara" value="<?=$Especs['general']['embobinado_cara']?>" size="4" placeholder="[1-8]" /></td>
	</tr>
	<tr>
		<td>Dorso:</td>
		<td><input type="text" name="embobinado_dorso" value="<?=$Especs['general']['embobinado_dorso']?>" size="4" placeholder="[1-8]" /></td>
	</tr>
</table>
<!-- FIN IMPRESION -->


<!-- INICIO INFO MANGA -->
<table style="float:left;" class="plani_manga plani_tablas">
	<tr>
		<th colspan="4">Repeticiones (mm)</th>
	</tr>
	<tr>
		<td>Ancho</td>
		<td><input type="text" name="repet_ancho" id="repet_ancho" value="<?=$Especs['general']['repet_ancho']?>" size="4" placeholder="Rep." /></td>
		<td>De</td>
		<td><input type="text" name="ancho_arte" id="ancho_arte" value="<?=$Especs['general']['ancho_arte']?>" size="6" placeholder="mm" /></td>
	</tr>
	<tr>
		<td>Circun</td>
		<td><input type="text" name="repet_alto" id="repet_alto" value="<?=$Especs['general']['repet_alto']?>" size="4" placeholder="Rep." /></td>
		<td>De</td>
		<td><input type="text" name="alto_arte" id="alto_arte" value="<?=$Especs['general']['alto_arte']?>" size="6" placeholder="mm" /></td>
	</tr>
	<tr>
		<th colspan="4">Medidas Totales (mm)</th>
	</tr>
	<tr>
		<td>Ancho</td>
		<td><input type="text" name="ancho_total" id="ancho_total" value="<?=$Especs['general']['ancho_total']?>" size="4" readonly="readonly" /></td>
		<td>Circun</td>
		<td><input type="text" name="alto_total" id="alto_total" value="<?=$Especs['general']['alto_total']?>" size="4" readonly="readonly" /></td>
	</tr>
	<tr>
		<td>Exceso (in)</td>
		<td><input type="text" size="5" name="codb_bwr" id="codb_bwr" value="<?=$Especs['general']['codb_bwr']?>" placeholder="p/ lado" /></td>
		<td>in2</td>
		<td><input type="text" size="7" name="codb_magni" id="codb_magni" value="<?=$Especs['general']['codb_magni']?>" readonly="readonly" /></td>
	</tr>
	<tr>
		<th colspan="4">Montaje Segmentado (Opcional)</th>
	</tr>
	<tr>
		<td>Desface</td>
		<td colspan="3"><input type="text" size="5" name="codb_tipo" id="codb_tipo" value="<?=$Especs['general']['codb_tipo']?>" /></td>
	</tr>
	<tr>
		<th colspan="4">Fotocelda</th>
	</tr>
	<tr>
		<td>Tama&ntilde;o</td>
		<td>
			<input type="text" name="alto_fotocelda" id="alto_fotocelda" value="<?=$Especs['general']['alto_fotocelda']?>" size="2" />
			x
			<input type="text" name="ancho_fotocelda" id="ancho_fotocelda" value="<?=$Especs['general']['ancho_fotocelda']?>" size="2" />
		</td>
		<td>Color</td>
		<td><input type="text" name="color_fotocelda" id="color_fotocelda" value="<?=$Especs['general']['color_fotocelda']?>" size="8" /></td>
	</tr>
	<tr>
		<td colspan="4">
			C&oacute;digo de Barras
			<input type="text" size="19" name="codb_num" id="codb_num" value="<?=$Especs['general']['codb_num']?>" />
		</td>
	</tr>
</table>
<!-- FIN INFO MANGA -->


<br style="clear:both;" /><br />


<!-- INICIO DISTORSION -->
<table class="plani_distorsion plani_tablas">
	<tr>
		<th colspan="3">Distorsi&oacute;n</th>
	</tr>
	<tr>
		<td>
			Altura:
			<select name="polimero" id="polimero" onchange="calcular_distorsion()">
				<option value="">Polimero</option>
<?php
$Polimero = array(
	'0.045', '0.067', '0.090',
	'0.100', '0.107', '0.112',
	'0.115', '0.155', '0.250'
);
foreach($Polimero as $index => $Valor)
{
?>
				<option value="<?=$Valor?>"<?=($Valor==$Especs['distorsion']['polimero'])?' selected="selected"':''?>><?=$Valor?></option>
<?php
}
?>
			</select>
		</td>
		<td>
			StickyBack:
			<select name="stickyback" id="stickyback" onchange="calcular_distorsion()">
				<option value="">StickyBack</option>
<?php
$Stickyback = array('0.015', '0.020', '0.0177', '0.250', '0.60');
foreach($Stickyback as $index => $Valor)
{
?>
				<option value="<?=$Valor?>"<?=($Valor==$Especs['distorsion']['stickyback'])?' selected="selected"':''?>><?=$Valor?></option>
<?php
}
?>
			</select>
		</td>
		<td>
			Distorsi&oacute;n
			<input type="text" name="dp" id="dp" size="6" value="<?=$Especs['distorsion']['dp']?>" />
		</td>
	</tr>
</table>

<input type="hidden" name="k" id="k" value="<?=$Especs['distorsion']['k']?>" />
<input type="hidden" name="pb" id="pb" value="<?=$Especs['distorsion']['pb']?>" />
<input type="hidden" name="pa" id="pa" value="<?=$Especs['distorsion']['pa']?>" />
<input type="hidden" name="dn" id="dn" value="<?=$Especs['distorsion']['dn']?>" />
<!-- FIN DISTORSION -->




<br style="clear:both;" />


<input type="button" value="Guardar Planificaci&oacute;n" onclick="enviar_espec()" />


</form>


<style type="text/css">
	.ta100td25{
		width: 100%;
	}
	.ta100td25 td{
		width: 25%;
	}
</style>


<script type="text/javascript" src="/html/js/hojas_plani.001.js?n=1"></script>