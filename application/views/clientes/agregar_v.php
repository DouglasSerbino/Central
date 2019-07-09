<script src="/html/js/jquery.min.js"></script> 
<script src="/html/js/jquery-ui.min.js"></script> 
<script src="/html/js/bootstrap.min.js"></script>
<script src="/html/js/wizard.min.js"></script>
<link href="/html/css/wizard.css" rel="stylesheet">

<style>
	#info_anilox div{
		float: left;
		margin-right: 20px;
	}
	.txt_encabezado, .txt_encabezado:focus, .txt_encabezado:disabled{
		border: none;
		color: #333333;
		background: #ffffff;
	}
	.resaltado{
		font-weight: bold;
		background: #eeeeee;
	}
		font-size: 15px;		display: block;
		margin-bottom: 5px;
	}
	
	
	a:link
	{
		text-decoration:none;
	}
</style>


<form id="agregar_clientes" method="post" action="/clientes/agregar/agregar_datos" onsubmit="return validar('agregar_clientes')">
	<div class="panel-group wiz-aco" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						Informacion General
					</a>
				</h4> 
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body"> 
					<table class="table table-condensed table-borderless" >
						<tr>
							<td>Oficina</td>
							<td colspan="3">
								<select name="cpais">
									<?php
									foreach($Paises_C as $pCod => $pNomb)
									{
										?>
										<option value="<?=$pCod?>"><?=$pNomb?></option>
										<?php
									}
									?>
								</select>
								<span class="pais_sv" id="span_pais"></span>
							</td>
						</tr>
						<tr>
							<td>C&oacute;digo:*</td>
							<td>
								<input type="text" name="codigo" size="7" class="requ no_raros" maxlength="7" />
							</td>
							<td>Empresa:*</td>
							<td>
								<input type="text" name="nombre" size="35" class="requ" />
							</td>
						</tr>
						<tr>
							<td>NIT:*</td>
							<td>
								<input type="text" name="nit" size="20" class="requ" />
							</td>
							<td>D&iacute;as Cr&eacute;dito:*</td>
							<td>
								<input type="text" name="credito" size="5" class="requ" />
							</td>
						</tr>
						<tr>
							<td>Direcci&oacute;n:*</td>
							<td>
								<input type="text" name="direccion" size="35" class="requ" />
							</td>
							<td>Web</td>
							<td>
								<input type="text" name="web" size="35" />
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Informacion de Contacto
					</a>
				</h4> 
			</div>
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body"> 
					<span class="manita btn btn-sm  btn-success" info="contacto">+</span> Agregar Contacto
					<table class="table table-condensed table-borderless">
						<thead>
							<tr>
								<th>Contacto</th>
								<th>Cargo</th>
								<th>E-mail</th>
								<th>T. Oficina</th>
								<th>T. Directo</th>
								<th>T. Celular</th>
							</tr>
						</thead>
						<tbody id="info_contacto" class="info_filas"></tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingThree">
				<h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						Maquinas Impresoras
					</a>
				</h4> </div>
				<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
					<div class="panel-body"> 
						<span class="manita btn btn-sm  btn-success" info="maquina">+</span> Agregar M&aacute;quina
						<table class="table table-condensed table-borderless">
							<thead>
								<tr>
									<th>M&aacute;quina</th>
									<th>Colores</th>
								</tr>
							</thead>
							<tbody id="info_maquina" class="info_filas"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingThree">
					<h4 class="panel-title">
						<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
							Configuracion de planchas
						</a>
					</h4> </div>
					<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
						<div class="panel-body"> 
							<span class="manita btn btn-sm  btn-success" info="plancha">+</span> Agregar Plancha
							<table class="table table-condensed table-borderless">
								<thead>
									<tr>
										<th>Altura</th>
										<th>Lineaje</th>
										<th>Marca</th>
									</tr>
								</thead>
								<tbody id="info_plancha" class="info_filas"></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingThree">
						<h4 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
								Listado de Anilox
							</a>
						</h4> </div>
						<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
							<div class="panel-body"> 
								<span class="manita btn btn-sm  btn-success" info="anilox">+</span> Agregar Anilox
								<div id="info_anilox" class="table table-condensed table borderless">
									<?php
									for($i = 1; $i <= 3; $i++)
									{
										?>
										<div>
											<input type="text" size="10" class="txt_encabezado" disabled="disabled" value="Lineaje" />
											<input type="text" size="7" class="txt_encabezado" disabled="disabled" value="BCM" />
											<input type="text" size="7" class="txt_encabezado" disabled="disabled" value="Cantidad" />
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
									Listado de Productos
								</a>
							</h4> </div>
							<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								<div class="panel-body"> 
									<?php									if(isset($productos))
									{
										?>
										<table class="table table-condensed table-borderless" id="cliente_productos">
											<tr>
												<th>Descripci&oacute;n</th>
												<th>Precio</th>
												<th>Unidad de medida</th>
												<th>Asignar</th>
											</tr>
											<?php
											foreach ($productos as $Datos) {
												?>
												<tr id="tr-<?=$Datos['id_producto']?>">
													<td id="descripcion_producto-<?=$Datos['id_producto']?>" contenteditable="true" onblur="modificarProducto(<?=$Datos['id_producto']?>)"><?=$Datos['producto']?></td>
													<td>
														<input type="text" name="clie_prod_precio[]" size="7" />
														<input type="hidden" name="hid_clie_prod[]" value="<?=$Datos['id_producto']?>" />
													</td>
													<td>
														<input type="text" name="clie_concepto[]" size="7" value="" />
													</td>
													<td><input type="checkbox" name="chk_clie_prod_<?=$Datos['id_producto']?>" info="tr-<?=$Datos['id_producto']?>" /></td>
												</tr>
												<?php
											}
											?>
										</table>
										<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>

				</form>
<script>

					

					$('[name="codigo"]').focus();


					$('[name="cpais"]').change(function()
					{
						$('#span_pais').removeClass();
						$('#span_pais').addClass('pais_'+$(this).val());
					});


//Almacenar las filas
var Filas = {};

//Agregar contactos
Filas['contacto'] = '<tr>';
Filas['contacto'] = Filas['contacto'] + '<td><strong class="btn btn-sm btn-default">-</strong> <input type="text" name="clie_contacto[]" size="20" /></td>';
Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_cargo[]" size="20" /></td>';
Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_email[]" size="20" /></td>';
Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_tofic[]" size="9" /></td>';
Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_tdire[]" size="9" /></td>';
Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_tcelu[]" size="9" /></td>';
Filas['contacto'] = Filas['contacto'] + '</tr>';

$('#info_contacto').append(Filas['contacto']);


//Agregar maquina
Filas['maquina'] = '<tr>';
Filas['maquina'] = Filas['maquina'] + '<td><strong class="btn btn-sm btn-default">-</strong><input type="text" name="maquina[]" size="40" /></td>';
Filas['maquina'] = Filas['maquina'] + '<td><input type="text" name="colores[]" size="5"/></td>';
Filas['maquina'] = Filas['maquina'] + '</tr>';

$('#info_maquina').append(Filas['maquina']);


//Agregar plancha
Filas['plancha'] = '<tr>';
Filas['plancha'] = Filas['plancha'] + '<td><strong class="btn btn-sm btn-default">-</strong><input type="text" name="altura[]" size="20" /></td>';
Filas['plancha'] = Filas['plancha'] + '<td><input type="text" name="lineaje[]" size="5" /></td>';
Filas['plancha'] = Filas['plancha'] + '<td><input type="text" name="marca[]" size="25" value="Kodak Flexcel NX" /></td>';
Filas['plancha'] = Filas['plancha'] + '</tr>';

$('#info_plancha').append(Filas['plancha']);


//Agregar anilox
Filas['anilox'] = '';
for(i = 1; i <= 3; i++)
{
	Filas['anilox'] = Filas['anilox'] + '<div>';
	Filas['anilox'] = Filas['anilox'] + '<input type="text" size="9" name="anilox[]" />';
	Filas['anilox'] = Filas['anilox'] + '<input type="text" size="7" name="bcm[]" />';
	Filas['anilox'] = Filas['anilox'] + '<input type="text" size="7" name="ani_cantidad[]" />';
	Filas['anilox'] = Filas['anilox'] + '</div>';
}

$('#info_anilox').append(Filas['anilox']);


$('span.manita').click(function()
{
	$('#info_'+$(this).attr('info')).append(Filas[$(this).attr('info')]);
});

$('.info_filas').on('click', 'strong', function()
{
	$(this).parent().parent().remove();
});

$('#cliente_productos [type="checkbox"]').click(function()
{
	if(true == $(this).prop('checked'))
	{
		$('#'+$(this).attr('info')).addClass('resaltado');
	}
	else
	{
		$('#'+$(this).attr('info')).removeClass('resaltado');
	}
});

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
				finish: "Guardar Cliente"
			},
			templates: {
				buttons: function() {
					var options = this.options;
					return '<div class="panel-footer"><ul class="pager">' + '<li class="previous">' + '<a href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a>' + '</li>' + '<li class="next">' + '<a href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a>' + '<a href="#' + this.id + '" data-wizard="finish" role="button" onclick="guardarCliente()">' + options.buttonLabels.finish + '</a>' + '</li>' + '</ul></div>';
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

	function guardarCliente(){
		$('#agregar_clientes').submit();
	}

	onbl
	function modificarProducto(id_producto){
		$.ajax({
			url: '/productos/producto/modificarProducto',
			type: 'POST',
			data: {'id_producto': id_producto,
				   'descripcion_producto': $('#descripcion_producto-'+id_producto).text()
				  },
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
</script>
