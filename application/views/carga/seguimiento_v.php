<script type="text/javascript" src="/html/js/carga.js?n=1"></script>
<script type="text/javascript" src="/html/js/thickbox.js"></script>
<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<script src="/html/js/bootstrap.min.js"></script>
<script src="/html/js/jquery.min.js"></script> 
<script src="/html/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/html/css/pedido.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Atencion: Estos estilos contraatacan los estilos generales, porque todo tiene tamanho especial -->
<style>
	
	table td, table th{
		padding: 1px 3px;
	}
	.tabular td, .tabular th{
		border-bottom: 2px solid #b3b3b3;
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
	
	
	.tablero_listas{
		position: absolute;
		top: 250px;
		left: 10px;
		width: 2500px;
		padding-bottom: 50px;
	}
	.tablero_listas div{
		position: absolute;
		top: 0px;
	}
	.tablero_listas ul{
		clear: both;
		display: block;
		height: 24px;
		overflow: hidden;
		/*background: url(/html/img/un_dia.png);*/
	}
	.tablero_listas li{
		float: left;
		overflow: hidden;
		font-size: 10px;
		height: 22px;
	}
	.tiempos .no_sortable{
		font-weight: 700;
	}
	.tiempos li{
		height: 24px;
	}
	.no_sortable{
		width: 98px;
		cursor: pointer;
		padding-left: 3px;
	}
	.no_sortable:hover{
		background-color: #cccccc;
	}
	.line_Agregado, .line_Procesando, .line_Asignado, .line_Pausado, .no_sortable{
		border: 1px solid #ffffff;
		color: #000000;
	}
	.line_Agregado{
		background: #d5d5d5;
	}
	.line_Procesando{
		background: #c5d09a;
	}
	.line_Asignado, .line_Pausado{
		background: #c5d09a;
	}
	.ui-sortable li{
		cursor: pointer;
	}
	.ui-sortable li:active{
		cursor: move;
	}
	.ui-sortable .line_Agregado:active, .ui-sortable .line_Agregado:hover,
	.ui-sortable .line_Procesando:active, .ui-sortable .line_Procesando:hover,
	.ui-sortable .line_Asignado:active, .ui-sortable .line_Pausado:active,
	.ui-sortable .line_Asignado:hover, .ui-sortable .line_Pausado:hover{
		border: 1px solid #888888;
	}
	.ui-sortable .no_sortable:hover, .ui-sortable .no_sortable:active{
		border: 1px solid #ffffff;
		cursor: default;
	}
	.ui-sortable .line_Agregado:active, .ui-sortable .line_Agregado:hover{
		background-color: #aaaaaa;
	}
	.ui-sortable .line_Procesando:active, .ui-sortable .line_Procesando:hover{
		background-color: #7CB26B;
	}
	.ui-sortable .line_Asignado:active, .ui-sortable .line_Pausado:active,
	.ui-sortable .line_Asignado:hover, .ui-sortable .line_Pausado:hover{
		background-color: #7795B2;
	}
	.tiempos li{
		color: #000;
		background: url(/html/img/line_titulo.png);
	}
	.posicion_fix .no_sortable{
		background: #dddddd;
		overflow: hidden;
	}
	.posicion_fix span:hover{
		text-decoration: underline;
	}
	
	
	input.btn_bonito{
		float: right;
		margin-top: 10px;
		border: none;
		color: #555555;
		font: bold 12px "helvetica neue", helvetica, arial, sans-serif;
		line-height: 1;
		text-align: left;
		width: 100px;
		height: 45px;
		cursor: pointer;
		background: url('/html/img/flecha-inicio.png') repeat-x;
	}
	input.btn_bonito:hover{
		color: #333333;
		background: url('/html/img/flecha-inicio.png') repeat-x;
	}
	
</style>

<script type="text/javascript">
	$(function() {
		$('.span_carga').click(function(){
			atras_adelante_carga($(this).attr('id'));
		});
	});
	
	$('#form_scan input[type=file]').bind('change', function()
	{
		cambio_scan($(this).attr('id'));
	});
</script>
<!-- <table>
 --><tr>
<select id="departamento_areas">
	<?php 
	foreach ($Usuarios as $Dpto_Usuarios) {
		if(
			'Gerente de Grupo' != $Dpto_Usuarios['dpto']
			&& 'Sistemas Inform&aacute;ticos' != $Dpto_Usuarios['dpto']
			&& 'Planificaci&oacute;n' != $Dpto_Usuarios['dpto']
			&& 'Ventas' != $Dpto_Usuarios['dpto']
			&& 'Grupo Externo' != $Dpto_Usuarios['dpto'])
		{
	?>
		<option value="<?=$Dpto_Usuarios['id_dpto']?>"><?=$Dpto_Usuarios['dpto']?></option>
	<?php 
		}
	}
	?>
</select>

<select name="mes1" id="mes1">
	<?php
		foreach($Meses as $Mes => $MNombre)
		{
	?>
		<option value="<?=$Mes?>"<?=($Mes==$Fechas['mes1'])?' selected="selected"':''?>><?=$MNombre?></option>
	<?php
		}
	?>
</select>
<input type="text" name="anho1" id="anho1" size="8" value="<?=$Fechas['anho1']?>" />
</td>
</tr>
<button class="btn btn-sm btn-default">Ver Reporte</button>
<!-- </table> -->


<br>
<br>
<div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                	<h3 class="text-center"><i class="material-icons">group</i> 3</h3>
                                	<h5 class="text-center">Operadores</h5>
                                </div>
                                <button class="btn panel-info btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                	<h3 class="text-center">62</h3>
                                	<h5 class="text-center">Promedio de trabajos realizados</h5>
                                </div>

                                <button class="btn panel-warning btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                </button>

                                <div class="collapse" id="collapseExample">
									   <div class="card card-body">
										   	<ul class="list-group">
										   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
										   		<li class="list-group-item"> REYNALDO</li>
										   		<li class="list-group-item"> ROBERTO</li> -->
										   		<h5 class="text-center">CHRISTIAN</h5>
										   		<h5 class="text-center">REYNALDO</h5>
										   		<h5 class="text-center">ROBERTO</h5>
										   	</ul>
									   
									    </div>
								</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                               <div class="panel-body">
                                	<h3 class="text-center">0.00%</h3>
                                	<h5 class="text-center">Promedio de rechazos</h5>
                                </div>
                                <button class="btn panel-danger btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                	<h3 class="text-center">0.00%</h3>
                                	<h5 class="text-center">Horas extras realizadas</h5>
                                </div>
                                <button class="btn panel-success btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                	<h3 class="text-center">85.00%</h3>
                                	<h5 class="text-center">Meta: Indice global de productividad</h5>
                                </div>
                                <button class="btn panel-info btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                	<h3 class="text-center">63.85%</h3>
                                	<h5 class="text-center">Real: Porcentaje de productividad</h5>
                                </div>
                                <button class="btn panel-success btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                               <div class="panel-body">
                                	<h3 class="text-center">95:32</h3>
                                	<h5 class="text-center">Promedio de horas utilizadas</h5>
                                </div>
                                <button class="btn panel-warning btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                            </div>
                            <div class="panel-wrapper collapse in" aria-expanded="true">
                                <div class="panel-body">
                                	<h3 class="text-center">80:29</h3>
                                	<h5 class="text-center">Promedio de horas disponibles</h5>
                                </div>
                                <button class="btn panel-danger btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                	 	 <i class="material-icons">expand_more</i>
                                	 </button>

                                	<div class="collapse" id="collapseExample">
									   <div class="card card-body">
									   	<ul class="list-group">
									   	<!-- 	<li class="list-group-item"> CHRISTIAN</li>
									   		<li class="list-group-item"> REYNALDO</li>
									   		<li class="list-group-item"> ROBERTO</li> -->
									   		<h5 class="text-center">CHRISTIAN</h5>
									   		<h5 class="text-center">REYNALDO</h5>
									   		<h5 class="text-center">ROBERTO</h5>
									   	</ul>
									   
									  </div>
									</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->


<form action="/carga/seguimiento" method="post" name="miform" id="formseguimiento">
	<table>
		<tr>
			<td>
				<table>
					<tr>
						<td>Comienzo</td>
						<td>
							<input type="text" name="dia1" id="dia1" size="5" value="<?=$Fechas['dia1']?>" />
							<select name="mes1" id="mes1">
<?php
foreach($Meses as $Mes => $MNombre)
{
?>
								<option value="<?=$Mes?>"<?=($Mes==$Fechas['mes1'])?' selected="selected"':''?>><?=$MNombre?></option>
<?php
}
?>
							</select>
							<input type="text" name="anho1" id="anho1" size="8" value="<?=$Fechas['anho1']?>" />
						</td>
					</tr>
					<tr>
						<td>Finalizaci&oacute;n</td>
						<td>
							<input type="text" name="dia2" id="dia2" size="5" value="<?=$Fechas['dia2']?>" />
							<select name="mes2" id="mes2">
<?php
foreach($Meses as $Mes => $MNombre)
{
?>
								<option value="<?=$Mes?>"<?=($Mes==$Fechas['mes2'])?' selected="selected"':''?>><?=$MNombre?></option>
<?php
}
?>
							</select>
							<input type="text" name="anho2" id="anho2" size="8" value="<?=$Fechas['anho2']?>" />
						</td>
					</tr>
					<tr>
						<td>Pa&iacute;s</td>
						<td>
							<select name="pais_c" id="pais_c">
								<option value="">Todos</option>
<?php
foreach($Paises_C as $iPais => $nPais)
{
?>
								<option value="<?=$iPais?>"<?=($Pais_C==$iPais)?' selected="selected"':''?>><?=$nPais?></option>
<?php
}
?>
							</select>
						</td>
					</tr>
					<tr>
						<td>&Aacute;rea</td>
						<td>
							<select name="puesto" id="puesto">
								<option value="todos">Todos</option>
	<?php
		foreach($Usuarios as $Dpto_Usuarios)
		{
		if(
			'Gerente de Grupo' != $Dpto_Usuarios['dpto']
			&& 'Sistemas Inform&aacute;ticos' != $Dpto_Usuarios['dpto']
			&& 'Planificaci&oacute;n' != $Dpto_Usuarios['dpto']
			&& 'Ventas' != $Dpto_Usuarios['dpto']
			&& 'Grupo Externo' != $Dpto_Usuarios['dpto'])
		{
	?>
				<optgroup label="<?=$Dpto_Usuarios['dpto']?>">
			<?php
				foreach($Dpto_Usuarios['usuarios'] as $Id_Usuario => $Usuario)
				{
			?>
				<option value="<?=$Id_Usuario?>"<?=($Id_Usuario==$Puesto)?' selected="selected"':''?>><?=$Usuario['usuario']?></option>
			<?php
				}
			?>
				</optgroup>
			<?php
			}
		}
?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Cliente</td>
						<td>
							<input type="hidden" name="cliente_tipo" id="cliente_tipo" value="todos" />
							<select name="cliente" id="cliente">
								<option value="todos">Todos</option>
<?php
foreach($Clientes as $Cliente)
{
?>
								<option value="<?=$Cliente['id_cliente']?>"<?=($Cliente['id_cliente']==$Id_Cliente)?' selected="selected"':''?>><?=$Cliente['codigo_cliente']?> - <?=$Cliente['nombre']?></option>
<?php
}
?>
							</select>
						</td>
					</tr>
				</table>
				
			</td>
			<td>
				
				<table>
					<tr>
						<td>
							<input type="radio" name="trabajo" id="trabajo1" value="finalizado"<?=('finalizado'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo1">Terminados</label>
							<input type="radio" name="trabajo" id="trabajo2" value="incompleto"<?=('incompleto'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo2">Inconclusos</label>
							<br />
							<input type="radio" name="trabajo" id="trabajo3" value="atrasado"<?=('atrasado'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo3">Atrasados</label>
							<input type="radio" name="trabajo" id="trabajo4" value="reproceso"<?=('reproceso'==$Trabajo)?' checked="checked"':''?> />
							<label for="trabajo4">Reproceso</label>
							<select name='bus_material' id='bus_material'>
								<option value=''></option>
<?php
foreach($bus_materiales as $Datos)
{
?>
								<option value='<?=$Datos['id_material_solicitado']?>' <?=($Id_material==$Datos['id_material_solicitado'])?' selected="selected"':''?>><?=$Datos['material_solicitado']?></option>
<?php
}
?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
					</tr>
				</table>
				
<?php
if(
	'Sistemas' == $this->session->userdata('codigo')
	//|| 'Gerencia' == $this->session->userdata('codigo')
)
{
?>
				
				<br /><br />
				<input type="button" value="Modificar Prioridades" id="guar_prior" />
<?php
}
?>
				<input type="submit" class="btn_bonito" value="Crear Reporte" />
			</td>
		</tr>
		<tr>
			<td>
<?php
$Avanzar = array();
foreach($Usuarios as $Dpto_Usuarios)
{
	if(
		'Gerente de Grupo' != $Dpto_Usuarios['dpto']
		&& 'Sistemas Inform&aacute;ticos' != $Dpto_Usuarios['dpto']
	)
	{
		foreach($Dpto_Usuarios['usuarios'] as $Datos => $Datos_usuario)
		{
			$Avanzar[$Datos] = $Datos;
		}
	}
}

			$puesto_ant = 'todos';
			$puesto_sig = '';
		$bandera = 0;
		foreach($Avanzar as $ide_usu_puesto)
		{
			if($bandera == 1 or $Puesto == 'todos')
			{
				$puesto_sig = $ide_usu_puesto;
				break;
			}
			if($ide_usu_puesto == $Puesto)
			{
				$bandera = 1;
			}
			if($bandera == 0)
			{
				$puesto_ant = $ide_usu_puesto;
			}
		}
?>
				<span id='puesto_ant-<?=$puesto_ant?>' name='puesto_anterior' class='span_carga' title='Cargar puesto anterior'><strong>&laquo; Anterior</strong></span> &nbsp; &nbsp;
				<span id='puesto_sig-<?=$puesto_sig?>' name='puesto_siguiente' class='span_carga' title='Cargar puesto siguiente'><strong>Siguiente &raquo;</strong></span>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
if(isset($Carga['trabajos']))
{
?>
				<label style="text-align: right;">Total de Trabajos: <strong><?=count($Carga['trabajos'])?></strong></label>
<?php
}
?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td></td>
		</tr>
	</table>
	
</form>


<?php
if($Mostar_Datos)
{
?>

<br />

<div>
<table class="tabular" style="width: 100%;" id="ordena_tabla">
	<tr class="no_ordena">
		<th style="width: 20px;"></th>
		<th style="width: 130px;">Proceso</th>
		<th style="width: 330px;">Producto</th>
		<th style="width: 90px;">Ingreso</th>
		<th style="width: 90px;">Estimada</th>
		<?php
if('finalizado' == $Trabajo)
{
?><th style="width: 90px;">Real</th><?php
}
?>
		<th>Ruta</th>
	</tr>
<?php
$a=0;
	foreach($Carga['trabajos'] as $Detalle)
	{
		if(isset($Carga['Fechas'][$Detalle['id_pedido']]))
		{
			$Detalle['entre'] = $Carga['Fechas'][$Detalle['id_pedido']];
		}
		
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
	<tr id="tr_ca_<?=$Detalle['id_pedido']?>" class='mover'>
		<td>
<?php
	if($Detalle['url'] != '')
	{
?>
			<a href="<?=$Detalle['url']?>" class="thickbox" title='' >
				<img width='30px' height='25px' src="<?=$Detalle['url']?>" title="<?=$Detalle['nombre_adjunto']?>" />
			</a>
<?php
	}
	else
	{
?>
			<a href="javascript:ver_agregar_scan('<?=$Detalle['id_proceso']?>-imagen_proceso');" class="iconos imas toolizq"><span>Agregar Miniatura</span></a>
<?php
	}
?>
		</td>
		<td>
			<a href="javascript:ventana_externa('/pedidos/especificacion/ver/<?=$Detalle['id_pedido']?>/n');" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>
			<a href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$Detalle['id_pedido']?>');" class="toolizq"><span>Ver detalle</span>
				<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>
			</a><?=(4==$Detalle['tipo'])?' *':''?>
			
		</td>
		<td>
			
<?php
if(
	'finalizado' != $Trabajo
	&& 'Ventas' != $this->session->userdata('codigo')
)
{
	if(
		'Sistemas' == $this->session->userdata('codigo')
		|| 'Plani' == $this->session->userdata('codigo')
		|| 'Gerencia' == $this->session->userdata('codigo')
	)
	{
?>
			<a href="javascript:ver_cambiar_fecha('<?=$Detalle['id_pedido']?>', '<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>', '<?=$Detalle['entre'][2].'-'.$Detalle['entre'][1].'-'.$Detalle['entre'][0]?>');" class="iconos icalendario toolder"><span>Cambiar Fecha de Entrega</span></a>
<?php
	}
?>
			<a href="javascript:finalizar_trabajo('<?=$Detalle['id_pedido']?>', '<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>');" class="iconos iterminado toolder"><span>Dar por Terminado</span></a>
<?php
}
?>
			
			<?=$Detalle['prod']?>
		
	<?php
	$tiempo_usuario = '';
	if($Puesto != 'todos' && 'finalizado' != $Trabajo)
	{
		if(isset($Carga['tiempo'][$Detalle['id_pedido']]))
		{
			foreach($Carga['tiempo'][$Detalle['id_pedido']] as $Tie => $Datos)
			{
				if(isset($Datos['tiempo']))
				{
					$tiempo_usuario = $Datos['tiempo'];
				}
				
				if('' == $tiempo_usuario)
				{
					$tiempo_usuario = 0;
				}
			
				//Es posible que este trabajo tenga un tiempo que este corriendo en este momento.
				//Debo saber cuanto es
				if(isset($Datos['inicio']))
				{
					$tiempo_inicio = $Datos['inicio'];
					//Debo saber cuantas horas tardo en realizarse este trabajo
					$f_i = $this->fechas_m->fecha_subdiv($tiempo_inicio);
					$f_f = $this->fechas_m->fecha_subdiv(date('Y-m-d H:i:s'));
					$segundos = mktime($f_f['hora'],$f_f['minuto'],0,$f_f['mes'],$f_f['dia'],$f_f['anho']) - mktime($f_i['hora'],$f_i['minuto'],0,$f_i['mes'],$f_i['dia'],$f_i['anho']);
					$horas = $segundos/60;
					//echo $horas;
					$tiempo_usuario += $horas;
				}
			
				//echo $tiempo_usuario;
				if(0 == $tiempo_usuario)
				{
					$tiempo_usuario = '0.0';
				}
				
				$tiempo_util = $Detalle['tiempo_asignado'] - $tiempo_usuario;
				
				$menos = '';
				if($tiempo_util < 0)
				{
					$tiempo_util = '0:00';
				}
	
				if($Detalle['tiempo_asignado'] != 0)
				{
					$horas = $this->fechas_m->minutos_a_hora($Detalle['tiempo_asignado']);
				}
				else
				{
					$horas = '00:00';
				}
?>
				<span id="tu-<?=$Detalle["id_peus"]?>" class="toolizq">
					[<?=$this->fechas_m->minutos_a_hora($tiempo_util)?> h]
					<span style="text-align: left;">
						Tiempo Programado: <?=$horas?> h
						<br />Tiempo utilizado: <?=$this->fechas_m->minutos_a_hora($tiempo_usuario)?> h
						<br />Tiempo restante: <?=$this->fechas_m->minutos_a_hora($tiempo_util)?> h
					</span>
				</span>
				<a href="javascript:tiempo_asignado('<?=$Detalle["id_peus"]?>', '<?=$tiempo_usuario?>', '<?=$Detalle['id_usuario']?>')" class="tiempo_asignado">
					<span id="tp-<?=$Detalle["id_peus"]?>" class="toolizq">[<?=$horas?> h]<span>Cambiar tiempo Programado</span></span>
				</a>
				
<?php
			}
		}
	}
		?>
		</td>
		<td><?=$Detalle['entra'][2].'-'.$Detalle['entra'][1].'-'.$Detalle['entra'][0]?></td>
		<td class="<?=$Estado_Fecha?>"><?=$Detalle['entre'][2].'-'.$Detalle['entre'][1].'-'.$Detalle['entre'][0]?></td>
		<?php
		if('finalizado' == $Trabajo)
		{
?><td><?=$Detalle['reale'][2].'-'.$Detalle['reale'][1].'-'.$Detalle['reale'][0]?></td><?php
		}
?>
		<!--td style="clear: both;"></td-->
		
		<td>
<?php
		if(isset($Carga['ruta'][$Detalle['id_pedido']]))
		{
			foreach($Carga['ruta'][$Detalle['id_pedido']] as $Ruta)
			{
				if(('GR' == $Ruta['ini'] && 'Asignado' == $Ruta['est']))
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
?><span class="rut_<?=$Ruta2['est']?> toolder"><?=$Ruta2['ini']?><span><?=$Ruta2['usu']?>: <?=$Ruta2['est']?><br />Fecha Finalizado: <?=$Ruta2['fin']?></span></span><?php
						}
						
					}
				}
				else
				{
?><span class="rut_<?=$Ruta['est']?> toolder"><?=$Ruta['ini']?><span><?=$Ruta['usu']?>: <?=$Ruta['est']?><br />Fecha Finalizado: <?=$Ruta['fin']?></span></span><?php
				}
			}
		}
?>
		</td>
	</tr>
<?php
	$a++;
	}
	
	
	
if('Flexo' != $Id_Cliente)
{
	if(isset($Carga_Grupos))
	{
		/*print_r($Carga['enlaces']);
		print_r($Carga_Grupos['enlaces']);*/
		foreach($Carga_Grupos['trabajos'] as $Detalle)
		{
			
			if(isset($Carga_Grupos['enlaces'][0][$Detalle['id_pedido']]))
			{
				if(
					isset($Carga['trabajos'][$Carga_Grupos['enlaces'][0][$Detalle['id_pedido']]])
				)
				{
					continue;
				}
			}
			
			
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
		<td>&nbsp;</td>
		<td>
			<a href="javascript:ventana_externa('/pedidos/especificacion/ver/<?=$Detalle['id_pedido']?>/n');" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>
			<a href="javascript:ventana_externa('/pedidos/pedido_detalle/index/<?=$Detalle['id_pedido']?>');" class="toolizq"><span>Ver detalle</span>
				<?=$Detalle['codcl']?>-<?=$Detalle['proce']?>
			</a><?=(4==$Detalle['tipo'])?' *':''?>
			
		</td>
		<td><?=$Detalle['prod']?></td>
		<td><?=$Detalle['entra'][2].'-'.$Detalle['entra'][1].'-'.$Detalle['entra'][0]?></td>
		<td class="<?=$Estado_Fecha?>"><?=$Detalle['entre'][2].'-'.$Detalle['entre'][1].'-'.$Detalle['entre'][0]?></td>
		<?php
			if('finalizado' == $Trabajo)
			{
?><td><?=$Detalle['reale'][2].'-'.$Detalle['reale'][1].'-'.$Detalle['reale'][0]?></td><?php
			}
?>
		<!--td style="clear: both;"></td-->
		<!--td>&nbsp;</td-->
		<td>
<?php
			foreach($Carga_Grupos['ruta'][$Detalle['id_pedido']] as $Ruta)
			{
				if('GR' != $Ruta['ini'])
				{
?>
			<span class="rut_<?=$Ruta['est']?> toolder"><?=$Ruta['ini']?><span><?=$Ruta['usu']?>: <?=$Ruta['est']?><br />Fecha Finalizado: <?=$Ruta['fin']?></span></span>
<?php
				}
			}
?>
		</td>
	</tr>
<?php
		}
	}
}
?>
</table>


</div>
<?php
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



<?php
$this->load->view('/scan/cargar_scan_v', $num_cajas);
?>


<script>
	$(function(){
		$("[name=fecha_entrega]").datepicker({dateFormat: 'dd-mm-yy', showButtonPanel: true});
	});
	$('#dia1').focus();
	
	
	
<?php

if(
	'Sistemas' == $this->session->userdata('codigo')
	|| 'Gerencia' == $this->session->userdata('codigo')
)
{
?>

	
	
	$('#guar_prior').click(function()
	{
		var valor = $('#guar_prior').val();
		if('Guardar Prioridades' != valor)
		{
			$('#guar_prior').val('Guardar Prioridades');
			$('#ordena_tabla').sortable({
						items: 'tr:not(.no_ordena)',
						placeholder: "sortable-placeholder",
					}).disableSelection();
			$('.mover').click(function()
			{
				$(this).css({'font-weight':'700'});
			});
		}
		else
		{
			var pedidos = []
			$('#ordena_tabla tr').each(function()
			{
				if('no_ordena' != $(this).attr('class'))
				{
					var ide_teere = $(this).attr('id');
					ide_teere = ide_teere.split('_');
					pedidos.push(ide_teere[2]);
				}
			});
			
		
			$.ajax({
				type: "POST",
				url: "/carga/orden_prioridad",
				data: 'prioridades='+JSON.stringify(pedidos),
				success: function(msg)
				{
					if('ok' == msg)
					{
						alert('Las prioridades fueron aplicadas.');
					}
					else
					{
						alert('Ocurrio un error.');
					}
				},
				error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
			});
		
		}
		
	});
	
	
<?php
}
?>
	
	
</script>

<style>
	.sortable-placeholder{
		width: 500px;
		height: 70px;
		background: #000000;
	}
</style>
