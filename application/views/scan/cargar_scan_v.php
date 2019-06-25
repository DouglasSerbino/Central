	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
	<script type="text/javascript" src="/html/js/thickbox.js"></script>
	<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />
<div id="form_scan" style="display: none;">

<div id="scan_contenido" class="sombra">
	
	<form enctype="multipart/form-data" id="formscan" name="formscan" method="post" action="/scan/cargar_scan"<?=(isset($Ruta_Actual)?' target="sube_miniatura"':'')?>>
		<?php
		if(1 == $num_cajas and '' == $Redir)
		{
			?>
			<input type="hidden" name="dia1" id="dia1" size="5" value="<?=$Fechas['dia1']?>" />
			<input type="hidden" name="mes1" id="mes1" size="8" value="<?=$Fechas['mes1']?>" />
			<input type="hidden" name="anho1" id="anho1" size="8" value="<?=$Fechas['anho1']?>" />
			<input type="hidden" name="dia2" id="dia2" size="5" value="<?=$Fechas['dia2']?>" />
			<input type="hidden" name="mes2" id="mes2" size="8" value="<?=$Fechas['mes2']?>" />
			<input type="hidden" name="anho2" id="anho2" size="8" value="<?=$Fechas['anho2']?>" />
			<input type="hidden" name="cliente" id="cliente" size="8" value="<?=$Id_Cliente?>" />
			<input type="hidden" name="puesto" id="puesto" size="8" value="<?=$Puesto?>" />
			<input type="hidden" name="trabajo" id="trabajo" value="<?=$Trabajo?>" />
			<input type="hidden" name="cliente_tipo" id="cliente_tipo"  value="<?=$tipo_cliente?>" />
			<?php
		}
		?>
		<input type="hidden" value="" id="scan_pedido" name="scan_pedido" />
		<input type="hidden" value="<?=$Redir?>" name="scan_redireccion" />
		
		<strong>SELECCIONAR ARCHIVOS</strong>
		<br>
		
		<?/*
			 *Nota para el programador de turno.
			 *Debido a que se han colocado estos tres input files manualmente,
			 *manualmente se ha colocado su validacion en el javascript "acciones".
			 *Si agregas o quitas un input file, debes modificar tambien el js.
			 *Espero haberte sido util en un comentario por lo menos :)
			 */
		?>
		
		<table>
			<tr>
				<td>
<?php
if($num_cajas == 1)
{
?>
					<input type="button" value="Archivo 1" onclick="javascript:document.getElementById('archivo_0').click();">
					<span id="span_archivo_0" class="span_archivo" onclick="javascript:document.getElementById('archivo_0').click();">...</span>
<?php
}
else
{
?>
					<input type="button" value="Archivo 1" onclick="javascript:document.getElementById('archivo_0').click();">
					<span id="span_archivo_0" class="span_archivo" onclick="javascript:document.getElementById('archivo_0').click();">...</span>
					<br>
					<input type="button" value="Archivo 2" onclick="javascript:document.getElementById('archivo_1').click();">
					<span id="span_archivo_1" class="span_archivo" onclick="javascript:document.getElementById('archivo_1').click();">...</span>
					<br>
					<input type="button" value="Archivo 3" onclick="javascript:document.getElementById('archivo_2').click();">
					<span id="span_archivo_2" class="span_archivo" onclick="javascript:document.getElementById('archivo_2').click();">...</span>
<?php
}
?>
				</td>
				<td>
					<input type="button" onclick="cargar_scan(<?=$num_cajas?>)" value="Subir scaneos" class="boton" id="btn_sub_scaneos" />
					<br />
					<input type="button" onclick="ocultar_scan()" value="Cancelar" class="boton" />
				</td>
			</tr>
		</table>

		<div style="height:20px; overflow: hidden;">
<?php
if($num_cajas == 1)
{
?>
	<input type="file" id="archivo_0" name="archivo_0" style="visibility: hidden" />
<?php
}
else
{
?>
			<input type="file" id="archivo_0" name="archivo_0" style="visibility: hidden" />
			<input type="file" id="archivo_1" name="archivo_1" style="visibility: hidden" />
			<input type="file" id="archivo_2" name="archivo_2" style="visibility: hidden" />
<?php
}
?>
		</div>
	</form>
<?php
if($num_cajas != 1)
{
	?>
	<strong>ARCHIVOS ADJUNTOS</strong>
	<br />
	<div id="form_scan_archivos"></div>
<?php
}
?>
</div>
</div>

<script>
	$('#form_scan input[type=file]').bind('change', function()
	{
		cambio_scan($(this).attr('id'));
	});
</script>