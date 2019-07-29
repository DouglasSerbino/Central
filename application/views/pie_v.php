		
		</div>



<script type="text/javascript" src="/html/js/administracion.js?n=1"></script>
<style>
	#m_msj {
		text-align: left;
		position: absolute;
		left: 787px;
		width: 200px;
		height: 150px;
		bottom: 10px;
		padding: 0px;
		background-color: #dde4ff;
		overflow: scroll;
		display: none;
	}
	.posicion_div
	{
		background: #aaccaa;
		position: fixed;
		right: 170px;
		font-weight: bold;
		bottom: 42px;
		height: 25px;
		width: 400px;
		text-align: center;
	}
	.scroll
	{
		margin: 10px auto;
		height:100px;
		overflow: scroll;
		width:200px;
	}
	#actu-pendiente, #actu-finalizado{
		top: 192px;
		width: 700px;
		display: none;
		padding: 7px 12px;
		margin-left: 125px;
		position: absolute;
		background: #ffffff;
		border: 1px solid #F8C773;
	}
	#actu-pendiente ol, #actu-finalizado ol{
		margin-left: 10px;
	}
	#actu-pendiente li, #actu-finalizado li{
		list-style: circle;
	}
	#actu-pendiente span, #actu-finalizado span{
		padding: 0px 7px;
		display: block;
	}
	#pie_tempo .iconos{
		background: url('/html/img/temporizador.jpg');
	}
</style>		
		<div id="atrapa"></div>
<?php
$usuario = explode(' ', $this->session->userdata('nombre'));
?>
		<input type='hidden' name='id_usuario' id='id_usuario' value='<?=$usuario[0]?>' />
		<br style="clear:both;" /><br /><br />
		
		<div id='credito' class="credito">
		
<?php
if('' == $this->session->userdata('id_cliente'))
{
?>
			<a href="/carga/puestos/usuario/<?=$this->session->userdata('id_usuario')?>/<?=date('Y')?>/<?=date('m')?>" id="pie_rech" class="toolder tooltip_top"><strong>0</strong><cite class="iconos irechazar"></cite><span>Rechazos Recibidos</span></a>
<?php
	
	$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '', '410' => '');
	if(isset($Permitido[$this->session->userdata('codigo')]))
	{
?>
			<a href="/herramientas_sis/curiosos" id="pie_curi" class="toolder tooltip_top"><strong>0</strong><cite class="iconos ialerta"></cite><span>Pedidos Curiosos</span></a>
			<a href="/pedidos/preingreso/estado/Pendientes" id="pie_vent" class="toolder tooltip_top"<?=('410'==$this->session->userdata('codigo'))?' style="display:none;"':''?>><strong>0</strong><cite class="iconos idocumento"></cite><span>Pedidos pendientes en Ventas</span></a>
			<a href="/pedidos/preingreso/estado/Pendientes" id="pie_plan" class="toolder tooltip_top"<?=('410'==$this->session->userdata('codigo'))?' style="display:none;"':''?>><strong>0</strong><cite class="iconos iruta"></cite><span>Pedidos pendientes en Planificaci&oacute;n</span></a>
<?php
	}
}
?>
			<span id="pie_tempo"><cite class="iconos"></cite></span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
		
		
		
		
	</div>

<?php
if('SAP' == $this->session->userdata('codigo'))
{
?>
	<div id='div_oculto' class='posicion_div'>
		Cotizaciones (<a href='/herramientas_sis/modi_coti'><?=$this->session->userdata('modi_coti')?></a>)
	</div>
<?php
}
?>
	
<?php
if('plani' == $this->session->userdata('codigo') and $this->session->userdata('sol_pedido') != '')
{
?>
	<div id='div_oculto' class='posicion_div'>
		Cotizaciones (<a href='/herramientas_sis/modi_coti'><?=$this->session->userdata('modi_coti')?></a>)
		Solicitud de Material (<a href='/herramientas_sis/lsol_pedido'><?=(0 != $this->session->userdata('sol_pedido')?$this->session->userdata('sol_pedido'):'0')?></a>)
	</div>
	
<?php
}
?>

<?php
if('Sistemas' == $this->session->userdata('codigo') and $this->session->userdata('sol_cambio') != '')
{
?>
	<div id='div_oculto' class='posicion_div'>
		Cambios de Fecha(<a href='/herramientas_sis/cambio_fecha'><?=(0 != $this->session->userdata('sol_cambio')?$this->session->userdata('sol_cambio'):'0')?></a>)
	</div>
<?php
}
?>

<?php
if('SAP' == $this->session->userdata('codigo') and $this->session->userdata('modi_coti') != '')
{
?>
	<div id='div_oculto' class='posicion_div'>
		Cotizaciones (<a href='/herramientas_sis/modi_coti'><?=$this->session->userdata('modi_coti')?></a>)
	</div>
<?php
}
?>



<style type="text/css" media="print">
	#logo_grupo,
	#banner_img,
	#navegacion,
	#menu-lateral,
	#nombre_usuario,
	#imprimir,
	#manto,
	#credito,
	#reporte_semanal,
	.no_imprime{
		display: none;
	}
	#contenedor{
		width: 100%;
		border: none;
		line-height: 16pt;
	}
	#encabezado_ocul,
	.solo_print{
		display: block;
	}
	#cont-pagina{
		display: inline;
	}
	[type="text"]{
		padding: 0px;
		margin: 0px;
		border: none;
		border-bottom: 1px solid #000000;
	}
	.info_cuerpo{
		width: 100%;
	}
	.imprime_al_lado{
		width: 47%;
		float: left;
	}
	.imprime_al_lado textarea{
		width: 400px;
		height: 100px;
	}
	.imprime_al_lado th, .imprime_al_lado td{
		width: 1px;
    white-space: nowrap;
  }
  .plani_tablas td, .plani_tablas th{
    padding-top: 0px;
    padding-bottom: 0px;
  	height: 10px;
  }
  .plani_tablas input{
    margin: 0px;
  }
	[type="button"]{
		display: none;
	}
</style>

<script>
	function cerrar_sesion()
	{
		window.location = '/salir';
	}

	var Tiempo_Posicion = 0;
	function mover_temporizador()
	{

		$.get('/pie/temporizador', function(tiempo)
		{
			if(tiempo.status == 302)
			{
				window.location.href = '/inicio';
			}
			
			var tiempo = parseInt(tiempo) * 1000;
			Tiempo_Posicion = Math.floor((tiempo / Tiempo_Espera_SS) * 100);
			Tiempo_Posicion = Math.round(Tiempo_Posicion / 20) * -20;
			$('#pie_tempo .iconos').css('background-position', Tiempo_Posicion+'px 0px');
			if(tiempo > Tiempo_Espera_SS)
			{
				cerrar_sesion();
			}
		});
		
	}

	var Tiempo_Espera_SS = 3600000;
	mover_temporizador();
	setInterval(mover_temporizador, (Tiempo_Espera_SS / 6.3));
</script>

</body>

</html>
