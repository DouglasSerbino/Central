<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<style>
	.tabular tr:hover input[type="text"], input[type="textarea"]
	{
		background-color: rgba(254, 230, 177, 0.3);
	}
	.obs_ver_ocu:hover{
		cursor: pointer;
	}
	#para_correito{
		margin: 15px 5px;
		padding: 5px 15px;
		background: #fafafa;
		border: 1px solid #393b57;
		box-shadow: 2px 2px 5px #888888;
	}
</style>


<!-- ***************** -->
<strong style="font-size:17px;"><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?>:</strong>
&nbsp; <strong><?=$Info_Proceso['nombre_proceso']?></strong>
<br />
<?=$Info_Proceso['nombre']?>


<!--Controles-->
<div id="pedido-controles">
	<strong>Opciones</strong>
	<br />
	<a href="javascript:ventana_externa('/pedidos/especificacion/ver/<?=$Id_Pedido?>/n');" class="iconos idocumento toolder"><span>Documentos del Pedido</span></a>
	<a href="javascript:ver_agregar_scan('<?=$Info_Proceso['id_proceso']?>-<?=$Id_Pedido?>');" class="iconos iscan toolder"><span>Archivos Adjuntos</span></a>
	<a href="javascript:ventana_externa('/hojas_revision/historial/index/<?=$Id_Pedido?>');" class="iconos iruta toolder"><span>Historial de Revisi&oacute;n</span></a>

</div>

<div class="limpiar m15"></div>

<table id="tabla_detalle">
	<tr>
		<th>Departamento</th>
		<th>Usuario</th>
		<th>Estado</th>
		<th>Fecha Asignado</th>
		<th>Fecha Terminado</th>
	</tr>
<?php
if(is_array($ruta_trabajo))
{
	foreach($ruta_trabajo as $fila)
	{
		$Clase = '';
		if(
			"Asignado" == $fila["estado"]
			|| "Procesando" == $fila["estado"]
			|| "Pausado" == $fila["estado"]
			|| "Aprobacion" == $fila["estado"]
		)
		{
			$Clase = ' class="selec"';
		}
		
		$f = $this->fechas_m->fecha_subdiv($fila['fecha_asignado']);
		$fila['fecha_asignado'] = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
		$f = $this->fechas_m->fecha_subdiv($fila['fecha_fin']);
		$fila['fecha_fin'] = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
		
		
	?>
	<tr<?=$Clase?>>
		<td>&nbsp;&nbsp;<?=$fila["departamento"]?></td>
		<td><?=$fila["usuario"]?></td>
		<td><?=(isset($Pedidos[$fila['tipo']]))?$Pedidos[$fila['tipo']]:''?></td>
		<td><?=$fila["estado"]?></td>
		<td><?=$fila["fecha_asignado"]?></td>
		<td><?=$fila["fecha_fin"]?></td>
	</tr>
<?php
	}
}
?>
</table>

<br /><strong>OBSERVACIONES</strong>

&nbsp; <strong><span class="obs_ver_ocu" acc="ver" style="display:none;">[Mostrar Observaciones]</span><span class="obs_ver_ocu" acc="ocu">[ Ocultar Observaciones ]</span></strong>


<form action="/pedidos/cotizacion_modif/index/<?=$Id_Pedido?>" method="post" id="cotizacion_ped_det" style="display:none;">
<? $this->load->view('pedidos/cotizacion_v'); ?>
	<input type="button" value="Cancelar" onclick="$('#cotizacion_ped_det').hide();" />
	<input type="button" value="Actualizar Cotizaci&oacute;n" onclick="$('#cotizacion_ped_det').submit();" />
</form>



<table class="tabular obsr_tabla" style="width: 100%;" id="obsrv_normal">
	<tr>
		<th style="width: 157px;">Fecha:</th>
		<th style="width: 125px;">Usuario:</th>
		<th>Observaci&oacute;n:</th>
	</tr>
<?php
if(
	'Plani' == $this->session->userdata('codigo')
	|| 'Sistemas' == $this->session->userdata('codigo')
	|| 'Ventas' == $this->session->userdata('codigo')
	|| 'Despacho' == $this->session->userdata('codigo')
	|| 'Gerencia' == $this->session->userdata('codigo')
)
{
?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
			<a href="javascript:cotizacion_ver();"<?=(0==count($Cotizacion))?' style="display:none;"':''?>>Cotizaci&oacute;n:</a><br />
			<div id="ver_cotizacion" style="display:none;">
<?php
	foreach($Cotizacion as $Producto_v)
	{
		foreach ($Producto_v as $iProducto => $fila)
		{
			if('total' === $iProducto)
			{
				continue;
			}
			$producto = $fila['producto'];
			$precio = $fila['precio'];
			
			$cantidad = $fila['pulgadas'];
			$concepto = '';//$fila['concepto'];
			
			$total_precio = number_format(($cantidad * $precio), 2);
			
			echo $producto.' - '.$cantidad.' '.$concepto.' [$'.$total_precio.']<br />';
		}
	}
	?>
			</div>
<?
	$Muestra_Editar = ' display:none;';
	if(
		'Plani' == $this->session->userdata('codigo')
		|| 'Sistemas' == $this->session->userdata('codigo')
		//|| 'Ventas' == $this->session->userdata('codigo')
		|| 'Gerencia' == $this->session->userdata('codigo')
		|| 'Despacho' == $this->session->userdata('codigo')
	)
	{
?>
			<span id="edita_coti_det" style="cursor: pointer;">[Editar cotizaci&oacute;n]</span>
			
			&nbsp; &nbsp; &nbsp; 
			<span id="enviar_correito" style="cursor: pointer;">[Enviar Cotizaci&oacute;n]</span>

			<div id="para_correito" style="display: none;">
				
				<strong>Enviar Cotizaci&oacute;n por correo</strong>
				
				<br /><br />
				<label>
					<input type="radio" id="para_correito_manual" name="para_correito_radio" checked="checked" />
					Digitar la informaci&oacute;n del Contacto
				</label>
				<br />
				&nbsp; &nbsp; &nbsp; &nbsp; 
				Att.: <input type="text" id="para_correito_att" placeholder="Nombre Contacto" />
				&nbsp; &nbsp; &nbsp; 
				email: <input type="text" id="para_correito_to" placeholder="Correo Contacto" />
				
				<br />
				<label>
					<input type="radio" id="para_correito_automatico" name="para_correito_radio" />
					Seleccionar un Contacto de la lista
				</label>
				<br />
				&nbsp; &nbsp; &nbsp; &nbsp;
				<select id="para_correito_select" disabled="disabled">
					<option value="--">Seleccionar Contacto</option>
<?
		foreach($Info_Cliente['contacto'] as $Cliente)
		{
?>
					<option value="<?=$Cliente['email']?>[.]<?=$Cliente['nombre']?>"><?=$Cliente['nombre']?> [<?=$Cliente['email']?>]</option>
<?
		}
?>
				</select>
				
				<br /><br />
				<input type="button" id="para_correito_enviar" value="Enviar Cotizaci&oacute;n" />
				&nbsp; &nbsp;

				<input type="button" id="para_correito_cerrar" value="Cerrar" />
				
			</div>
<?
	}
?>
		</td>
	</tr>
<?php
}



if(is_array($Observaciones))
{
	foreach($Observaciones as $fila)
	{
		
		if(
			'p' == $fila['req_aprobacion']
			&&
			!('Plani' == $this->session->userdata('codigo')
			|| 'Sistemas' == $this->session->userdata('codigo')
			|| 'SAP' == $this->session->userdata('codigo')
			|| 'Despacho' == $this->session->userdata('codigo'))
		)
		{
			continue;
		}
		
		$f = $this->fechas_m->fecha_subdiv($fila["fecha_hora"]);
		$fecha_hora = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
?>
	<tr>
		<td><?=$fecha_hora?></td>
		<td><?=$fila["usuario"]?></td>
	<?php

	if($this->session->userdata('codigo') == 'Sistemas')
	{
		$sinComas= str_replace(",","¸",html_entity_decode($fila["observacion"]));
		// reemplazamos las nuevas lineas por comas
		$mensaje= str_replace("\n",",",$sinComas);
		// contamos los separadores
		$nuLineas=count(explode(",", $mensaje));
		// Mostramos en pantalla los datos
		$alto = $nuLineas * 20;
	?>
		<td><textarea onblur="obser($(this).val(), <?=$fila["id_usuario"]?>, <?=$Id_Pedido?>, <?=$fila['id_observacion']?>);" style="width: 630px; font-weight: normal; font-size: 12px; border: none; height: <?=$alto?>px;" ><?=$fila["observacion"]?></textarea></td>
	<?php
	}
	else
	{
	?>
		<td><?=nl2br($fila["observacion"])?></td>
	<?php
	}
	?>
	</tr>
<?
	}
}
?>
</table>


<div class="limpiar m15"></div>

<!-- ** -->
<?
if('' == $this->session->userdata('id_cliente'))
{
	$this->load->view('/pedidos/pedido_consumo_v');
}
?>


<?
$this->load->view('/scan/cargar_scan_v', $num_cajas);
?>


<div>
<?php
$Variables['Redireccion'] = 'deta';
$this->load->view('planchas/lecturas_pedido_v', $Variables);
?>
</div>


<script>
	$(function()
	{
		$('#cambia_obsrv').change(function()
		{
			$('.obsr_tabla').hide();
			$('#'+$(this).val()).show();
		});
	});
	
	$('#coti_trabajo input[type=checkbox]').each(function()
	{
		var id_chk = $(this).attr('id');
		id_chk = id_chk.split('_');
		poner_color_fila('chmat_'+id_chk[1],'mat_'+id_chk[1]);
	});
	$('#edita_coti_det').click(function()
	{
		$('#cotizacion_ped_det').toggle();
	});
	
	function guardar(cantidad, id_inventario, id_pedido)
	{
		window.location = '/herramientas_sis/modificar_consumo/modificar/'+cantidad+"/"+id_inventario+"/"+id_pedido;
	}
	
	function modificar(estado, id_inventario, id_pedido)
	{
		estado = estado.trim();
		if(window.confirm("Desea realizar el cambio?"))
		{
			window.location = '/herramientas_sis/modificar_consumo/cambio_estado/'+estado+"/"+id_inventario+"/"+id_pedido;
		}
	}
	
	function obser(observacion, id_usuario, id_pedido, id_observacion)
	{
		if(window.confirm("Desea realizar el cambio?"))
		{
			$.ajax(
			{
				type: "POST",
				url: "/herramientas_sis/modificar_observaciones/modificar/",
				data: "observacion="+observacion+"&id_usuario="+id_usuario+"&id_pedido="+id_pedido+"&id_observacion="+id_observacion,
				success: function(msg)
				{
					window.location = '/pedidos/pedido_detalle/index/'+id_pedido;
				},
				error: function(msg)
				{
					alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
				}
			});
		}
	}
	
	
	
	function medi_plancha(pedido, redir)
	{
		alert('hola: '+pedido+' = '+redir);
	}
	
	
	$('.obs_ver_ocu').click(function()
	{
		if('ver' == $(this).attr('acc'))
		{
			$('.obsr_tabla').show();
		}
		else
		{
			$('.obsr_tabla').hide();
		}
		
		$('.obs_ver_ocu').show();
		$(this).hide();
	});

	$('#enviar_correito').click(function()
	{
		$('#para_correito').show('blind');
	});

	$('#para_correito_cerrar').click(function()
	{
		$('#para_correito').hide('blind');
	});


	$('[name="para_correito_radio"]').click(function()
	{
		if('para_correito_manual' == $(this).attr('id'))
		{
			$('#para_correito_to').attr('disabled', false);
			$('#para_correito_att').attr('disabled', false);
			$('#para_correito_select').attr('disabled', true);
		}
		if('para_correito_automatico' == $(this).attr('id'))
		{
			$('#para_correito_to').attr('disabled', true);
			$('#para_correito_att').attr('disabled', true);
			$('#para_correito_select').attr('disabled', false);
		}
	});


	$('#para_correito_enviar').click(function(){

		var BotonitoMail = $(this);
		BotonitoMail.val('Procesando...');

		var Para = $('#para_correito_to').val();
		var Atento = $('#para_correito_att').val();

		if('para_correito_manual' == $('[name="para_correito_radio"]:checked').attr('id'))
		{
			if('' == Para || '' == Atento)
			{
				alert('Debe digitar la informacion del Contacto');
				BotonitoMail.val('Enviar Cotizacion');
				return false;
			}
		}
		
		if('para_correito_automatico' == $('[name="para_correito_radio"]:checked').attr('id'))
		{
			var Info_Contacto = $('#para_correito_select').val();
			if('--' == Info_Contacto)
			{
				alert('Debe seleccionar un contacto de la lista');
				BotonitoMail.val('Enviar Cotizacion');
				return false;
			}

			Info_Contacto = Info_Contacto.split('[.]');
			Para = Info_Contacto[0];
			Atento = Info_Contacto[1];
		}

		

		
		$.ajax(
		{
			type: "POST",
			url: "/pedidos/enviar_coti/index/<?=$Id_Pedido?>",
			data: "para="+Para+"&atento="+Atento,
			success: function(msg)
			{
				BotonitoMail.val('Enviar Cotizacion');
				if('ok' == msg)
				{
					alert('Mail enviado exitosamente');
					$('#para_correito').hide('blind');
				}
				else
				{
					alert(msg);
				}
			},
			error: function(msg)
			{
				BotonitoMail.val('Enviar Cotizacion');
				alert('Lo sentimos. Ocurri\xf3 un error en la acci\xf3n solicitada.');
			}
		});
	});
</script>

