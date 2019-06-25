<script type="text/javascript" src="/html/js/carga.js?n=1"></script>
<script type="text/javascript" src="/html/js/thickbox.js"></script>
<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />

<!-- Atencion: Estos estilos contraatacan los estilos generales, porque todo tiene tamanho especial -->
<style>
	
	table td, table th{
		padding: 1px 3px;
	}
	.tabular td, .tabular th{
		border-bottom: 2px solid #fdb930;
		line-height: 18px;
	}
	.rut_Agregado, .rut_Asignado, .rut_Procesando, .rut_Pausado, .rut_Aprobacion, .rut_Terminado{
		padding: 0px 1px;
		margin: 1px;
		font-size: 11px;
		border-radius: 2px 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px;
		display: inline-block;
		width: 36px;
	}
</style>

<form action="/carga/v_seguimiento" method="post" name="miform">
	
	<table>
		<tr>
			<td>
				
				<table>
					<tr>
						<td>Inicio</td>
						<td>
							<input type="text" name="dia1" id="dia1" size="5" value="<?=$Fechas['dia1']?>" />
							<select name="mes1" id="mes1">
<?
foreach($Meses as $Mes => $MNombre)
{
?>
								<option value="<?=$Mes?>"<?=($Mes==$Fechas['mes1'])?' selected="selected"':''?>><?=$MNombre?></option>
<?
}
?>
							</select>
							<input type="text" name="anho1" id="anho1" size="8" value="<?=$Fechas['anho1']?>" />
						</td>
					</tr>
					<tr>
						<td>Fin</td>
						<td>
							<input type="text" name="dia2" id="dia2" size="5" value="<?=$Fechas['dia2']?>" />
							<select name="mes2" id="mes2">
<?
foreach($Meses as $Mes => $MNombre)
{
?>
								<option value="<?=$Mes?>"<?=($Mes==$Fechas['mes2'])?' selected="selected"':''?>><?=$MNombre?></option>
<?
}
?>
							</select>
							<input type="text" name="anho2" id="anho2" size="8" value="<?=$Fechas['anho2']?>" />
						</td>
					</tr>
				</table>
				
			</td>
			<td>
				
				<table>
					<tr>
						<td>
							<input type="radio" name="trabajo" id="trabajo1" value="finalizado"<?=('finalizado'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo1">Finalizados</label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="trabajo" id="trabajo2" value="incompleto"<?=('incompleto'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo2">Incompletos</label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="trabajo" id="trabajo3" value="atrasado"<?=('atrasado'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo3">Atrasados</label>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="trabajo" id="trabajo4" value="reproceso"<?=('reproceso'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo4">Reproceso</label>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" class="boton" value="Generar Reporte" /></td>
		</tr>
	</table>
	
</form>


<?
if($Mostar_Datos)
{
?>

<br />

<div>
<table class="tabular">
	<tr>
		<th>&nbsp;</th>
		<th style="width: 130px;">Proceso</th>
		<th style="width: 450px;">Producto</th>
		<th style="width: 100px;">Ingreso</th>
		<th style="width: 100px;">Estimada</th>
		<?
if('finalizado' == $Trabajo)
{
?><th style="width: 90px;">Real</th><?
}
?>
		<th style="width: 55px;">&nbsp;</th>
		<th style="width: 300px;">Ruta</th>
	</tr>
<?
	foreach($Carga['trabajos'] as $Detalle)
	{
		if('finalizado' != $Trabajo)
		{
			if($Detalle['entre'] == date('Y-m-d'))
			{
				$Estado_Fecha = 'est_verde';
			}
			elseif(
				$this->fechas_m->fecha_mayor(
					$Detalle['entre'].' 00:01:02',
					date('Y-m-d').' 00:00:01'
				)
			)
			{
				$Estado_Fecha = 'est_azul';
			}
			else
			{
				$Estado_Fecha = 'est_rojo';
			}
		}
		else
		{
			if(
				!$this->fechas_m->fecha_mayor(
					$Detalle['entre'].' 00:01:02',
					$Detalle['reale'].' 00:00:01'
				)
			)
			{
				$Estado_Fecha = 'est_rojo';
			}
			else
			{
				$Estado_Fecha = 'est_verde';
			}
		}
		$Detalle['entra'] = explode('-', $Detalle['entra']);
		$Detalle['entre'] = explode('-', $Detalle['entre']);
		$Detalle['reale'] = explode('-', $Detalle['reale']);
?>
	<tr id="tr_ca_<?=$Detalle['id_pedido']?>">
		<td>
<?
	if($Detalle['url'] != '')
	{
?>
			<a href="<?=$Detalle['url']?>" class="thickbox" title='' >
				<img width='30px' height='25px' src="<?=$Detalle['url']?>" title="<?=$Detalle['nombre_adjunto']?>" />
			</a>
<?
	}
	else{ echo '&nbsp;'; }
?>
		</td>
		<td>
			<a href="javascript:ventana_externa('/pedidos/especificacion/ver/<?=$Detalle['id_pedido']?>/n');" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>
			<a href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$Detalle['id_pedido']?>');" class="toolizq"><span>Ver detalle</span>
				<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>
			</a><?=(4==$Detalle['tipo'])?' *':''?>
			
		</td>
		<td><?=$Detalle['prod']?></td>
		<td><?=$Detalle['entra'][2].'-'.$Detalle['entra'][1].'-'.$Detalle['entra'][0]?></td>
		<td class="<?=$Estado_Fecha?>"><?=$Detalle['entre'][2].'-'.$Detalle['entre'][1].'-'.$Detalle['entre'][0]?></td>
		<?
		if('finalizado' == $Trabajo)
		{
?><td><?=$Detalle['reale'][2].'-'.$Detalle['reale'][1].'-'.$Detalle['reale'][0]?></td><?
		}
?>
		<td>
<?
if(
	'finalizado' != $Trabajo
	&& 'Ventas' != $this->session->userdata('codigo')
)
{
?>
			<a href="javascript:ver_cambiar_fecha('<?=$Detalle['id_pedido']?>', '<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>', '<?=$Detalle['entre'][2].'-'.$Detalle['entre'][1].'-'.$Detalle['entre'][0]?>');" class="iconos icalendario toolder"><span>Cambiar Fecha de Entrega</span></a>
			<a href="javascript:finalizar_trabajo('<?=$Detalle['id_pedido']?>', '<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>');" class="iconos iterminado toolder"><span>Dar por Terminado</span></a>
<?
}
?>
		</td>
		<td>
<?
		if(isset($Carga['ruta'][$Detalle['id_pedido']]))
		{
			foreach($Carga['ruta'][$Detalle['id_pedido']] as $Ruta)
			{
				/*if('GR' == $Ruta['ini'])
				{
					if(!isset($Carga_Grupos['enlaces'][1][$Detalle['id_pedido']]))
					{
						continue;
					}
					
					if(isset($Carga_Grupos['ruta'][$Carga_Grupos['enlaces'][1][$Detalle['id_pedido']]]))
					{
						
						
						foreach($Carga_Grupos['ruta'][$Carga_Grupos['enlaces'][1][$Detalle['id_pedido']]] as $Ruta2)
						{
							if('GR' == $Ruta2['ini'] || 'Vent' == $Ruta2['ini'])
							{
								continue;
							}
?><span class="rut_<?=$Ruta2['est']?> toolder"><?=$Ruta2['ini']?><span><?=$Ruta2['usu']?>: <?=$Ruta2['est']?><br />Fecha Finalizado: <?=$Ruta2['fin']?></span></span><?
						}
						
					}
				}
				else
				{*/
?><span class="rut_<?=$Ruta['est']?> toolder"><?=$Ruta['ini']?><span><?=$Ruta['usu']?>: <?=$Ruta['est']?><br />Fecha Finalizado: <?=$Ruta['fin']?></span></span><?
				//}
			}
		}
?>
		</td>
	</tr>
<?
	}
?>
</table>

</div>
<?
}
?>


<input type="hidden" value="" name="id_pedido" id="id_pedido" />

<div id="fecha-trabajo" style="top:75px;display:none;">
	
	<strong>Modificar Fecha de Despacho</strong>
	
	<br />
	Proceso: &nbsp; <span id="correlativo"></span>
	
	<br />
	<input type="hidden" name="fecha_anterior" id="fecha_anterior" value="" />
	<div style="text-align:left;">
		Nueva Fecha: <input type="text" name="fecha_entrega" id="fecha_entrega" size="15" value="" readonly="readonly" />
		<br />
		Solicitado por: <input type="text" name="quien_solicita" id="quien_solicita" size="15" />
		<br />
		Justificaci&oacute;n: <input type="text" name="justifica_fecha" id="justifica_fecha" size="35" />
	</div>
	
	<br />
	<input type="button" class="boton" value="Cancelar" onclick="$('#fecha-trabajo').hide();" /> &nbsp; 
	<input type="button" class="boton" id="btn_cambiar" value="Cambiar Fecha" onclick="cambiar_fecha()" />
	
</div>



<script>
	$(function(){
		$("[name=fecha_entrega]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
	});
	$('#dia1').focus();
</script>
