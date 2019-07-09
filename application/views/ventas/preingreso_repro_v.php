
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<script type="text/javascript" src="/html/js/procesos.js?n=1"></script>
<script src="/html/js/jquery.min.js"></script> 
<script src="/html/js/jquery-ui.min.js"></script> 
<script src="/html/js/bootstrap.min.js"></script>
<script src="/html/js/wizard.min.js"></script>
<link href="/html/css/wizard.css" rel="stylesheet">

<form name="form_espec_repro" id="form_espec_repro" action="/ventas/preingreso/ingresar" method="post">
<input type="hidden" id="cliente" name="cliente" value="" />
    <div class="panel-group wiz-aco" id="accordion" role="tablist" aria-multiselectable="true">
    	<div class="panel panel-default">
    		<div class="panel-heading" role="tab" id="headingOne">
    			<h4 class="panel-title">
    				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
    					Datos Generales
    				</a>
    			</h4> 
    		</div>
    		<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
    			<div class="panel-body">  

    				<div class="container-fluid">         	                    	<!-- ***************** -->
    					<table class="table table-condensed table-borderless">
    						<tr>
    							<td>C&oacute;d. Cliente:</td>
    							<td colspan="3"><input type="text" name="codigo_cliente" id="codigo_cliente" value="" onblur="vercliente(this.value)" size="10" /></td>
    						</tr>
    						<tr>
    							<td>Proceso:</td>
    							<td colspan="3">
    								<input type="text" name="proceso" id="proceso" value="" onblur="verifica_proceso()" />
    								<input type="button" onclick="genera_correlativo()" value="Generar" class="btn btn-info" />
    							</td>
    						</tr>
    						<tr>
    							<td>Cliente:</td>
    							<td colspan="3"><input type="text" name="nombre_cliente" id="nombre_cliente" value="" disabled="disabled" /></td>
    						</tr>
    						<tr>
    							<td>Producto:</td>
    							<td colspan="3"><input type="text" name="producto" id="producto" value="" size="50" /></td>
    						</tr>
    						<tr>
    							<td>Fecha Ingreso: </td>
    							<th><?=date('d-m-Y')?></th>
    						</tr>
    						<tr>
    							<td>Fecha Solicitado:</td>
    							<th><input type="text" readonly="readonly" name="fecha_entrega" id="fecha_entrega" size="12" value="" /></th>
    						</tr>
    					</table>
    				</div> 
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
    					</div>
    				</div>
    			</div>
    			<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingTwo">
    					<h4 class="panel-title">
    						<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
    							Especificaciones
    						</a>
    					</h4> 
    				</div>
    				<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
    					<div class="panel-body"> 
    						<!-- ***************** -->
    						<strong>Tipo de trabajo:</strong>
    						<select name="tipo_trabajo">
    							<?php
    							foreach($Tipos_Trabajo as $Tipo)
    							{
    								?>
    								<option value="<?=$Tipo['id_tipo_trabajo']?>"><?=$Tipo['trabajo']?></option>
    								<?php
    							}
    							?>
    						</select>



    						<br />


    						<!-- ***************** -->
    						<strong>Tipo de Impresi&oacute;n:</strong>
    						<?php
    						foreach($Tipos_Impresion as $Tipo)
    						{
    							?>
    							<input type="radio" name="id_tipo_impresion" id="iti_<?=$Tipo['id_tipo_impresion']?>" value="<?=$Tipo['id_tipo_impresion']?>"
    							<?php
    							if($Tipo['id_tipo_impresion'] == 2)
    							{
    								?> checked="checked"
    								<?php
    							}
    							?>
    							/>
    							<label for="iti_<?=$Tipo['id_tipo_impresion']?>"><?=$Tipo['tipo_impresion']?></label>
    							<?php
    						}
    						?>

    						<!-- ***************** -->
    						<strong>Embobinado</strong>

    						<br />
    						Cara:
    						<?php
    						$Embobinado = 1;
    						for($i = 1; $i <= 8; $i++)
    						{
    							?>
    							&nbsp; <input type="radio" name="embobinado_cara" id="embo_cara_<?=$i?>" value="<?=$i?>"<?=($i==$Embobinado)?' checked="checked"':''?> />
    							<label for="embo_cara_<?=$i?>"><span class="embobinados embo<?=$i?>"></span></label>
    							<?php
    						}
    						?>

    						<br /><br />
    						Dorso:
    						<?php
    						for($i = 1; $i <= 8; $i++)
    						{
    							?>
    							&nbsp; <input type="radio" name="embobinado_dorso" id="embo_dorso<?=$i?>" value="<?=$i?>"<?=($i==$Embobinado)?' checked="checked"':''?> />
    							<label for="embo_dorso<?=$i?>"><span class="embobinados embo<?=$i?>"></span></label>
    							<?php
    						}
    						?>
    						<!-- ***************** -->
    						<br>
    						<input type="checkbox" name="chk_impresion_digital" id="chk_impresion_digital" class="mues_ocul_dp" />
    						<label for="chk_impresion_digital"><strong>Impresi&oacute;n Digital</strong></label>

    						<div id="divchk_impresion_digital" style="display: none;">

    							<table style="width: 100%;">
    								<tr>
    									<!-- ***************** -->
    									<td style="width: 33%;" rowspan="2">

    										<strong>Acabados</strong>

    										<?php
    										foreach($Tipo_Acabado as $Tipo)
    										{
    											?>
    											<br />
    											<input type="checkbox" name="impd_acabado_<?=$Tipo['id_tipo_impd_acabado']?>" id="impd_acabado_<?=$Tipo['id_tipo_impd_acabado']?>" />
    											<label for="impd_acabado_<?=$Tipo['id_tipo_impd_acabado']?>"><?=$Tipo['tipo_impd_acabado']?></label>
    											<?php
    										}
    										?>

    									</td>

    									<!-- ***************** -->
    									<td style="width: 33%;">

    										<strong>Material</strong>

    										<?php
    										foreach($Tipo_Material as $Tipo)
    										{
    											?>
    											<br />
    											<input type="radio" name="id_tipo_impd_material" id="impd_material_<?=$Tipo['id_tipo_impd_material']?>" value="<?=$Tipo['id_tipo_impd_material']?>" />
    											<label for="impd_material_<?=$Tipo['id_tipo_impd_material']?>"><?=$Tipo['tipo_impd_material']?></label>
    											<?php
    										}
    										?>
    										<br />
    										<input type="radio" name="id_tipo_impd_material" id="impd_material_100" value="100" />
    										<label for="impd_material_100">Otro</label>
    										<input type="text" name="otro_impd_material" id="otro_impd_material" value="" />


    										<br /><br />
    										<strong>Cant. Impresiones:</strong>
    										<input type="text" name="cant_impresiones" id="cant_impresiones" value="" />

    									</td>

    									<!-- ***************** -->
    									<td>

    										<strong>Imposici&oacute;n</strong>

    										<br />
    										<input type="radio" name="imposicion" id="imposicion_t" value="t" />
    										<label for="imposicion_t">Tiro</label>

    										<br />
    										<input type="radio" name="imposicion" id="imposicion_tr" value="tr" />
    										<label for="imposicion_tr">Tiro y Retiro</label>


    										<br /><br />
    										<strong>Colores</strong>

    										<br />
    										Tiro:
    										&nbsp; &nbsp; &nbsp;
    										<input type="radio" name="tiro_color" id="tiro_color1" value="B/W" />
    										<label for="tiro_color1">B/W</label>
    										&nbsp; <input type="radio" name="tiro_color" id="tiro_color2" value="FC" />
    										<label for="tiro_color2">FC</label>

    										<br />
    										Retiro: &nbsp;
    										<input type="radio" name="retiro_color" id="retiro_color1" value="B/W" />
    										<label for="retiro_color1">B/W</label>
    										&nbsp; <input type="radio" name="retiro_color" id="retiro_color2" value="FC" />
    										<label for="retiro_color2">FC</label>

    									</td>
    								</tr>
    								<tr>
    									<!-- ***************** -->
    									<td colspan="2">
    										<br />
    										<strong>Otros Acabados:</strong>
    										<input type="text" name="otro_acabado_imp" id="otro_acabado_imp" size="40" value="" />
    									</td>
    								</tr>
    							</table>

    						</div>



    					</div>
    				</div>
    			</div>
    			<div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingThree">
    					<h4 class="panel-title">
    						<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
    							Datos del Arte
    						</a>
    					</h4> </div>
    					<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
    						<div class="panel-body"> 
    							<!-- ***************** -->
    							<table class="table table-borderless table-condensed">
    								<tr>
    									<!-- ***************** -->
    									<td style="width: 33%;">
    										Unidad de Medida:
    										<select name="unidad_medida" id="unidad_medida">
    											<option value="mm">mm</option>
    											<option value="in">in</option>
    										</select>

    										<br />
    										<input type="text" size="9" name="alto_arte" id="alto_arte" value="" />
    										Alto del Arte

    										<br />
    										<input type="text" size="9" name="ancho_arte" id="ancho_arte" value="" />
    										Ancho del Arte

    										<br />
    										<input type="text" size="9" name="ancho_fotocelda" id="ancho_fotocelda" value="" />
    										Ancho de Fotocelda

    										<br />
    										<input type="text" size="9" name="alto_fotocelda" id="alto_fotocelda" value="" />
    										Alto de Fotocelda

    										<br />
    										<input type="text" size="9" name="color_fotocelda" id="color_fotocelda" value="" />
    										Color Fotocelda

    									</td>

    									<!-- ***************** -->
    									<td style="width: 33%;">

    										<strong>Impresi&oacute;n</strong>

    										<br />
    										<input type="radio" name="lado_impresion" id="lado_impresion_cara" value="cara" />
    										<label for="lado_impresion_cara">Cara</label>

    										&nbsp;

    										<input type="radio" name="lado_impresion" id="lado_impresion_dorso" value="dorso" />
    										<label for="lado_impresion_dorso">Dorso</label>

    										<br /><br />

    										<strong>Emulsi&oacute;n del Negativo</strong>

    										<br />
    										<input type="radio" name="emulsion_negativo" id="emulsion_negativo_cara" value="cara" />
    										<label for="emulsion_negativo_cara">Cara</label>

    										&nbsp;

    										<input type="radio" name="emulsion_negativo" id="emulsion_negativo_dorso" value="dorso" />
    										<label for="emulsion_negativo_dorso">Dorso</label>

    									</td>

    									<!-- ***************** -->
    									<td>

    										<input type="checkbox" name="chk_impresion_cbd" id="chk_impresion_cbd" class="mues_ocul_dp" />
    										<label for="chk_impresion_cbd"><strong>C&oacute;digo de Barra</strong></label>


    										<div id="divchk_impresion_cbd" style="display: none;">
    											<input type="text" size="19" name="codb_tipo" id="codb_tipo" value="" />
    											Tipo

    											<br />
    											<input type="text" size="19" name="codb_num" id="codb_num" value="" />
    											N&uacute;mero

    											<br />
    											<input type="text" size="19" name="codb_magni" id="codb_magni" value="" />
    											Magnificaci&oacute;n

    											<br />
    											<input type="text" size="19" name="codb_posicion" id="codb_posicion" value="" />
    											Posici&oacute;n

    											<br />
    											<input type="text" size="19" name="codb_bwr" id="codb_bwr" value="" />
    											BWR
    										</div>

    									</td>
    								</tr>
    							</table>
    						</div>
    					</div>
    				</div>
    				<div class="panel panel-default">
    					<div class="panel-heading" role="tab" id="headingThree">
    						<h4 class="panel-title">
    							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
    								Datos de Montaje
    							</a>
    						</h4> </div>
    						<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
    							<div class="panel-body">
    								<strong>Datos de Montaje</strong>

    								<table class="table table-bordered table-borderless">
    									<tr>
    										<td><input type="text" size="9" name="repet_ancho" id="repet_ancho" value="1" /></td>
    										<td>Repeticiones en Ancho</td>
    										<td><input type="text" size="9" name="repet_alto" id="repet_alto" value="1" /></td>
    										<td>Repeticiones en Alto</td>
    									</tr>
    									<tr>
    										<td><input type="text" size="9" name="separ_ancho" id="separ_ancho" value="0" /></td>
    										<td>Separaci&oacute;n en Ancho</td>
    										<td><input type="text" size="9" name="separ_alto" id="separ_alto" value="0" /></td>
    										<td>Separaci&oacute;n en Alto</td>
    									</tr>
    								</table>



    								<!-- ***************** -->
    								<table>
    									<tr>
    										<!-- ***************** -->
    										<td>

    											<table class="padding1">
    												<tr>
    													<th>&nbsp;</th>
    													<th>Colores</th>
    													<th><span class="toolizq punteado">S<span>Color Solicitado</span></span></th>
    													<th><span class="toolizq punteado">T<span>Tono</span></span></th>
    													<th><span class="toolizq punteado">L<span>L&iacute;nea</span></span></th>
    													<th>&Aacute;ngulo</th>
    													<th>Lineaje</th>
    													<!-- th>Resoluci&oacute;n</th-->
    												</tr>
    												<?php
    												for($i = 1; $i <= 10; $i++)
    												{
    													?>
    													<tr>
    														<td><?=$i?></td>
    														<td><input type="text" size="10" name="color_<?=$i?>" id="color_<?=$i?>" value="" onblur="poner_angulos('<?=$i?>')" /></td>
    														<td><input type="checkbox" name="solicitado_<?=$i?>" id="solicitado_<?=$i?>" onclick="pintar_caja('<?=$i?>')" /></td>
    														<td><input type="checkbox" name="tono_<?=$i?>" id="tono_<?=$i?>" /></td>
    														<td><input type="checkbox" name="linea_<?=$i?>" id="linea_<?=$i?>" /></td>
    														<td><input type="text" size="4" name="angulo_<?=$i?>" id="angulo_<?=$i?>" value="" /></td>
    														<td><input type="text" size="4" name="lineaje_<?=$i?>" id="lineaje_<?=$i?>" value="" /></td>
    														<!-- td><input type="text" size="4" name="resolucion_<?=$i?>" id="resolucion_<?=$i?>" value="" /></td-->
    													</tr>
    													<?php
    												}
    												?>
    											</table>

    										</td>

    										<!-- ***************** -->
    										<td>

    											<input type="checkbox" name="chk_guia_mont" id="chk_guia_mont" class="mues_ocul_dp" />
    											<label for="chk_guia_mont"><strong>Gu&iacute;as de Montaje</strong></label>

    											<table id="divchk_guia_mont" style="display: none;">
    												<tr>
    													<td>
    														<input type="checkbox" name="registro" id="registro" />
    														<label for="registro">Gu&iacute;as de Registro</label>
    													</td>
    													<td>
    														<input type="checkbox" name="espectofotometria" id="espectofotometria" />
    														<label for="espectofotometria">Espectofotometr&iacute;a</label>
    													</td>
    													<td>
    														<input type="checkbox" name="corte" id="corte" />
    														<label for="corte">Gu&iacute;as de Corte</label>
    													</td>
    												</tr>
    												<tr>
    													<td>
    														<input type="checkbox" name="micropuntos" id="micropuntos" />
    														<label for="micropuntos">Micropuntos</label>
    													</td>
    													<td colspan="2">
    														<input type="checkbox" name="semaforo" id="semaforo" />
    														<label for="semaforo">Sem&aacute;foros</label>
    													</td>
    												</tr>
    												<tr>
    													<td>
    														<input type="checkbox" name="grafikontrol" id="grafikontrol" />
    														<label for="grafikontrol">Grafikontrol</label>
    													</td>
    													<td colspan="2">
    														<input type="text" name="color" id="color" value="" />
    														Color
    													</td>
    												</tr>
    											</table>

    											<br />

    											<input type="checkbox" name="chk_distorsion" id="chk_distorsion" class="mues_ocul_dp" />
    											<label for="chk_distorsion"><strong>Distorsi&oacute;n</strong></label>

    											<table id="divchk_distorsion" style="display: none;">
    												<tr>
    													<th colspan="3">
    														<a href="javascript:limpiar()">[Borrar Datos]</a>
    													</th>
    												</tr>
    												<tr>
    													<td>
    														<input type="text" name="radio" id="radio" size="6" value="" onblur="calcular_distorsion()" /> Radio
    													</td>
    													<td>
    														<?php
    														$Polimero = array(
    															'0.045', '0.067', '0.090',
    															'0.100', '0.107', '0.112',
    															'0.115', '0.155', '0.250'
    														);

    														$Stickyback = array('0.015', '0.020', '0.0177', '0.250', '0.60');
    														?>
    														<select name="polimero" id="polimero" onchange="calcular_distorsion()">
    															<option value="">Polimero</option>
    															<?php
    															foreach($Polimero as $index => $Valor)
    															{
    																?>
    																<option value="<?=$Valor?>"><?=$Valor?></option>
    																<?php
    															}
    															?>
    														</select>
    													</td>
    													<td>
    														<select name="stickyback" id="stickyback" onchange="calcular_distorsion()">
    															<option value="">StickyBack</option>
    															<?php
    															foreach($Stickyback as $index => $Valor)
    															{
    																?>
    																<option value="<?=$Valor?>"><?=$Valor?></option>
    																<?php
    															}
    															?>
    														</select>
    													</td>
    												</tr>
    												<tr>
    													<td>
    														<input type="text" name="k" id="k" size="6" value="" />
    														<span class="toolder punteado">(K)<span>Constante (Valor autom&aacute;tico)</span></span>
    													</td>
    													<td>
    														<input type="text" name="pb" id="pb" size="6" value="" onblur="calcular_distorsion()" />
    														<span class="toolder punteado">PB<span>Per&iacute;metro Base</span></span>
    													</td>
    													<td>
    														<input type="text" name="pa" id="pa" size="6" value="" onblur="calcular_distorsion()" />
    														<span class="toolder punteado">PA<span>Per&iacute;metro Aumentado</span></span>
    													</td>
    												</tr>
    												<tr>
    													<td>
    														<input type="text" name="dp" id="dp" size="6" value="" />
    														<span class="toolder punteado">DP<span>Distorsi&oacute;n Positiva</span></span>
    													</td>
    													<td>
    														<input type="text" name="dn" id="dn" size="6" value="" />
    														<span class="toolder punteado">DN<span>Distorsi&oacute;n Negativa</span></span>
    													</td>
    												</tr>
    											</table>

    										</td>
    									</tr>
    								</table>
    							
    						</div>
    					</div>
    				</div>
    				<div class="panel panel-default">
    					<div class="panel-heading" role="tab" id="headingThree">
    						<h4 class="panel-title">
    							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
    								Cotizacion
    							</a>
    						</h4> </div>
    						<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
    							<div class="panel-body">
    								<input type="checkbox" name="cotizacion" id="cotizacion" style="display: none;" checked="checked" />
    								<div id="div_cotizaciones"></div>

    								<br />
    							</div>
    						</div>
    					</div>
    					<div class="panel panel-default">
    						<div class="panel-heading" role="tab" id="headingThree">
    							<h4 class="panel-title">
    								<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
    									Observaciones
    								</a>
    							</h4> </div>
    							<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
    								<div class="panel-body">
    									<strong>Observaciones</strong>
    									<br />
    									<textarea name="observaciones" id="observaciones" rows="4" cols="60"></textarea>

    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>


<!-- <input type="button" value="Agregar Pre-Ingreso" id="agr_pre_ingreso" disabled="disabled" onclick="guardar_preingreso()" /> -->
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
	
	$(function()
	{
		$("[name=fecha_entrega]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
		
		$('.mues_ocul_dp').click(function()
		{
            
			if($('#'+$(this).attr('id')).is(':checked'))
			{
				$('#div'+$(this).attr('id')).show();
			}
			else
			{
				$('#div'+$(this).attr('id')).hide();
			}
		});
		
		$("#dialog").dialog({ autoOpen: false, width: 460, resizable: false, modal: true });
	});
	
	poner_angulos('');
	
	//Pintamiento de cajas
	for(i = 1; i <= 10; i++)
	{
		pintar_caja(i);
	}
	
	
	
	
	function ver_preingreso_br()
	{
		if('' == $('#producto').val() || '' == $('#nombre_cliente').val())
		{
			alert('La informacion del Proceso no debe estar vacia');
			return false;
		}
		if('none' == $('#nuevo_proc').css('display'))
		{
			guardar_preingreso()
		}
	}
	
	
	function enviar_espec()
	{
		if(confirm('La informaci\xf3n ser\xe1 modificada.\r\nDesea continuar?'))
		{
			$('#form_espec_repro').submit();
		}
	}
</script>

<!-- Scripts para el acordion -->
<script type="text/javascript">
	(function() {
		$('#accordion').wizard({
			step: '[data-toggle="collapse"]',
			buttonsAppendTo: '.panel-collapse',
			 buttonLabels:{
                        back: "Anterior",
                        next: "Siguiente",
                        finish: "Agregar Pre-Ingreso"
            },
			templates: {
				buttons: function() {
					var options = this.options;
					return '<div class="panel-footer"><ul class="pager">' + '<li class="previous">' + '<a href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a>' + '</li>' + '<li class="next">' + '<a href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a>' + '<a href="#' + this.id + '" data-wizard="finish" role="button" onclick="guardar_preingreso()">' + options.buttonLabels.finish + '</a>' + '</li>' + '</ul></div>';
				}
			},
			onBeforeShow: function(step) {
				step.$pane.collapse('show');
			},
			onBeforeHide: function(step) {
				step.$pane.collapse('hide');
			},
			onFinish: function() {
				swal("Message Finish!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
			}
		});
	})();
</script>

