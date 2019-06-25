

<!--
Analizar un planificador para fotopolimeros
-->


<script type="text/javascript" src="/html/js/carga.js?n=1"></script>

<style type="text/css">
	.tablero_listas{
		position: absolute;
		top: 150px;
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
		background: url(/html/img/line_agregado.png);
	}
	.line_Procesando{
		background: url(/html/img/line_procesando.png);
	}
	.line_Asignado, .line_Pausado{
		background: url(/html/img/line_asignado.png);
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
	.planchas
	{
		display: none;
	}
	#planchas
	{
		display: none;
	}
</style>


<input type="hidden" value="Aplicar Prioridades" onclick="guardar_prioridad()" />
<div id='deptos'>
<div class="tablero_listas" style="height: 780px;">
	<div style="width: 102px;border-right: 1px solid #999;height: 720px;"></div>
<?
$Tiempos = array(
	'08:00',
	'09:00',
	'10:00',
	'11:00',
	'01:00',
	'02:00',
	'03:00',
	'04:00',
);
$Dias = array(
	'Domingo',
	'Lunes',
	'Martes',
	'Mi&eacute;rcoles',
	'Jueves',
	'Viernes',
	'S&aacute;bado'
);


//Franjas para representar el ancho de los dias
$Contador2 = 0;
$Izquierda = 102;
$Horas_Tablero = 0;
for($i = 1; $i <= 8; $i++)
{
	$ancho = 449;
	if(
		6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))
	)
	{
		$ancho = 239;
		$Horas_Tablero += 4;
	}
	else
	{
		$Horas_Tablero += 8;
	}
	$Fondo = 'ffffff';
	if(0 == ($i % 2))
	{
		$Fondo = 'eeeeee';
	}
?>
		<div style="background:#<?=$Fondo?>;left:<?=$Izquierda?>px;width:<?=$ancho?>px;border-right: 1px solid #999;height: 720px;"></div>
<?
	$Izquierda += $ancho + 1;
	$Contador2++;
}


$Ancho_General = $Izquierda + 25;


//Franjas para representar el ancho de las horas
$Contador = 0;
$Contador2 = 0;
$Izquierda = 102;
for($i = 1; $i <= $Horas_Tablero; $i++)
{
	if(
		6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))
		&& '01:00' == $Tiempos[$Contador]
	)
	{
		$Contador = 0;
		$Contador2++;
	}
	$ancho = 59;
	if(7==$Contador)
	{
		$ancho = 29;
	}
?>
		<div style="top:20px;left:<?=$Izquierda?>px;width:<?=$ancho?>px;border-right: 1px solid #ccc;height: 700px;"></div>
<?
	$Izquierda += $ancho + 1;
	$Contador++;
	if(8 == $Contador)
	{
		$Contador = 0;
		$Contador2++;
	}
}
?>




</div>


<!-- Representacion de los trabajos -->
<div class="tablero_listas" style="width:<?=($Ancho_General+1500)?>px;">
	
	<ul class="tiempos">
		<li class="no_sortable"></li>
<?php
$Contador2 = 0;
for($i = 0; $i <= 7; $i++)
{
	if(0 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d')))))
	{
		$Contador2++;
	}
	$Ancho_Div = 450;
	if(6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d')))))
	{
		$Ancho_Div = 240;
	}
?>
		<li style="overflow: hidden;width: <?=$Ancho_Div?>px;text-align: center;"><strong><?=$Dias[date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))].' '.date('d-m-Y', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))?></strong></li>
<?
	$Contador2++;
}
?>
	</ul>
<?
foreach($Dpto_Usu as $Id_Dpto => $Dpto)
{
	if('n' == $Dpto['tiempo'] && 'SAP' != $Dpto['dpto'] && 'Despacho' != $Dpto['dpto'])
	{
		continue;
	}
?>
	<ul class="tiempos" style="z-index: 5;">
		<li class="no_sortable">&nbsp;</li>
<?


$Contador = 0;
$Contador2 = 0;
for($i = 1; $i <= $Horas_Tablero; $i++)
{
	if(
		6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))
		&& '01:00' == $Tiempos[$Contador]
	)
	{
		$Contador = 0;
		$Contador2++;
	}
?>
		<li style="width: <?=(7==$Contador)?'30':'60'?>px; text-align: center;"><strong><?=$Tiempos[$Contador]?></strong></li>
<?
	$Contador++;
	if(8 == $Contador)
	{
		$Contador = 0;
		$Contador2++;
	}
}
?>
	</ul>
<?
	//Listado de los trabajos
	foreach($Dpto['usuarios'] as $Id_Usuario => $Usuario)
	{
		if(9 != $Id_Dpto)
		{
?>
		<ul class="sortable">
			<li class="no_sortable usu_<?=$Id_Usuario?>">&nbsp;</li>
<?php
		if(isset($Trabajos[$Id_Usuario]['finalizado']))
		{
?>
			<li class="no_sortable line_Agregado" style="width:<?=$Trabajos[$Id_Usuario]['finalizado']?>px;"></li>
<?php
		}
		if(isset($Trabajos[$Id_Usuario]['trabajos']))
		{
			foreach($Trabajos[$Id_Usuario]['trabajos'] as $Id_Pedido => $Trabajo)
			{
?>
			<li peus="<?=$Trabajo['peu']?>" class="line_<?=$Trabajo['est']?>" style="width:<?=(10<$Trabajo['tie'])?$Trabajo['tie']:10?>px;" title="<?=$Trabajo['pro']?>"><?=$Trabajo['pro']?></li>
<?php
			}
		}
?>
		</ul>
<?php
		}
	}
?>
		<br style="clear:both;" />
<?
}
?>
</div>






<!-- Representacion de los dptos y puestos -->
<div class="tablero_listas posicion_fix" style="width: 100px;">

<form action="/carga/seguimiento" method="post" id="ir_carga_laboral" target="cargaaaa">
	<?
//Este form envia la info a carga por puesto y busca un mes antes (M_A) y un mes despues (M_D)
$M_A = date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))));
$M_D = date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))));
$M_A = explode('-', $M_A);
$M_D = explode('-', $M_D);
?>
	<input type="hidden" name="dia1" value="<?=$M_A[2]?>" />
	<input type="hidden" name="mes1" value="<?=$M_A[1]?>" />
	<input type="hidden" name="anho1" value="<?=$M_A[0]?>" />
	<input type="hidden" name="dia2" value="<?=$M_D[2]?>" />
	<input type="hidden" name="mes2" value="<?=$M_D[1]?>" />
	<input type="hidden" name="anho2" value="<?=$M_D[0]?>" />
	<input type="hidden" name="puesto" id="asig_puesto" value="" />
	<input type="hidden" name="cliente" value="todos" />
	<input type="hidden" name="trabajo" value="incompleto" />
	<input type="hidden" name="bus_proceso" value="" />
	<input type="hidden" name="cliente_tipo" id="cliente_tipo" value="todos" />
	<ul class="tiempos" style="height:26px;">
		<li></li>
	</ul>
<?
foreach($Dpto_Usu as $Id_Dpto => $Dpto)
{
	if('n' == $Dpto['tiempo'] && 'SAP' != $Dpto['dpto'] && 'Despacho' != $Dpto['dpto'])
	{
		continue;
	}
?>
	<ul class="tiempos" style="z-index: 5;">
		<li class="no_sortable" <?=(9==$Id_Dpto)?' onclick="MostrarPlanchas();"':''?>><?=$Dpto['dpto']?></li>
	</ul>
<?
	//Listado de los trabajos
	foreach($Dpto['usuarios'] as $Id_Usuario => $Usuario)
	{
		if(9 != $Id_Dpto)
		{
?>
		<ul class="sortable">
			<li class="no_sortable usuario" usu="<?=$Id_Usuario?>" style="width:70px;"><?=$Usuario['usuario']?></li>
			<li class="no_sortable" style="width:20px;"><span usu="<?=$Id_Usuario?>">[&#x279a;]</span></li>
		</ul>
<?php
		}
	}
?>
		<br style="clear:both;" />
<?
}
?>
</form>

</div>
</div>

<?php
$Tiempos = array(
	'08:00',
	'09:00',
	'10:00',
	'11:00',
	'13:00',
	'14:00',
	'15:00',
	'16:00',
	'17:00',
	'18:00',
	'20:00',
	'21:00',
	'22:00',
	'23:00',
	'24:00',
);
?>
<div id='planchas'>
	
<div class="tablero_listas" style="height: 150px;">
	<div style="width: 102px;border-right: 1px solid #999;height: 110px;"></div>
<?
//Franjas para representar el ancho de los dias
$Contador2 = 0;
$Izquierda = 102;
$Horas_Tablero = 0;

for($i = 1; $i <= 8; $i++)
{
	
	$ancho = 899;
	if(
		6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))
	)
	{
		$ancho = 239;
		$Horas_Tablero += 4;
	}
	else
	{
		$Horas_Tablero += 15;
	}
	$Fondo = 'ffffff';
	if(0 == ($i % 2))
	{
		$Fondo = 'eeeeee';
	}
?>
		<div style="background:#<?=$Fondo?>;left:<?=$Izquierda?>px;width:<?=$ancho?>px;border-right: 0px solid #999;height: 110px;"></div>
<?
	$Izquierda += $ancho + 1;
	$Contador2++;
}


$Ancho_General = $Izquierda + 25;


//Franjas para representar el ancho de las horas
$Contador = 0;
$Contador2 = 0;
$Izquierda = 102;
$ancho = 59;
for($i = 1; $i <= $Horas_Tablero; $i++)
{
	//echo '6 == '.date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d')))).' && 11:00 == '.$Tiempos[$Contador].'----<br>';
	if(
		6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))
		&& '11:00' == $Tiempos[$Contador]
	)
	{
		$Contador = 0;
		$Contador2++;
	}
?>
		<div style="top:20px;left:<?=$Izquierda?>px;width:<?=$ancho?>px;border-right: 1px solid #ccc;height: 90px;"></div>
<?
	$Izquierda += $ancho + 1;
	$Contador++;
	if(7 == $Contador)
	{
		$Contador = 0;
		$Contador2++;
	}
}
?>




</div>


<!-- Representacion de los trabajos -->
<div class="tablero_listas" style="width:<?=($Ancho_General+1500)?>px;">
	
	<ul class="tiempos">
		<li class="no_sortable"></li>
<?php
$Contador2 = 0;
for($i = 0; $i <= 7; $i++)
{
	if(0 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d')))))
	{
		$Contador2++;
	}
	$Ancho_Div = 900;
	if(6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d')))))
	{
		$Ancho_Div = 240;
	}
?>
		<li style="overflow: hidden;width: <?=$Ancho_Div?>px;text-align: center;"><strong><?=$Dias[date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))].' '.date('d-m-Y', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))?></strong></li>
<?
	$Contador2++;
}
?>
	</ul>
<?
foreach($Dpto_Usu as $Id_Dpto => $Dpto)
{
	if(9 == $Id_Dpto)
	{
?>
	<ul class="tiempos" style="z-index: 5;">
		<li class="no_sortable">&nbsp;</li>
	
<?


$Contador = 0;
$Contador2 = 0;
//echo '<br><br><br>';
for($i = 1; $i <= $Horas_Tablero; $i++)
{
	if(
		6 == date('w', strtotime('+'.$Contador2.' days', strtotime(date('Y-m-d'))))
		&& 4 == $Contador
	)
	{
		$Contador = 0;
		$Contador2++;
	}
?>
		<li style="width: 60px; text-align: center;"><strong><?=$Tiempos[$Contador]?></strong></li>
<?
	$Contador++;
	if(15 == $Contador)
	{
		$Contador = 0;
		$Contador2++;
	}
}
?>
	</ul>
<?
	//Listado de los trabajos
	foreach($Dpto['usuarios'] as $Id_Usuario => $Usuario)
	{
?>
		<ul class="sortable">
			<li class="no_sortable usu_<?=$Id_Usuario?>">&nbsp;</li>
			
<?php			
		if(isset($Trabajos[$Id_Usuario]['finalizado']))
		{
?>
			<li class="no_sortable line_Agregado" style="width:<?=$Trabajos[$Id_Usuario]['finalizado']?>px;"></li>
<?php
		}
		if(isset($Trabajos[$Id_Usuario]['trabajos']))
		{
			foreach($Trabajos[$Id_Usuario]['trabajos'] as $Id_Pedido => $Trabajo)
			{
				//Se restan 240 minutos correspondientes a las 4 horas de proceso de las planchas.
				$Trabajo['tie'] = $Trabajo['tie']-220;
?>
			<li peus="<?=$Trabajo['peu']?>" class="line_<?=$Trabajo['est']?>" style="width:<?=(10<$Trabajo['tie'])?$Trabajo['tie']:10?>px;" title="<?=$Trabajo['pro']?>"><?=$Trabajo['pro']?></li>
<?php

			}
		}
?>
		</ul>
<?php
	}
?>
		<br style="clear:both;" />
<?
	}
}
?>
</div>






<!-- Representacion de los dptos y puestos -->
<div class="tablero_listas posicion_fix" style="width: 100px;">

<form action="/carga/seguimiento" method="post" id="ir_carga_laboral" target="cargaaaa">
	<?
//Este form envia la info a carga por puesto y busca un mes antes (M_A) y un mes despues (M_D)
$M_A = date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))));
$M_D = date('Y-m-d', strtotime('+30 days', strtotime(date('Y-m-d'))));
$M_A = explode('-', $M_A);
$M_D = explode('-', $M_D);
?>
	<input type="hidden" name="dia1" value="<?=$M_A[2]?>" />
	<input type="hidden" name="mes1" value="<?=$M_A[1]?>" />
	<input type="hidden" name="anho1" value="<?=$M_A[0]?>" />
	<input type="hidden" name="dia2" value="<?=$M_D[2]?>" />
	<input type="hidden" name="mes2" value="<?=$M_D[1]?>" />
	<input type="hidden" name="anho2" value="<?=$M_D[0]?>" />
	<input type="hidden" name="puesto" id="asig_puesto" value="" />
	<input type="hidden" name="cliente" value="todos" />
	<input type="hidden" name="trabajo" value="incompleto" />
	<input type="hidden" name="bus_proceso" value="" />
	<input type="hidden" name="cliente_tipo" id="cliente_tipo" value="todos" />
	<ul class="tiempos" style="height:26px;">
		<li></li>
	</ul>
<?
foreach($Dpto_Usu as $Id_Dpto => $Dpto)
{
	if(9 == $Id_Dpto)
	{
?>
	<ul class="tiempos" style="z-index: 5;">
		<div style='height: 22px; width: 102px; background: #ffffff;'><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label onclick="Ocultar();">Cerrar</label></strong></div>
		<li class="no_sortable"><?=$Dpto['dpto']?></li>
	</ul>
<?

?>
		<ul class="sortable">
			<li class="no_sortable usuario" usu="<?=$Id_Usuario?>" style="width:70px;"><?=$Usuario['usuario']?></li>
			<li class="no_sortable" style="width:20px;"><span usu="<?=$Id_Usuario?>">[&#x279a;]</span></li>
		</ul>
<?php
	}
}
?>
</form>

</div>
</div>

<script>
	
	var leftInit = $(".posicion_fix").offset().left;
	
	$(window).scroll(function(event)
	{
		var x = 0 + $(this).scrollLeft();
		$(".posicion_fix").offset(
		{
			left: x + leftInit
		});
	});
	
	
	
	$('.usuario').click(function()
	{
		$('.usu_'+$(this).attr('usu')).parent().sortable(
		{
			items: 'li:not(.no_sortable)'
		}).disableSelection();
		$(this).css({'background-color':'#cccccc','font-weight':'700'});
		$('.usu_'+$(this).attr('usu')).parent().addClass('ruta_modificada');
	});
	
	$('.posicion_fix span').click(function()
	{
		$('#asig_puesto').val($(this).attr('usu'));
		window.open('/', 'cargaaaa');
		$('#ir_carga_laboral').submit();
	});
	
	
	
	
	function guardar_prioridad()
	{
		
		var prioridades = '';
		
		$('.ruta_modificada').each(function()
		{
			if('' != prioridades)
			{
				prioridades = prioridades+',';
			}
			var pedidos = '';
			$(this).children().each(function()
			{
				if('' != pedidos)
				{
					pedidos = pedidos+',';
				}
				if($(this).attr('peus'))
				{
					pedidos = pedidos + $(this).attr('peus');
				}
			});
			prioridades = prioridades+'"'+ $(this).attr('usu') +'":['+pedidos+']';
		});
		prioridades = '{'+prioridades+'}';
		
		
		$.ajax({
		type: "POST",
		url: "/carga/tablero/asignar",
		data: 'datos='+prioridades,
		success: function(msg)
		{
			if(msg == "ok")
			{
				alert("Cambios realizados con Exito.");
				window.location.reload();
			}
			else alert("Ocurrio un error: "+msg+".<br />Haga una captura de pantalla para realizar una verificacion del problema.");
		},
		error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
	});
		
	}
	function Ocultar()
	{
		$('#planchas').hide();
		//$('#deptos').show();
		location.reload();
	}
	
	function MostrarPlanchas()
	{
		$('#planchas').show();
		$('#deptos').hide();
	}
	
</script>

