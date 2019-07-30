
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

<style>
    .disabled_li {
    pointer-events:none; //This makes it not clickable
    opacity:0.6;         //This grays it out to look disabled
}
</style>

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

        <div style="display: none;" id="formulario_filtros">
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

                        <input type="button" class="btn_bonito pull-right " onclick="enviarInfoReporte();" value="Crear Reporte" />  
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
                    <li><a href="#section-bar-1" class="sticon"><span class=""><i class="material-icons">brush</i>INFOGRAFIAS</span></a></li>
                    <li id="li_estadisticas" class="disabled_li"><a href="#section-bar-2" class="sticon"><span><i class="material-icons">equalizer</i>ESTADISTICAS</span></a></li>
                    <li style="display: none" id="li_tabla"><a href="#section-bar-3" class="sticon"><span><i class="material-icons">list</i>TABLAS</span></a></li>
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
                                    	<h5 class="text-center">Total de trabajos realizados</h5>
                                    </div>

                                    <button class="btn panel-warning btn-sm btn-block" type="button">
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
                                    	<h5 class="text-center">Total de rechazos</h5>
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
                                    	<h5 class="text-center">Total de horas utilizadas</h5>
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
                                    	<h5 class="text-center">Total de horas disponibles</h5>
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
                    <div id="procesos">
                        
                    </div>
                </section> 
               
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
        $('#li_tabla').hide();
        $('#formulario_filtros').hide();
		$('#listado_operadores').empty();
        $('#li_estadisticas').removeClass('disabled_li');
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

<script>
function enviarInfoReporte(){
var frm = $('#formseguimiento');
    $.ajax({
        type: frm.attr('method'),
        url: '/carga/seguimiento/obtenerTabla',
        data: frm.serialize(),
        success: function (response) {
            $('#procesos').html(response.procesos);
            $('#li_tabla').show();
        },
        error: function (response) {
            alert('Ha ocurrido un error porfacor realice una captura de pantalla y envie al desarrollador pertinente');
            alert(response);
        },
    });
}
</script>
