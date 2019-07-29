

<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<script type="text/javascript" src="/html/js/detalle.js?n=1"></script>
<script type="text/javascript" src="/html/js/req_sel_add.js?n=1"></script>
<script>
	function reconsumo(id_pedido)
	{
		$('#ruta_finalizar').val('/pedidos/detalle_activo/index/'+id_pedido);
		$('#reportar_material').css('display','block');
	}
</script>

<!--Datos generales-->
<!--table style="width: 660px;">
	<tr><td>Cliente:</td><th colspan="3"><?=$Proceso['nombre']?></th></tr>
</table-->

<!-- ***************** -->
<strong style="font-size:17px;"><?=$Proceso['codigo_cliente'].'-'.$Proceso['proceso']?>:</strong>
&nbsp; <strong><?=$Proceso['nombre_proceso']?></strong>
<br />
<?=$Proceso['nombre']?>


<!--Controles-->
<div id="pedido-controles">
	<strong>Opciones</strong>
	
	<br />
	<a href="javascript:rechazar('<?=$Id_Pedido?>','<?=$Pedido_Usuario['id_peus']?>');" class="iconos irechazar toolder"><span>Rechazar Trabajo</span></a>
<?php
//Iniciar trabajo
if(
	'Asignado' == $Pedido_Usuario['estado']
	&& 'N/A' != $Pedido_Usuario['tiempo_asignado']
	&& 'NaN' != $Pedido_Usuario['tiempo_asignado']
)
{
?>
	<a href="/pedidos/tiempo/accion/iniciar/<?=$Id_Pedido?>/<?=$Pedido_Usuario['id_peus']?>" class="iconos iiniciar toolder"><span>Iniciar Tiempo de Trabajo</span></a>
<?php
}
//Pausar trabajo
if('Procesando' == $Pedido_Usuario['estado'])
{
?>
	<a href="/pedidos/tiempo/accion/pausar/<?=$Id_Pedido?>/<?=$Pedido_Usuario['id_peus']?>" class="iconos ipausar toolder"><span>Pausar Desarrollo</span></a>
<?php
}
//Continuar trabajo
if('Pausado' == $Pedido_Usuario['estado'])
{
?>
	<a href="/pedidos/tiempo/accion/continuar/<?=$Id_Pedido?>/<?=$Pedido_Usuario['id_peus']?>" class="iconos icontinuar toolder"><span>Continuar Desarrollo</span></a>
<?php
}
?>
	<a href="javascript:ver_agregar_scan('<?=$Proceso['id_proceso']?>-<?=$Id_Pedido?>');" class="iconos iscan toolder"><span>Archivos Adjuntos</span></a>
<?php

//Asigno los valores de el id_dpto y el id_grupo
//a variables porque las Utilizare mas adelante.
$id_dpto = $this->session->userdata('id_dpto');

$Mostrar_finalizado = 'si';

		//Verifico que el estado sea distinto de Asignado para poder mostrar el boton de finalizar.
		if(
			$Pedido_Usuario['estado'] != 'Asignado'
			|| 28 == $this->session->userdata('id_dpto')
		)
		{
			$id_hoja = '';
			//Verificamos que la variable $hoja_revision
			//Contenga valores.
			if($hoja_revision != '')
			{
				$id_hoja = $hoja_revision;
			}
			
			
			if($id_hoja == "" and $id_dpto != 17)
			{
				$Mostrar_finalizado = 'no';
				if($id_dpto != 3 and 6 != $id_dpto and 18 != $id_dpto and 19 != $id_dpto )
				{
?>
					<dt><a href="/hojas_revision/nueva_revision/index/<?=$Id_Pedido?>">
					<span class="iconos iruta toolder"></span>&nbsp;&nbsp;Hoja de Revisi&oacute;n</a></dt>
<?php
				}
			}
		}
		if(9 == $id_dpto or 3 == $id_dpto or 18 == $id_dpto or 19 == $id_dpto )
		{
			$Mostrar_finalizado = 'si';
		}
		
		
		if(
			$Mostrar_finalizado != 'no'
			and ($Pedido_Usuario['estado'] != 'Asignado' or $id_dpto == '3')
			or $id_dpto == '11' or $id_dpto == '10' or $id_dpto == '6' or $hoja_revision == 'si'
		)
		{
?>
		<a href="javascript:fin_trabajo('<?=$this->session->userdata('puesto')?>','<?=$Id_Pedido?>','<?=$Pedido_Usuario['id_peus']?>');" class="iconos iterminado toolder"><span>Dar por Terminado</span></a>
		<br />
<?php
		}
		//Esta validacion nos servira para mostrar un salto de linea
		//Este salto de linea lo necesitare cuando no se muestre el
		//la opcion de dar por terminado.
		if($Pedido_Usuario['estado'] == 'Asignado' and $id_dpto != '3' and $id_dpto != '11' and $id_dpto != '10' and $id_dpto != '6')
		{
			echo '<br />';
		}
?>
	
	
	
	<a href="javascript:ventana_externa('/pedidos/especificacion/ver/<?=$Id_Pedido?>/n');" class="iconos iexterna toolder"><span>Abrir en Ventana Externa</span></a>&nbsp;
	<a href="/pedidos/especificacion/ver/<?=$Id_Pedido?>/n">Hoja de Planificaci&oacute;n</a>
	<br />
	<a href="javascript:ventana_externa('/pedidos/historial_pedido/index/<?=$Proceso['id_proceso']?>');" class="iconos iexterna toolder"><span>Abrir en Ventana Externa</span></a>&nbsp;
	<a href="/pedidos/historial_pedido/index/<?=$Proceso['id_proceso']?>">Historial de Pedido</a>
	
	<br />
	<a href="javascript:ventana_externa('/hojas_revision/historial/index/<?=$Id_Pedido?>');" class="iconos iexterna toolder"><span>Abrir en Ventana Externa</span></a>&nbsp;
	<a href="/hojas_revision/historial/index/<?=$Id_Pedido?>">Historial de Revisi&oacute;n</a>
	<br />
	
</div>


<div class="limpiar m10"></div>

<!--Ruta de trabajo-->
<div style="width: 600px;">
<?php
if(is_array($ruta_trabajo))
{
	foreach($ruta_trabajo as $Fila)
	{
		$Estado = $Fila['estado'];
		if('Asignado' == $Fila['estado'])
		{
			$Estado = 'Sin Inicio';
		}
?>
	<span class="toolizq rut_<?=$Fila['estado']?>">
		<?=$Fila['iniciales']?>
		<span>
			Operador: <?=$Fila['usuario']?>.
			<br />
			Estado: <?=$Estado?>.
			<br />
			Finalizado: <?=($Fila['fecha_fin']!='0000-00-00 00:00:00')?date('d-m-Y H:i:s', strtotime($Fila['fecha_fin'])):'00-00-0000 00:00:00'?>.
		</span>
	</span>
<?php
	}
}
?>
</div>


<div class="limpiar m15"></div>


<table style="width: 660px;">
	<tr>
		<td>Asignado: <?=date('d-m-Y H:i:s', strtotime($Pedido_Usuario['fecha_asignado']))?></td>
		<td>Iniciado: <?=('0000-00-00 00:00:00' != $Pedido_Usuario['fecha_inicio'])?date('d-m-Y H:i:s', strtotime($Pedido_Usuario['fecha_inicio'])):'0000-00-00 00:00:00'?></td>
	</tr>
</table>


<!--Tiempos-->
<?php
if(
	'N/A' != $Pedido_Usuario['tiempo_asignado']
	&& 'NaN' != $Pedido_Usuario['tiempo_asignado']
)
{
	$Tiempo_Asignado = $this->fechas_m->minutos_a_hora(
		$Pedido_Usuario['tiempo_asignado']
	);
	
	$Tiempo_Utilizado = $this->fechas_m->minutos_a_hora($Tiempo);
	$Tiempo_Restante = $Pedido_Usuario['tiempo_asignado'] - $Tiempo;
	if(0 > $Tiempo_Restante)
	{
		$Tiempo_Restante = 0;
	}
	$Tiempo_Restante = $this->fechas_m->minutos_a_hora($Tiempo_Restante);
		
	$Pixels = 0;
	if($Pedido_Usuario['tiempo_asignado'] > 0)
	{
		$Pixels = ($Tiempo * 100) / $Pedido_Usuario['tiempo_asignado'];
		$Pixels = ($Pixels * 945) / 100;
		$Pixels = intval($Pixels);
		if(945 < $Pixels)
		{
			$Pixels = 945;
		}
	}
	
?>
	Programado: <strong><?=$Tiempo_Asignado?>h</strong>. &nbsp; &nbsp;
	Utilizado: <strong><?=$Tiempo_Utilizado?>h</strong>. &nbsp; &nbsp;
	Restante: <strong><?=$Tiempo_Restante?>h</strong>.
	<div id="tiempo_utilizado">
		<div class="tinormal" style="width: <?=$Pixels?>px;"></div>
	</div>
<?php
}
?>


<div class="limpiar m10"></div>

<?php
$this->load->view('/observaciones/agregar_obs_v');
?>

&nbsp;

Tipo Observaciones
<select name="cambia_obsrv" id="cambia_obsrv">
	<option value="obsrv_normal">Generales</option>
	<option value="obsrv_resaltada">Indicaciones</option>
</select>



<form action="/pedidos/cotizacion_modif/index/<?=$Id_Pedido?>/activo" method="post" id="cotizacion_det_act" style="display:none;">
<?php $this->load->view('pedidos/cotizacion_v'); ?>
	<input type="button" value="Cancelar" onclick="$('#cotizacion_det_act').hide();" />
	<input type="button" value="Actualizar Cotizaci&oacute;n" onclick="$('#cotizacion_det_act').submit();" />
</form>



&nbsp; <strong><span class="obs_ver_ocu" acc="ver" style="display:none;">[Mostrar Observaciones]</span><span class="obs_ver_ocu" acc="ocu">[ Ocultar Observaciones ]</span></strong>

<br /><strong style="color:#D10000;font-size: 15px;">Nota: Las observaciones se ordenan de la siguiente manera: Las recientes est&aacute;n al principio y las antiguas al final.</strong>
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
			<a href="javascript:cotizacion_ver();">Cotizaci&oacute;n:</a>
			<div id="ver_cotizacion" style="display:none;">
<?php
	/*
	foreach($Cotizacion as $fila)
	{
		$producto = $fila["producto"];
		$precio = $fila["precio"];
		$cantidad = $fila["cantidad"];
		$concepto = $fila["concepto"];
		
		$total_precio = number_format(($cantidad * $precio), 2);
?>
	<?php echo $producto.' - '.$cantidad.' '.$concepto.' [$'.$total_precio.']'?><br />
<?php
	}
	*/
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
<?php
	$Muestra_Editar = ' display:none;';
	if(
		'Plani' == $this->session->userdata('codigo')
		|| 'Sistemas' == $this->session->userdata('codigo')
		|| 'Ventas' == $this->session->userdata('codigo')
		|| 'Gerencia' == $this->session->userdata('codigo')
		|| 'Despacho' == $this->session->userdata('codigo')
	)
	{
		$Muestra_Editar = '';
	}
?>
			<span id="edita_coti_det" style="cursor: pointer;<?=$Muestra_Editar?>">[Editar cotizaci&oacute;n]</span>

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
?>
	<tr>
		<td><?=date('d-m-Y H:i:s', strtotime($fila["fecha_hora"]))?></td>
		<td><?=$fila["usuario"]?></td>
		<td><?=nl2br($fila["observacion"])?></td>
	</tr>
<?php
	}
}
?>
</table>


<table class="tabular obsr_tabla" style="width: 100%;display:none;" id="obsrv_resaltada">
	<tr>
		<th style="width: 157px;">Fecha:</th>
		<th style="width: 125px;">Usuario:</th>
		<th>Observaci&oacute;n:</th>
	</tr>
<?php
$Observ_Indicaciones = false;
if(is_array($Observaciones))
{
	foreach($Observaciones as $fila)
	{
		if('s' != $fila['req_aprobacion'])
		{
			continue;
		}
		$Observ_Indicaciones = true;
?>
	<tr>
		<td><?=date('d-m-Y H:i:s', strtotime($fila["fecha_hora"]))?></td>
		<td><?=$fila["usuario"]?></td>
		<td><?=nl2br($fila["observacion"])?></td>
	</tr>
<?php
	}
}
?>
</table>


<div class="limpiar m15"></div>

<!-- ** -->
<?php
$this->load->view('/pedidos/pedido_consumo_v');
?>

<!--Formularios personalizados-->

<?php
$this->load->view('pedidos/rechazo_v.php');
?>

<?php
$this->load->view('/scan/cargar_scan_v', $num_cajas);
?>

<div id="finalizar_sap" class="sombra" style="display:none;">
<?php
$pedido_sap = '';
$venta = '';
foreach($SAP as $Datos)
{
	$pedido_sap = $Datos['sap'];
	$venta = $Datos['venta'];
	$orden = explode(',', $Datos['orden']);
}	
?>
	<strong>Detalles de Factura</strong>
	&nbsp; &nbsp; &nbsp;
	<a href="javascript:fin_pedido();">[ No Aplica ]</a>
	
	
	<br />
	<table>
		<tr>
			<td><strong>Pedido/Orden:</strong>&nbsp;&nbsp;</td>
			<td><input type="text" name="fs_pedido" id="fs_pedido" value="<?=$pedido_sap?>" /></td>
		</tr>
		<tr>
			<td><strong>Monto: $</strong>&nbsp;&nbsp;</td>
			<td><input type="text" name="fs_venta" id="fs_venta" value="<?=$venta?>" /></td>
		</tr>
	</table>
	
	
	
	<br />
	<input class="boton" type="button" value="Cancelar" onclick="$('#finalizar_sap').hide();" />
	<input class="boton" type="button" value="Guardar" onclick="finalizar_sap('<?=$Id_Pedido?>')" />
	
</div>



<div id="reportar_material" class="sombra reportar_material" style="display:none;">
	
	<strong>Reportar consumo de Material</strong>
	&nbsp; &nbsp; &nbsp;
	<a href="javascript:fin_pedido();">[ No Aplica ]</a>
	
	<br /><br />
	
	<table>
		<tr><th>Material</th><th>Descripci&oacute;n</th><th>Cantidad</th></tr>
<?php
for($i_m_t = 0; $i_m_t < 6; $i_m_t++)
{
	?>
		<tr>
			<td><input class="nombre_material" type="text" size="12" name="codigo_material_<?php echo $i_m_t; ?>" id="codigo_material_<?php echo $i_m_t; ?>" onblur="ver_material('_<?php echo $i_m_t; ?>')" /></td>
			<td>
				<input class="nombre_material" type="text" size="50" name="nombre_material_<?php echo $i_m_t; ?>" id="nombre_material_<?php echo $i_m_t; ?>" value="" readonly="readonly" />
				<input type="hidden" id="id_material_<?php echo $i_m_t; ?>" name="id_material_<?php echo $i_m_t; ?>" value="--" />
			</td>
			<td>
				<input class="nombre_material" type="text" size="12" name="cantidad_material_<?php echo $i_m_t; ?>" id="cantidad_material_<?php echo $i_m_t; ?>" />
				<input type="checkbox" name="repro_material_<?php echo $i_m_t; ?>" id="repro_material_<?php echo $i_m_t; ?>" />
			</td>
		</tr>
<?php
}
?>
	</table>
	
	<input type="button" class="boton" value="Cancelar" onclick="$('#reportar_material').hide();" />
	<input type="button" class="boton" value="Guardar Consumo" onclick="reportar_consumo('<?=$Id_Pedido?>')" id="btn_guar_consu" />	
</div>


<input type="hidden" name="ruta_finalizar" id="ruta_finalizar" value="/pedidos/tiempo/accion/finalizar/<?=$Id_Pedido?>/<?=$Pedido_Usuario['id_peus']?>" />

<div id="check_observaciones" class="sombra" style="display:none;">
	<strong>Confirmar desarrollo</strong>
	<br />
	<span class="italica">Favor confirmar que las indicaciones siguiente fueron aplicadas al desarrollo:</span>
	
	<br />
	<table id="tabla_check_observ" style="width: 700px;" class="tabular"></table>

	<br />
	<input class="boton" type="button" value="Cancelar" onclick="$('#check_observaciones').hide();" />
	<input class="boton" type="button" value="Revisado" onclick="$('#check_observaciones').hide();poner_consumo();" />
	
</div>





<div>
<?php
$Variables['Redireccion'] = 'pedi';
$this->load->view('planchas/lecturas_pedido_v', $Variables);
?>
</div>




<style>
	.nombre_material{
		border: none;
		border-bottom: 1px solid #555555;
	}
	.nombre_material:focus{
		border: none;
		background-color: #fff;
		border-bottom: 1px solid #555555;
	}
	.nombre_material:disabled{
		background: none;
	}
	#reportar_material table{
		line-height: 10px;
	}
	.obs_ver_ocu:hover{
		cursor: pointer;
	}
</style>


<script>
	$(function()
	{
		$('#cambia_obsrv').change(function()
		{
			$('.obsr_tabla').hide();
			$('#'+$(this).val()).show();
		});
	});
	<?=($Observ_Indicaciones)?'cambios_consumo = "camb"; ':''?>
	
	$('#coti_trabajo input[type=checkbox]').each(function()
	{
		var id_chk = $(this).attr('id');
		id_chk = id_chk.split('_');
		poner_color_fila('chmat_'+id_chk[1],'mat_'+id_chk[1]);
	});
	$('#edita_coti_det').click(function()
	{
		$('#cotizacion_det_act').toggle();
	});
	
	
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
	
</script>

