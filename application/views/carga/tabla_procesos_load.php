<!-- INICIO DE LA TABLA -->
                <div id="contenido_tabla">
                    <?php
                    if($Mostar_Datos)
                    {
                    ?>
                        <br />
                   
                    <table class="tabular table table-hover table-bordered" style="width: 100%;" id="ordena_tabla">
                        <thead>
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
                        </thead>
                        <tbody>
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
                    </tbody>
                    </table>

                    <?php
                        }
                    ?>
                </div>
                     <!-- FIN DE LA TABLA -->