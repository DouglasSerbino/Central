
<br />

<strong>OBSERVACIONES</strong>
&nbsp; &nbsp;

	<input type="checkbox" name="apro" id="apro" checked="checked" />
	<label for="apro">Resaltar Cambios</label>

<br />
<textarea name="observaciones" id="observaciones" style="width: 700px; height: 120px;"></textarea>

<br />
<input type="button" id="btn_submit" value="<?=$Titulo_Pagina?>" onclick="validar_pedido('<?=$Titulo_Pagina?>')" />

</form>


<?php
	$this->load->view('/scan/cargar_scan_v', $num_cajas);
?>


<iframe src="" name="sube_miniatura" style="display:none;"></iframe>

<link rel="stylesheet" href="/html/css/tinyeditor.css" />
<script src="/html/js/tinyeditor.js"></script>
<script type="text/javascript">
	$(function()
	{
		if('Reproceso' == $('[name=tipo_trabajo] option:selected').text()){ $('#id_usu_rechazo').show(); $('#reproceso_razon').show(); }
		$("[name=fecha_entrega]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
		$('[name=tipo_trabajo]').change(function()
		{
			if('Reproceso' == $('[name=tipo_trabajo] option:selected').text())
			{
				$('#id_usu_rechazo').show();
				$('#reproceso_razon').show();
				$('#motivo').show();
			}
			else
			{
				$('#id_usu_rechazo').hide();
				$('#reproceso_razon').hide();
				$('#motivo').hide()
			}
		});
		$('#ruta_trabajo input[type=checkbox]').each(function()
		{
			if($(this).prop('checked'))
			{
				$('.fruta_'+$(this).attr('info')).addClass('flujo_rt_seleccionado');
			}
		});
		
		if($('#cotizacion').attr('checked'))
		{
			$('#coti_trabajo').show();
		}

		$('#btn_sub_scaneos').click(function()
		{
			$('#form_scan').hide();
		});
	});

	new TRA_TE.editor.edit('cuak', {
		id: 'observaciones',
		width: 680,
		height: 225,
		cssclass: 'traba-tiny',
		controlclass: 'traba-tiny-control',
		rowclass: 'traba-tiny-header',
		dividerclass: 'traba-tiny-divider',
		controls: [
			'bold', 'italic', 'underline', '|', 'unorderedlist', 'unformat'
		],
		footer: true,
		xhtml: true,
		cssfile: 'tinyeditor.css',
		bodyid: 'agr_trab_indi_html',
		footerclass: 'traba-tiny-footer',
		resize: {cssclass: 'resize'}
	});
</script>

