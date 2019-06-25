<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<script type="text/javascript" src="/html/js/procesos.js?n=1"></script>

<form name="form_espec_repro" id="form_espec_repro" action="/ventas/preingreso/ingresar" method="post">
<input type="hidden" id="cliente" name="cliente" value="<?=$this->session->userdata('id_cliente')?>" />
<input type="hidden" id="codigo_cliente" name="codigo_cliente" value="<?=$this->session->userdata('codigo_cliente')?>" />

<!-- ***************** -->
<table>
	<tr>
		<td>Proceso:</td>
		<th colspan="3"><?=$this->session->userdata('codigo_cliente')?> - <input type="text" name="proceso" id="proceso" value="" onblur="verifica_proceso()" />
			<input type="button" onclick="genera_correlativo()" value="Generar" class="boton" />
		</th>
	</tr>
	<tr>
		<td>Producto:</td>
		<td colspan="3"><input type="text" name="producto" id="producto" value="" size="50" /></td>
	</tr>
	<tr class="no_imprime">
		<td>Fecha Ingreso:</td>
		<th><?=date('d-m-Y')?></th>
    <td>Fecha Solicitado:</td>
		<td><input type="text" readonly="readonly" name="fecha_entrega" id="fecha_entrega" size="12" value="" /></td>
	</tr>
</table>


<div class="ui-widget" id="nuevo_proc" style="display: none;">
	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
		Este proceso es nuevo.<br />Favor digitar Nombre del Producto.</p>
	</div>
</div>

<div class="ui-widget" id="proceso_proc" style="display: none;">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		No se puede Crear un Pedido Nuevo.<br />Este Proceso tiene una Ruta sin finalizar.</p>
	</div>
</div>

<br class="no_imprime" />



<div id="lista_especificaciones">
<!-- ***************** -->
<strong>Tipo de trabajo:</strong>
<select name="tipo_trabajo">
<?
foreach($Tipos_Trabajo as $Tipo)
{
?>
	<option value="<?=$Tipo['id_tipo_trabajo']?>"><?=$Tipo['trabajo']?></option>
<?
}
?>
</select>



&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 

<!-- ***************** -->
<strong>Tipo de Impresi&oacute;n:</strong>
<?
foreach($Tipos_Impresion as $Tipo)
{
?>
<input type="radio" name="id_tipo_impresion" id="iti_<?=$Tipo['id_tipo_impresion']?>" value="<?=$Tipo['id_tipo_impresion']?>"<?
if($Tipo['id_tipo_impresion'] == 2)
{
?> checked="checked"<?
}
?> />
<label for="iti_<?=$Tipo['id_tipo_impresion']?>"><?=$Tipo['tipo_impresion']?></label>
<?
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
<?
for($i = 1; $i <= 10; $i++)
{
?>
	<tr>
		<td><?=$i?></td>
		<td><input type="text" size="10" name="color_<?=$i?>" id="color_<?=$i?>" onblur="poner_angulos('<?=$i?>')" /></td>
		<td><input type="checkbox" name="solicitado_<?=$i?>" id="solicitado_<?=$i?>" info="<?=$i?>" /></td>
		<td><input type="text" size="4" name="angulo_<?=$i?>" id="angulo_<?=$i?>" /></td>
		<td><input type="text" size="4" name="lineaje_<?=$i?>" id="lineaje_<?=$i?>" /></td>
		<td><input type="text" size="4" name="anilox_<?=$i?>" id="resolucion_<?=$i?>" /></td>
		<td><input type="text" size="4" name="bcm_<?=$i?>" id="resolucion_<?=$i?>" /></td>
		<td><input type="text" size="4" name="sticky_<?=$i?>" id="resolucion_<?=$i?>" /></td>
	</tr>
<?
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
<?
foreach($Maquinas as $Maquina)
{
?>
				<option value="<?=$Maquina?>"><?=$Maquina?></option>
<?
}
?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">Lado a Imprimir</td>
	</tr>
	<tr>
		<td colspan="2"><input type="text" size="7" name="lado_impresion" id="lado_impresion" /></td>
	</tr>
	<tr>
		<th colspan="2">Embob. Arte</th>
	</tr>
	<tr>
		<td>Cara:</td>
		<td><input type="text" name="embobinado_cara" id="embobinado_cara" size="4" /></td>
	</tr>
	<tr>
		<td>Dorso:</td>
		<td><input type="text" name="embobinado_dorso" id="embobinado_dorso" size="4" /></td>
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
		<td><input type="text" name="repet_ancho" id="repet_ancho" size="4" /></td>
		<td>De</td>
		<td><input type="text" name="ancho_arte" id="ancho_arte" size="4" /></td>
	</tr>
	<tr>
		<td>Circun</td>
		<td><input type="text" name="repet_alto" id="repet_alto" size="4" /></td>
		<td>De</td>
		<td><input type="text" name="alto_arte" id="alto_arte" size="4" /></td>
	</tr>
	<tr>
		<th colspan="4">Medidas Totales (mm)</th>
	</tr>
	<tr>
		<td>Ancho</td>
		<td><input type="text" name="ancho_total" id="ancho_total" size="4" readonly="readonly" /></td>
		<td>Circun</td>
		<td><input type="text" name="alto_total" id="alto_total" size="4" readonly="readonly" /></td>
	</tr>
	<tr style="display:none;">
		<td>Exceso (in)</td>
		<td><input type="text" size="5" name="codb_bwr" id="codb_bwr" placeholder="p/ lado" /></td>
		<td>in2</td>
		<td><input type="text" size="7" name="codb_magni" id="codb_magni" readonly="readonly" /></td>
	</tr>
	<tr style="display:none;">
		<th colspan="4">Montaje Segmentado (Opcional)</th>
	</tr>
	<tr style="display:none;">
		<td>Desface</td>
		<td colspan="3"><input type="text" size="5" name="codb_tipo" id="codb_tipo" /></td>
	</tr>
	<tr>
		<th colspan="4">Fotocelda</th>
	</tr>
	<tr>
		<td>Tama&ntilde;o</td>
		<td>
			<input type="text" name="alto_fotocelda" id="alto_fotocelda" size="2" />
			x
			<input type="text" name="ancho_fotocelda" id="ancho_fotocelda" size="2" />
		</td>
		<td>Color</td>
		<td><input type="text" name="color_fotocelda" id="color_fotocelda" size="8" /></td>
	</tr>
	<tr>
		<th colspan="4">
			C&oacute;digo de Barras
			<input type="text" size="19" name="codb_num" id="codb_num" />
		</th>
	</tr>
</table>
<!-- FIN INFO MANGA -->


<br style="clear:both;" />


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
<?
$Polimero = array(
	'0.045', '0.067', '0.090',
	'0.100', '0.107', '0.112',
	'0.115', '0.155', '0.250'
);
foreach($Polimero as $index => $Valor)
{
?>
				<option value="<?=$Valor?>"><?=$Valor?></option>
<?
}
?>
			</select>
		</td>
		<td>
			StickyBack:
			<select name="stickyback" id="stickyback" onchange="calcular_distorsion()">
				<option value="">StickyBack</option>
<?
$Stickyback = array('0.015', '0.020', '0.0177', '0.250', '0.60');
foreach($Stickyback as $index => $Valor)
{
?>
				<option value="<?=$Valor?>"><?=$Valor?></option>
<?
}
?>
			</select>
		</td>
		<td>
			Distorsi&oacute;n
			<input type="text" name="dp" id="dp" size="6" />
		</td>
	</tr>
</table>

<input type="hidden" name="k" id="k" />
<input type="hidden" name="pb" id="pb" />
<input type="hidden" name="pa" id="pa" />
<input type="hidden" name="dn" id="dn" />
<!-- FIN DISTORSION -->




<br style="clear:both;" />



</div>

<div class="imprime_al_lado">
	<? $this->load->view('pedidos/cotizacion_v'); ?>

	<br class="no_imprime" />
</div>

<div class="imprime_al_lado">
	<strong>Observaciones</strong>
	<br />
	<textarea name="observaciones" id="observaciones" rows="4" cols="60"></textarea>
</div>



<br class="no_imprime" /><br class="no_imprime" />

<input type="button" value="Agregar Pre-Ingreso" id="agr_pre_ingreso" disabled="disabled" onclick="guardar_preingreso()" />

</form>


<style type="text/css">
	.ta100td25{
		width: 100%;
	}
	.ta100td25 td{
		width: 25%;
	}
	#nuevo_proc, #proceso_proc{
		position: absolute;
		top: 200px;
		margin-left: 500px;
	}
</style>


<script type="text/javascript">
	
	$('#codigo_cliente').focus();
	
	$("[name=fecha_entrega]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
	
	
	function enviar_espec()
	{
		if(confirm('La informaci\xf3n ser\xe1 modificada.\r\nDesea continuar?'))
		{
			$('#form_espec_repro').submit();
		}
	}
	
	//buscar_cotizacion();
	
</script>

<script type="text/javascript" src="/html/js/hojas_plani.001.js?n=1"></script>
<script>
	var IClie = <?=$this->session->userdata('id_cliente')?>;
</script>
