
<script type="text/javascript" src="/html/js/carga.js?n=1"></script>
<script type="text/javascript" src="/html/js/thickbox.js"></script>
<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<!-- <script src="/html/js/jquery.min.js"></script>  -->
<!-- <script src="/html/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="/html/css/pedido.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script> -->
<!-- <script src="/html/js/jquery.counterup.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="/html/css/datatable.min.css">
<script type="text/javascript" src="/html/js/datatable.min.js"></script>
<!-- <script type="text/javascript" src="/html/js/jquery.flot.js"></script> -->
<!-- <script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.flot/0.8.3/jquery.flot.min.js"></script> -->
<!-- <script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script> -->
<!-- Atencion: Estos estilos contraatacan los estilos generales, porque todo tiene tamanho especial -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script src="/html/js/cbpFWTabs.js"></script>
<link href="/html/css/style.css" rel="stylesheet">

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

<div class="container">
<div class="col-md-12">
<!--INICIO NUEVA SECCION-->
<tr>


<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group form-inline">
                    <select id="departamento_consulta" class="form-control" style="width: 70px;">
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

                <select name="mes_consulta" id="mes_consulta" class="form-control">
                    <?php
                        foreach($Meses as $Mes => $MNombre)
                        {
                    ?>
                        <option value="<?=$Mes?>"<?=($Mes==$Fechas['mes1'])?' selected="selected"':''?>><?=$MNombre?></option>
                    <?php
                        }
                    ?>
                </select>

                 <input type="text" class="form-control" name="ano_consulta" id="ano_consulta" size="8" value="<?=$Fechas['anho1']?>" />
          

                <button id="informacion" class="btn btn-sm form-control btn-danger" onclick="cargarInfo();">Ver Reporte</button>
            </div>

            


            <!-- <select name="mes_consulta" id="mes_consulta">
                <?php
                    foreach($Meses as $Mes => $MNombre)
                    {
                ?>
                    <option value="<?=$Mes?>"<?=($Mes==$Fechas['mes1'])?' selected="selected"':''?>><?=$MNombre?></option>
                <?php
                    }
                ?>
            </select> -->
<!-- 
            <input type="text" name="ano_consulta" id="ano_consulta" size="8" value="<?=$Fechas['anho1']?>" />
          

            <button id="informacion" class="btn btn-sm btn-default" onclick="cargarInfo();">Ver Reporte</button> -->

            <!-- <div class="clearfix"></div>  style="display: none;"-->

     
        </div>  

        <div id="formulario_filtros">
            <div class="col-md-7 form-group form-inline">
                <form action="/carga/seguimiento" method="post" name="miform" id="formseguimiento">
                    <!-- <div class="input-group"> -->
                        <label>Comienzo</label>
                        <input class="form-control" type="text" name="dia1" id="dia1" size="2" value="<?=$Fechas['dia1']?>" />
                        <select class="form-control" name="mes1" id="mes1">
                            <?php
                            foreach($Meses as $Mes => $MNombre)
                            {
                                ?>
                                <option value="<?=$Mes?>"<?=($Mes==$Fechas['mes1'])?' selected="selected"':''?>><?=$MNombre?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input class="form-control" type="text" name="anho1" id="anho1" size="4" value="<?=$Fechas['anho1']?>" />

                       <div class="form-group pull-right" id="grupo_radios">
                           <div class="input-group">
                                <span class="input-group-addon"> 
                                    <input type="radio" name="trabajo" id="trabajo1" class="pull-right" value="finalizado"<?=('finalizado'==$Trabajo)?' checked="checked"':''?> />
                                </span>
                                <label for="trabajo1" class="pull-right form-control">Terminados</label>
                           </div>

                           <div class="input-group">
                                <span class="input-group-addon">  
                                    <input type="radio" name="trabajo" id="trabajo2" class="pull-right" value="incompleto"<?=('incompleto'==$Trabajo)?' checked="checked"':''?> />
                                </span>
                                <label for="trabajo2" class="pull-right form-control">Inconclusos</label>
                           </div>
                       </div>

                      <!--   <label for="trabajo2" class="pull-right">Inconclusos</label>
                        <input type="radio" name="trabajo" id="trabajo2" class="pull-right" value="incompleto"<?=('incompleto'==$Trabajo)?' checked="checked"':''?> />
                         -->
                        <!-- <label for="trabajo1" class="pull-right">Terminados</label>
                        <input type="radio" name="trabajo" id="trabajo1" class="pull-right" value="finalizado"<?=('finalizado'==$Trabajo)?' checked="checked"':''?> />
 -->
                        <br>
                        <br>

                        <label>Finalizaci&oacute;n</label>
                        <input class="form-control" type="text" name="dia2" id="dia2" size="2" value="<?=$Fechas['dia2']?>" />
                        <select class="form-control" name="mes2" id="mes2">
                            <?php
                            foreach($Meses as $Mes => $MNombre)
                            {
                                ?>
                                <option value="<?=$Mes?>"<?=($Mes==$Fechas['mes2'])?' selected="selected"':''?>><?=$MNombre?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input class="form-control" type="text" name="anho2" id="anho2" size="4" value="<?=$Fechas['anho2']?>" />
                        
                        <div class="form-group pull-right" id="grupo_radios">
                           <div class="input-group">
                                <span class="input-group-addon"> 
                                    <input type="radio" name="trabajo" id="trabajo3" class="pull-right" value="atrasado"<?=('atrasado'==$Trabajo)?' checked="checked"':''?> />
                                </span>
                                <label for="trabajo3" class="pull-right form-control">Atrasados</label>
                           </div>

                           <div class="input-group">
                                <span class="input-group-addon"> 
                                    <input type="radio" name="trabajo" id="trabajo4" class="pull-right" value="reproceso"<?=('reproceso'==$Trabajo)?' checked="checked"':''?> />
                                </span>
                                <label for="trabajo4" class="pull-right form-control">Reproceso</label>
                           </div>
                       </div>
                        <!-- <label for="trabajo4" class="pull-right">Reproceso</label>
                        <input type="radio" name="trabajo" id="trabajo4" class="pull-right" value="reproceso"<?=('reproceso'==$Trabajo)?' checked="checked"':''?> />

                        <label for="trabajo3" class="pull-right">Atrasados</label>
                        <input type="radio" name="trabajo" id="trabajo3" class="pull-right" value="atrasado"<?=('atrasado'==$Trabajo)?' checked="checked"':''?> />
 -->
                        <br>
                        <br>

                        <label>Pa&iacute;s</label>
                        <select class="form-control" name="pais_c" id="pais_c">
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

                        <select name='bus_material' id='bus_material' class="pull-right form-control">
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

                        <br>
                        <br>

                        <label>&Aacute;rea</label>
                        <select class="form-control" name="puesto" id="puesto">
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

                        <input type="button" class="btn_bonito pull-right " value="Crear Reporte" />  
                        <!-- onclick="mostrar_li();" -->


                        <?php
                        if(
                            'Sistemas' == $this->session->userdata('codigo')//|| 'Gerencia' == $this->session->userdata('codigo')
                        )
                        {
                            ?>
                            <input type="button" value="Modificar Prioridades" id="guar_prior" class="pull-right form-control"/>
                            <?php
                        }
                        ?>

                        <br>
                        <br>

                        <label>Cliente</label>
                        <input class="form-control" type="hidden" name="cliente_tipo" id="cliente_tipo" value="todos" />
                        <select class="form-control" name="cliente" id="cliente">
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
                    <!-- </div> -->
                </form>            
            </div>     
          
        </div>
    </div>
  </td>
</tr>
<!--FIN NUEVA SECCION-->
</div>
<br>
<br>



   <!-- codigo original-->
<!-- <div id="formulario_filtros" class="col-md-6" style="display: none;"> -->
<!--      <form action="/carga/seguimiento" method="post" name="miform" id="formseguimiento">
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


                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
            </tr>
        </table>

    </form>
</div> -->

</div>
	<section>
        <div class="sttabs tabs-style-bar">
            <nav>
                <ul>
                    <li><a href="#section-bar-1" class="sticon ti-home"><span class="">INFOGRAFIAS</span></a></li>
                    <li><a href="#section-bar-2" class="sticon ti-trash"><span>ESTADISTICAS</span></a></li>
                    <li style="display: none" id="li_tabla"><a href="#section-bar-3" class="sticon ti-stats-up"><span>TABLAS</span></a></li>
                   <!--  <li><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                    <li><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                </ul>
            </nav>
            <div class="content-wrap">
                <section id="section-bar-1">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="pull-right"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a> <a href="#" data-perform="panel-dismiss"><i class="ti-close"></i></a> </div>
                                </div>
                                <div class="panel-wrapper collapse in" aria-expanded="true">
                                    <div class="panel-body">
                                    	<h3 class="text-center"><img src="/html/img/icons/operadores.png"><span class="counter" id="operadores_num">0</span></h3>
                                    	<h5 class="text-center">Operadores</h5>
                                    </div>
                                    <button class="btn panel-info btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="collapseExample">
        								   <div class="card card-body">
        								   	<ul id="listado_operadores" class="list-group">
        								   		
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
                                    	<h3 class="text-center"><img src="/html/img/icons/trabajos.png"><span id="trabajos_num" class="counter"> 0</span></h3>
                                    	<h5 class="text-center">Promedio de trabajos realizados</h5>
                                    </div>

                                    <button class="btn panel-warning btn-sm btn-block" type="button" data-toggle="modal" data-target="#exampleModal">
                                    	 	 <i class="material-icons">expand_more</i>
                                    </button>

                                    <div class="collapse" id="collapseExample">
        								   <div class="card card-body">
        									   	
        								   
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
                                    	<h3 class="text-center"><img src="/html/img/icons/rechazos.png"><span id="rechazos_num" class="counter">0</span></h3>
                                    	<h5 class="text-center">Promedio de rechazos</h5>
                                    </div>
                                    <button class="btn panel-danger btn-sm btn-block" type="button" data-toggle="collapse" data-target="#" aria-expanded="false" aria-controls="">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="">
        								   <div class="card card-body">
        								   	
        								   
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
                                    	<h3 class="text-center"><img src="/html/img/icons/extras.png"><span id="extras_num" class="counter">0</span></h3>
                                    	<h5 class="text-center">Horas extras realizadas</h5>
                                    </div>
                                    <button class="btn panel-success btn-sm btn-block" type="button" data-toggle="collapse" data-target="#" aria-expanded="false" aria-controls="">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="">
        								   <div class="card card-body">
        								   	
        								   
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
                                    	<h3 class="text-center"><img src="/html/img/icons/metaprod.png"><span id="esperado_num" class="counter" >0</span>%</h3>
                                    	<h5 class="text-center">Meta: Indice global de productividad</h5>
                                    </div>
                                    <button class="btn panel-info btn-sm btn-block" type="button" data-toggle="collapse" data-target="#" aria-expanded="false" aria-controls="">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="">
        								   <div class="card card-body">
        								   	
        								   
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
                                    	<h3 class="text-center"><img src="/html/img/icons/realprod.png"><span id="real_num" class="counter">0</span>%</h3>
                                    	<h5 class="text-center">Real: Porcentaje de productividad</h5>
                                    </div>
                                    <button class="btn panel-success btn-sm btn-block" type="button" data-toggle="collapse" data-target="#" aria-expanded="false" aria-controls="">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="">
        								   <div class="card card-body">
        								   	
        								   
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
                                    	<h3 class="text-center"><img src="/html/img/icons/utilizadas.png"><span id="utilizadas_num" class="counter">0</span></h3>
                                    	<h5 class="text-center">Promedio de horas utilizadas</h5>
                                    </div>
                                    <button class="btn panel-warning btn-sm btn-block" type="button" data-toggle="collapse" data-target="#" aria-expanded="false" aria-controls="">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="">
        								   <div class="card card-body">
        								   	
        								   
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
                                    	<h3 class="text-center"><img src="/html/img/icons/disponibles.png"><span id="disponibles_num" class="counter">0</span></h3>
                                    	<h5 class="text-center">Promedio de horas disponibles</h5>
                                    </div>
                                    <button class="btn panel-danger btn-sm btn-block" type="button" data-toggle="collapse" data-target="#" aria-expanded="false" aria-controls="">
                                    	 	 <i class="material-icons">expand_more</i>
                                    	 </button>

                                    	<div class="collapse" id="">
        								   <div class="card card-body">
        								   
        								  </div>
        								</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="section-bar-2">
				    <canvas id="grafico-linea" style="width:800px;height:300px;"></canvas>
				</section>

                <section id="section-bar-3">

                    <!-- style="display: none;" id="li_tabla" -->
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
                            {?>
                                <th style="width: 90px;">Real</th><?php
                            }?>
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
                                            date('Y-m-d').' 00:00:01')
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
                                            $Detalle['reale'].' 00:00:01'))
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
                            {?>
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



                </section>
                <!-- <section id="section-bar-4">
                    <h2>Tabbing 4</h2></section>
                <section id="section-bar-5">
                    <h2>Tabbing 5</h2></section> -->
            </div>
            <!-- /content -->
        </div>
	    <!-- /tabs -->
	</section>


               



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

<?php
if($Mostar_Datos)
{
?>

<br />

 
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


<!-- MODAL -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Trabajos Realizados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<table id="trabajos_list" class="table table-borderless table-condensed">
      		<thead>
      				<th>Proceso</th>
      				<th>Trabajo</th>
      				<th>Ingreso</th>
      				<th>Entrega</th>
      				<th>Entregado</th>
      		</thead>
      		<tbody>
      			
      		</tbody>
      		<!-- <tr>
      			<th>Nombre del producto: </th>
      			<th><input type="text" name="nombre_producto" id="nombre_producto_nuevo"></th>
      		</tr> -->
      	</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->



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

<script type="text/javascript">
	function contador(){
	  var counters = $(".counter");
	  var countersQuantity = counters.length;
	  var counter = [];

	  for (i = 0; i < countersQuantity; i++) {
	    counter[i] = parseInt(counters[i].innerHTML);
	  }

	  var count = function(start, value, id) {
	    var localStart = start;
	    setInterval(function() {
	      if (localStart < value) {
	        localStart++;
	        counters[id].innerHTML = localStart;
	      }
	    }, -1);
	  }

	  for (j = 0; j < countersQuantity; j++) {
	    count(0, counter[j], j);
	  }
	}
</script>
<script type="text/javascript">
	function cargarInfo(){
		operadores_nombres = [];
		numero_rechazos = [];
		productividad_real = [];
		productividad_esperada = [];
		$('#listado_operadores').empty();
		$.ajax({
			url: '/carga/seguimiento/obtenerDatos',
			type: 'POST',
			data: {'departamento': $('#departamento_consulta').val(),
				   'mes': $('#mes_consulta').val(),
				   'ano': $('#ano_consulta').val().trim()
			},
			success : function(response){
				$('#operadores_num').text(response.operadores.length);
				$('#trabajos_num').text(response.trabajos.length);
				$('#rechazos_num').text(response.rechazos.length);
				$('#extras_num').text(response.extras[0]["total_h"]);


				//contador();
				for (var i = 0; i < response.operadores.length; i++) {
					$('#listado_operadores').append('<button type="button" class="list-group-item list-group-item-action" onclick="infoEmpleado('+response.operadores[i]["id_usuario"]+')" >'+ response.operadores[i]["nombre"] +'</button> ');
						operadores_nombres.push(response.operadores[i]["nombre"]);

						$.ajax({
						url: '/carga/seguimiento/obtenerDatosUsuario',
						type: 'POST',
						data: {'empleado': response.operadores[i]["id_usuario"] ,
							   'mes': $('#mes_consulta').val(),
							   'ano': $('#ano_consulta').val().trim()
							  },
						success : function(response){
						 numero_rechazos.push(response.rechazos.length);
						 productividad_esperada.push(85);
						 productividad_real.push((((response.utilizadas[0]["tiempo"]/60)*100)/190).toFixed(2));
						},
						error: function(msg){ alert("Ocurrio un Error"); }
						});
				}
				

				//Para el grafico
				let draw = Chart.controllers.line.prototype.draw;
				Chart.controllers.line = Chart.controllers.line.extend({
				    draw: function() {
				        draw.apply(this, arguments);
				        let ctx = this.chart.chart.ctx;
				        let _stroke = ctx.stroke;
				        ctx.stroke = function() {
				            ctx.save();
				            ctx.shadowColor = '#E56590';
				            ctx.shadowBlur = 10;
				            ctx.shadowOffsetX = 0;
				            ctx.shadowOffsetY = 4;
				            _stroke.apply(this, arguments)
				            ctx.restore();
				        }
				    }
				});
				let ctx = $('#grafico-linea')[0].getContext('2d');
				new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: operadores_nombres.toString().split(','),
				        datasets: [{
				            label: 'Productividad Real',
				            data: productividad_real,
				            backgroundColor: [
				                'rgba(255, 99, 132, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(255, 206, 86, 0.2)',
				                'rgba(75, 192, 192, 0.2)',
				                'rgba(153, 102, 255, 0.2)',
				                'rgba(255, 159, 64, 0.2)'
				            ],
				            borderColor: [
				                'rgba(255, 99, 132, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(255, 206, 86, 1)',
				                'rgba(75, 192, 192, 1)',
				                'rgba(153, 102, 255, 1)',
				                'rgba(255, 159, 64, 1)'
				            ],
				            borderWidth: 1
				        },{
				        	label: "Rechazos",
				            data: numero_rechazos,
				            type: 'line',
				            borderColor: 'rgba(0, 177, 106, 1)',
				        },{
				        	label: "Productividad Esperada",
				            data: productividad_esperada,
				            type: 'line',
				            borderColor: 'rgba(242, 121, 53, 1)',
				            pointBackgroundColor: "#fff",
				            pointBorderColor: "#ffb88c",
				            pointHoverBackgroundColor: "#ffb88c",
				            pointHoverBorderColor: "#fff",
				            pointRadius: 4,
				            pointHoverRadius: 4,
				            fill: false
				        }
				        ]
				    },
				    options: {

				        scales: {
				            yAxes: [{
				                ticks: 
				                {
				                    beginAtZero: true

				                }
				            }]

				        },
				        responsive: true,
				    title: {
				      display: true,
				      text: 'Tiempos y Rechazos'
				    },
    				tooltips: {
    				mode: 'index',
      				intersect: true
    				},
				    annotation: {
				      	annotations: [{
				        type: 'line',
				        mode: 'horizontal',
				        scaleID: 'y-axis-0',
				        value: 85,
				        borderColor: 'rgb(75, 192, 192)',
				        borderWidth: 10,
				        label: {
				          enabled: true,
				          content: 'Meta'
				        }
				      }]
				    }
				    }
				});

				
			},
			error: function(msg){ console.log("Ocurrio un Error"); }
		});
		
	}
</script>

<script>
	function infoEmpleado(id_empleado){
		$.ajax({
			url: '/carga/seguimiento/obtenerDatosUsuario',
			type: 'POST',
			data: {'empleado': id_empleado,
				   'mes': $('#mes_consulta').val(),
				   'ano': $('#ano_consulta').val().trim()
				  },
		success : function(response){
				$('#trabajos_num').text(response.trabajos.length);
				$('#rechazos_num').text(response.rechazos.length);
				response.extras[0]["total_h"] > 0 ? $('#extras_num').text(response.extras[0]["total_h"]) : $('#extras_num').text(0);
				$('#utilizadas_num').text((response.utilizadas[0]["tiempo"]/60).toFixed(2));
				$('#disponibles_num').text((133-(response.utilizadas[0]["tiempo"]/60)).toFixed(2));
				// $('#esperado_num,').text((((response.utilizadas[0]["tiempo"]/60)*100)/133).toFixed(2));
				$('#esperado_num,').text(85);
				$('#real_num').text((((response.utilizadas[0]["tiempo"]/60)*100)/190).toFixed(2));
                $('#formulario_filtros').show();
			},
			error: function(msg){ alert("Ocurrio un Error"); }
		});
	}
</script>

<script type="text/javascript">
    (function() {
        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function(el) {
            new CBPFWTabs(el);
        });
    })();
</script>

<!-- <script type="text/javascript">
    function mostrar_li(){
        $('#li_tabla').show();
    }
</script> -->

<!-- <script type="text/javascript">
    function mostrar_li(){
          $.ajax({
            url: '/carga/seguimiento',
            type:'POST',
            data:{'diaC':$('#dia1').val(),
                  'mesC':$('#mes1').val(),
                  'anoC':$('#anho1').val(),

                  'diaF':$('#dia2').val(),
                  'mesF':$('#mes2').val(),
                  'anoF':$('#anho2').val(),

                  'pais':$('#pais_c').val(),
                  'puesto':$('#puesto').val(),
                  'cliente':$('#cliente').val(),

                  'r_terminado':$('#trabajo1').val(),
                  'r_inconcluso':$('#trabajo2').val(),
                  'r_atrasados':$('#trabajo3').val(),
                  'r_reproceso': $('#trabajo4').val(),

                  'material': $('#bus_material').val()
                  },
        success : function(response){
            $('#li_tabla').show();
        },
            error: function(msg){ alert("Ocurrio un Error"); }
        });
</script> -->