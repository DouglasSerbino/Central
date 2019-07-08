<style>
	#div_elim_proceso{
		width: 75%;
		margin: auto;
		display: none;
		font-size: 14px;
		margin-top: 15px;
		padding: 5px 15px;
		line-height: 25px;
		background: #FFEFEF;
		border: 1px solid #5B0000;
		box-shadow: 3px 3px 5px 3px #D8ABAB;
	}
	#div_elim_proceso span{
		color: #9E0000;
		font-size: 20px;
		font-weight: bold;
	}
	#div_elim_proceso li{
		margin-left: 20px;
		list-style-type: disc;
	}
</style>


<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>

<table>
	<!--tr><td>Proceso:</td><th><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></th></tr>
	<tr><td>Cliente:</td><th><?=$Info_Proceso['nombre']?></th></tr>
	<tr><td>Producto:</td><th><?=$Info_Proceso['nombre_proceso']?></th></tr-->
	<tr>
		<td><?php
if('' == $Miniatura)
{
	echo '&nbsp;';
}
else
{
?><img style="width: 85px;height: 85px;" src="<?=$Miniatura?>" /><?php
}
?></td>
		<td>
			<strong style="font-size:19px;"><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></strong>
			&nbsp; <strong><?=$Info_Proceso['nombre_proceso']?></strong>
			<br />
			<?=$Info_Proceso['nombre']?>
<?php
if(
	'Gerencia' == $this->session->userdata('codigo')
	|| 'Sistemas' == $this->session->userdata('codigo')
	|| '1' === $this->session->userdata('id_usuario')
)
{
?>
			<br /><input type="button" value="Eliminar Proceso" id="elimina_proceso" />
<?php
}
?>
		</td>
	</tr>
</table>



<?php
if(
	'Gerencia' == $this->session->userdata('codigo')
	|| 'Sistemas' == $this->session->userdata('codigo')
	|| '1' === $this->session->userdata('id_usuario')
)
{
?>
<div id="div_elim_proceso">
	<span>&iexcl;Atenci&oacute;n!</span>
	<br />
	Est&aacute; a punto de Eliminar un proceso completo y todo el historial que &eacute;l posee:
	<br />
	<ol>
		<li>Tiempos por operador</li>
		<li>Materia Prima notificada</li>
		<li>Cotizaciones</li>
		<li>Observaciones</li>
		<li>Etc.</li>
	</ol>
	<br />
	<input type="button" value="Eliminar Proceso" class="proceder" />
	<input type="button" value="&raquo; CANCELAR &laquo;" class="cancelar" />
</div>
<?php
}
?>




<br />

<table class="tabular" style="width: 100%;">
	<tr>
		<th>Fecha Ingreso</th>
		<th>Fecha Estimada</th>
		<th>Fecha Entregado</th>
		<th>&nbsp;</th>
		<th style="width: 150px;">Opciones</th>
	</tr>
<?php
$Modifica_Plani = true;
$mostrar_icono_revivir = 'si';
if(0 < count($Pedidos))
{
	//Recorremos el array principal para extraer todas las fechas de entrega real.
	//Ya que si hay un pedido vivo no se puede mostrar la opcion de revivir en los demas pedidos.
	//Para eso servira este array, ayudara a controlar que se vea o no la opcion de revivir.
	foreach($Pedidos['info'] as $fechas_ver)
	{
		$fec[$fechas_ver['id_pedido']] = $fechas_ver['fecha_reale'];
	}
	

	foreach($Pedidos['info'] as $Info_pedido)
	{
		$fecha_entrada = explode('-', $Info_pedido['fecha_entrada']);
		$fecha_entrega = explode('-', $Info_pedido['fecha_entrega']);
		$fecha_reale = explode('-', $Info_pedido['fecha_reale']);
		
		if($Modifica_Plani && '0000-00-00' == $Info_pedido['fecha_reale'])
		{
			$Modifica_Plani = false;
		}
		
?>
	<tr>
		<td><?=$fecha_entrada[2].'-'.$fecha_entrada[1].'-'.$fecha_entrada[0]?></td>
		<td><?=$fecha_entrega[2].'-'.$fecha_entrega[1].'-'.$fecha_entrega[0]?></td>
		<td><?=$fecha_reale[2].'-'.$fecha_reale[1].'-'.$fecha_reale[0]?></td>
		<td>
			<strong><a href="/pedidos/pedido_detalle/index/<?=$Info_pedido['id_pedido']?>" title="Detalle de Pedido">Detalle</a></strong>
		</td>
		<td>
<?php
		if(
			'Gerencia' == $this->session->userdata('codigo')
			|| 'Plani' == $this->session->userdata('codigo')
			|| 'Sistemas' == $this->session->userdata('codigo')
		)
		{
			if('0000-00-00' == $Info_pedido['fecha_reale'])
			{
?>
			<a href="/pedidos/modificar/index/<?=$Info_pedido['id_pedido']?>" class="iconos iruta toolder"><span>Modificar Ruta de Trabajo</span></a>
			<a href="/pedidos/especificacion/index/<?=$Info_pedido['id_pedido']?>/m" class="iconos idocumento toolder"><span>Modificar Hoja de Planificaci&oacute;n</span></a>
<?php
			}
			else
			{
				if($Modifica_Plani)
				{
?>
			<a href="/pedidos/especificacion/index/<?=$Info_pedido['id_pedido']?>/m" class="iconos idocumento toolder"><span>Modificar Hoja de Planificaci&oacute;n</span></a>
<?php
					$Modifica_Plani = false;
				}
				else
				{
?>
			<a href="/pedidos/especificacion/ver/<?=$Info_pedido['id_pedido']?>/n" class="iconos idocumento toolder"><span>Ver Hoja de Planificaci&oacute;n</span></a>
<?php
				}
			}
		}
		else
		{
?>
			&nbsp;
<?php
		}
		
?>
			<a href="javascript:ver_agregar_observacion('<?=$Info_pedido['id_pedido']?>');" class="iconos iobserva toolder"><span>Agregar Observaci&oacute;n</span></a>
			<a href="javascript:ver_agregar_scan('<?=$Id_Proceso?>-<?=$Info_pedido['id_pedido']?>');" class="iconos iscan toolder"><span>Agregar Archivos Adjuntos</span></a>
<?php

	//SOLAMENTE SISTEMAS TENDRA LAS OPCIONES DE ELIMINAR Y REVIVIR PEDIDO.
		if(
			'Gerencia' == $this->session->userdata('codigo')
			|| 'Plani' == $this->session->userdata('codigo')
			|| 'Sistemas' == $this->session->userdata('codigo')
		)
		{
			if($mostrar_icono_revivir != 'si' or '0000-00-00' == $Info_pedido['fecha_reale'])
			{
				$mostrar_icono_revivir = 'no';
			}
			else
			{
?>
			<label onclick="javascript:$('#nueva_fecha').toggle(function(){$('#id_pedido').val(<?=$Info_pedido['id_pedido']?>); $('#id_proceso').val(<?=$Id_Proceso?>);} )" class="iconos ialerta toolder"></label>
<?php
			$mostrar_icono_revivir = 'no';
			}
?>
			<a href="javascript:eliminar_pedido('<?=$Info_pedido['id_pedido']?>', '<?=$Id_Proceso?>');" class="iconos ieliminar toolder"><span>Eliminar Pedido</span></a>
		</td>
	</tr>
<?php
		}
	}
}

if(isset($Pedidos2))
{
	if(0 < count($Pedidos2))
	{
		$Modifica_Plani = true;
		
		foreach($Pedidos2 as $Info_pedido)
		{
			$fecha_entrada = explode('-', $Info_pedido['fecha_entrada']);
			$fecha_entrega = explode('-', $Info_pedido['fecha_entrega']);
			$fecha_reale = explode('-', $Info_pedido['fecha_reale']);
			
			if($Modifica_Plani && '0000-00-00' == $Info_pedido['fecha_reale'])
			{
				$Modifica_Plani = false;
			}
			
?>
	<tr>
		<td><?=$fecha_entrada[2].'-'.$fecha_entrada[1].'-'.$fecha_entrada[0]?></td>
		<td><?=$fecha_entrega[2].'-'.$fecha_entrega[1].'-'.$fecha_entrega[0]?></td>
		<td><?=$fecha_reale[2].'-'.$fecha_reale[1].'-'.$fecha_reale[0]?></td>
		<td>
			<strong><a href="/pedidos/pedido_detalle/index/<?=$Info_pedido['id_pedido']?>" title="Detalle de Pedido">Detalle</a></strong>
		</td>
		<td>
			<?php
			if(
				'Gerencia' == $this->session->userdata('codigo')
				|| 'Plani' == $this->session->userdata('codigo')
				|| 'Sistemas' == $this->session->userdata('codigo')
			)
			{
				if('0000-00-00' == $Info_pedido['fecha_reale'])
				{
?>
			<a href="/pedidos/modificar/index/<?=$Info_pedido['id_pedido']?>" class="iconos iruta toolder"><span>Modificar Ruta de Trabajo</span></a>
			<a href="/pedidos/especificacion/index/<?=$Info_pedido['id_pedido']?>/n" class="iconos idocumento toolder"><span>Modificar Hoja de Planificaci&oacute;n</span></a>
<?php
				}
				else
				{
?>
			<span class="iconos ivacio"></span>
<?php
					if($Modifica_Plani)
					{
?>
			<a href="/pedidos/especificacion/index/<?=$Info_pedido['id_pedido']?>/n" class="iconos idocumento toolder"><span>Modificar Hoja de Planificaci&oacute;n</span></a>
<?php
						$Modifica_Plani = false;
					}
					else
					{
?>
			<a href="/pedidos/especificacion/ver/<?=$Info_pedido['id_pedido']?>/n" class="iconos idocumento toolder"><span>Ver Hoja de Planificaci&oacute;n</span></a>
<?php
					}
				}
			}
			else
			{
?>
			&nbsp;
<?php
			}
?>
			<a href="javascript:ver_agregar_observacion('<?=$Info_pedido['id_pedido']?>');" class="iconos iobserva toolder"><span>Agregar Observaci&oacute;n</span></a>
			<a href="javascript:ver_agregar_scan('<?=$Id_Proceso?>-<?=$Info_pedido['id_pedido']?>');" class="iconos iscan toolder"><span>Agregar Archivos Adjuntos</span></a>
		</td>
	</tr>
<?php
		}
	}
}
?>
</table>


<div id="form_observacion" class="sombra" style="display: none;">
<?php
$this->load->view('/observaciones/agregar_obs_v', array('Cancelar' => TRUE));
?>
</div>

<?php
$this->load->view('/scan/cargar_scan_v', $num_cajas);

if(
	'Gerencia' == $this->session->userdata('codigo')
	|| 'Plani' == $this->session->userdata('codigo')
	|| 'Sistemas' == $this->session->userdata('codigo')
)
{
?>

<?php
}
?>

<div id="nueva_fecha">
	
	<input type='hidden' name='id_pedido' id='id_pedido'>
	<input type='hidden' name='id_proceso' id='id_proceso'>
	Nueva Fecha: <input type="text" name="nfecha" id="nfecha" size="15" value="" readonly="readonly" />
	<br />
	<input type="button" class="boton" id="revivir_ped" value="Revivir Pedido" onclick="revivir_pedido()" />
	
</div>

<script type="text/javascript">
	function ver_agregar_observacion(pedido)
	{
		$('#obs_pedido').val(pedido);
		$('#form_observacion textarea').val('');
		$('#form_observacion').show();
		$('#form_observacion textarea').focus();
	}
	
	$(function()
	{
		$('#form_observacion input[type=button]').click(function()
		{
			$('#form_observacion').hide();
		});
		$("[name=nfecha]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
		
		
<?php
if(
	'Gerencia' == $this->session->userdata('codigo')
	|| 'Sistemas' == $this->session->userdata('codigo')
	|| '1' === $this->session->userdata('id_usuario')
)
{
?>
		$('#elimina_proceso').click(function()
		{
			$('#div_elim_proceso').show('blind');
		});
		
		$('#div_elim_proceso .proceder').click(function()
		{
			if(confirm('Por favor confirme una vez mas que desea eliminar el Proceso'))
			{
				window.location = '/procesos/eliminar/index/<?=$Id_Proceso?>';
			}
		});
		
		$('#div_elim_proceso .cancelar').click(function()
		{
			$('#div_elim_proceso').hide('blind');
		});
<?php
}
?>
		
	});
	
</script>

<style type="text/css">
	
	#nueva_fecha
	{
		position: absolute;
		right: 300px;
		display: none;
		top:293px;
		background: white;
		border: 1px solid #F8C773;
		margin-bottom: 55px;
		padding: 15px 25px;
	}
	#form_observacion
	{
		background: #ffffff;
		border: 1px solid #F8C773;
		position: absolute;
		top: 150px;
		margin-left: 50px;
		padding: 15px 25px;
	}
</style>