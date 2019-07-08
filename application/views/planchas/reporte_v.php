

<script type="text/javascript" src="/html/js/jquery.flot.js"></script>

<div>
	
	<strong>Ver reporte por</strong>
	<label><input type="radio" name="reporte" value="fecha"<?=('fecha'==$Reporte)?' checked="checked"':''?> /> Fecha</label>
	<label><input type="radio" name="reporte" value="proceso"<?=('proceso'==$Reporte)?' checked="checked"':''?> /> Proceso</label>
	
	<br />
	
	<div>
		<select id="sel_cliente">
<?php
$Cliente = array();
foreach($Clientes as $Datos)
{
	$Cliente[$Datos['id_cliente']] = $Datos['nombre'];
?>
			<option value="<?=$Datos['id_cliente']?>"<?=($Datos['id_cliente']==$Id_Cliente)?' selected="selected"':''?>><?=$Datos['codigo_cliente'].' - '.$Datos['nombre']?></option>
<?php
}
?>
		</select>
		
		<select id="sel_anho">
<?php
for($i = 2013; $i <= date('Y'); $i++)
{
?>
			<option value="<?=$i?>"<?=($i==$Anho)?' selected="selected"':''?>><?=$i?></option>
<?php
}
?>
		</select>
		
		<select id="sel_mes">
<?php
foreach($meses_v as $iMes => $nMes)
{
?>
			<option value="<?=$iMes?>"<?=($iMes==$Mes)?' selected="selected"':''?>><?=$nMes?></option>
<?php
}
?>
		</select>
	</div>
	
	<input type="button" id="boton_reporte" value="Ver Reporte" />
	
</div>



<br />

<table class="tabular" style="width:100%;">
	<tr>
		<th>Proceso</th>
		<th>Trabajo</th>
		<th>Compensaci&oacute;n</th>
		<th>Plancha</th>
		<th>Sistema</th>
		<th>Altura</th>
		<th>Trama</th>
		<th>Lineaje</th>
		<th>Entregado</th>
	</tr>
<?php
foreach($Reales as $Id_Med => $Trabajo)
{
?>
	<tr id="tr-<?=$Id_Med?>">
		<td><span medi="<?=$Id_Med?>" class="toolizq medi_trab"><?=$Trabajo['pro']?><span>Ver Gr&aacute;fica</span></span></td>
		<td><?=$Trabajo['nom']?></td>
		<td><?=$Compensacion[$Trabajo['com']]?></td>
		<td><?=$Plancha[$Trabajo['pla']]?></td>
		<td><?=$Sistema[$Trabajo['sis']]?></td>
		<td><?=$Altura[$Trabajo['alt']]?></td>
		<td><?=(isset($Trama[$Trabajo['tra']]))?$Trama[$Trabajo['tra']]:''?></td>
		<td><?=(isset($Lineaje[$Trabajo['lin']]))?$Lineaje[$Trabajo['lin']]:''?></td>
		<td>
			<?=$Trabajo['fec']?>
			<span class="datos" style="display:none;"><?=json_encode($Trabajo)?></span>
		</td>
	</tr>
<?php
}
?>
</table>





<div id="datos_medi" style="top:5px;">
	
	<div id="conte_medi">
	<strong>Detalle de Lectura</strong>
	
	<br />
	Proceso: <span id="Med_proc"></span><br />
	Trabajo: <span id="Med_nom"></span><br />
	Par&aacute;metros: <span id="Med_para"></span>
	
	<br /><br />
	<table class="tabular" style="text-align:right;float:left;">
		<tr>
			<th style="width:25px;">&nbsp;</th>
			<th style="width:40px;">3%</th>
			<th style="width:40px;">25%</th>
			<th style="width:40px;">50%</th>
			<th style="width:40px;">75%</th>
			<th style="width:40px;">100%</th>
		</tr>
<?php
	$Colores_v = array('c', 'm', 'y', 'k', 'r', 'g', 'b');
	foreach($Colores_v as $Index => $Color)
	{
?>
		<tr>
			<td>
				<strong>
					<span class="lec_ref_list" info="<?=$Color?>"><?=strtoupper($Color)?>&nbsp;</span>
				</strong>
			</td>
			<td id="<?=$Color?>_5" class="medi">0</td>
			<td id="<?=$Color?>_25" class="medi">0</td>
			<td id="<?=$Color?>_50" class="medi">0</td>
			<td id="<?=$Color?>_75" class="medi">0</td>
			<td id="<?=$Color?>_100" class="medi">0</td>
		</tr>
<?php
	}
?>
	</table>
	
	<div style="margin-left:25px;float:left;height:400px;">
		<strong>Curva: <span id="etiqueta_c"></span></strong>
		<div id="grafico-color" style="width:500px;height:300px;"></div>
	</div>
	
	<br style="clear:both;" />
	<input type="button" value="Cerrar" onclick="$('#datos_medi').hide();" />
	
	</div>
</div>





<script>
	
	var Referencias = <?=(isset($Referencias))?json_encode($Referencias):'[]'?>;
	var Colores_v = {'1':'','c':'Cyan','m':'Magenta','y':'Amarillo','k':'Negro','r':'Rojo','g':'Verde','b':'Azul'};
	var Info_Medida = {};
	var Info_Refere = {};
	var Planchas_par = <?=json_encode($Plancha)?>;
	var Compensacion_par = <?=json_encode($Compensacion)?>;
	var Sistema_par = <?=json_encode($Sistema)?>;
	var Altura_par = <?=json_encode($Altura)?>;
	var Trama_par = <?=json_encode($Trama)?>;
	var Lineaje_par = <?=json_encode($Lineaje)?>;
	var Cliente_par = {<?=(isset($Cliente[$Id_Cliente]))?'"'.$Id_Cliente.'":"'.$Cliente[$Id_Cliente].'"':''?>};
	
	
	$('#boton_reporte').click(function()
	{
		var Reporte = $('[name="reporte"]:checked').val();
		if('fecha' == Reporte)
		{
			window.location = '/planchas/reporte/index/'+$('#sel_cliente').val()+'/'+$('#sel_anho').val()+'/'+$('#sel_mes').val();
		}
	});
	
	
	
	$('.medi_trab').click(function()
	{
		
		//ESTE VALOR SERVIRA PARA DIRIGIR EL NAVEGADOR AL PDF CORRESPONDIENTE A CADA TRAMA
		//alert($(this).attr('medi'));
		
		var meta = [[0,0], [1,0], [2,0], [3,0], [4,0]];
		var real = [[0,0], [1,0], [2,0], [3,0], [4,0]];
		hacer_grafica('grafico-color',1,meta,real,'');
		var Medicion = $(this).attr('medi');
		
		$('#datos_medi .medi').empty();
		$('#datos_medi').css('top', (pageYOffset + 20)).show();
		
		Info_Medida = JSON.parse($('#tr-'+Medicion+' .datos').text());
		
		var hola = '';
		
		try
		{
			hola = Referencias[Info_Medida.alt][Info_Medida.pla][Info_Medida.sis][Info_Medida.com][Info_Medida.tra][Info_Medida.lin][Info_Medida.cli];
		}
		catch(e)
		{
			hola = undefined;
		}
		
		if(undefined != hola)
		{
			Info_Refere = Referencias[Info_Medida.alt][Info_Medida.pla][Info_Medida.sis][Info_Medida.com][Info_Medida.tra][Info_Medida.lin][Info_Medida.cli];
		}
		else
		{
			Info_Refere = {};
		}
		
		$('#Med_proc').empty().append(Info_Medida.pro);
		$('#Med_nom').empty().append(Info_Medida.nom);
		
		
		$('#Med_para').empty().append(Compensacion_par[Info_Medida.com]+' - '+Planchas_par[Info_Medida.pla]+' - '+Sistema_par[Info_Medida.sis]+' - '+Altura_par[Info_Medida.alt]+' - '+Trama_par[Info_Medida.tra]+' - '+Lineaje_par[Info_Medida.lin]+' - '+Cliente_par[Info_Medida.cli]);
		
		for(color in Info_Medida.col)
		{
			
			for(valor in Info_Medida.col[color])
			{
				var Porcentaje = valor;
				if(5 == Porcentaje)
				{
					Porcentaje = 3
				}
				$('#'+color+'_'+valor).append('<a href="/mediciones/'+Medicion+'/'+Info_Medida.pro+' '+Colores_v[color]+' '+Porcentaje+'.pdf" target="_blank">'+Info_Medida.col[color][valor]+'</a>');
			}
			
		}
	});
	
	
	
	
	$('.lec_ref_list').click(function()
	{
		
		var color = $(this).attr('info');
		var meta = [[0,0], [1,0], [2,0], [3,0], [4,0]];
		if(undefined != Info_Refere[color])
		{
			meta = [
				[0,Info_Refere[color][5]],
				[1,Info_Refere[color][25]],
				[2,Info_Refere[color][50]],
				[3,Info_Refere[color][75]],
				[4,Info_Refere[color][100]]
			];
		}
		
		var real = [
			[0,Info_Medida.col[color][5]],
			[1,Info_Medida.col[color][25]],
			[2,Info_Medida.col[color][50]],
			[3,Info_Medida.col[color][75]],
			[4,Info_Medida.col[color][100]]
		];
		
		hacer_grafica('grafico-color', color, meta, real, color);
	});
	
	
	
	function hacer_grafica(div, color, meta, real, etiqueta)
	{
		$('#etiqueta_c').empty().append(Colores_v[color]);
		$('#'+div).empty();
		
		
		var Grafico = $.plot(
			$('#'+div),
			[{},{},{data: meta, label:'Meta'}, {data: real, label:'Real'}],
			{
				xaxis:
				{
					min: 0,
					ticks: [[0, "3"],[1, "25"], [2, "50"], [3, "75"], [4, "100"]],
					max: 4
				},
				series:
				{
					lines: { show:true, lineWidth:2, shadowSize:0 },
					shadowSize:0
				},
				yaxis: { min: 0, max: 100 }
			}
		);
		
		
		var color_fondo = {2:'6B0808', 3:'004900'};
		for(z = 2; z <= 3; z++)
		{
			$.each(Grafico.getData()[z].data, function(i, el, infor)
			{
				if('' != el)
				{
					var o = Grafico.pointOffset({x: el[0], y: el[1]});
					var Izquierda = o.left + 15;
					if(3 == z)
					{
						Izquierda = o.left - 25;
					}
					$('<div class="data-point-label">' + el[1] + '</div>').css(
						{
							position: 'absolute',
							display: 'none',
							left: Izquierda,
							top: o.top,
							color: '#'+color_fondo[z],
							'text-shadow': '2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff'
						}
					).appendTo(
						Grafico.getPlaceholder()
					).fadeIn('slow');
				}
			});
		}
	};
	
	
</script>


<style>
	.lec_ref_form:hover, .lec_ref_list:hover{
		cursor: pointer;
		color: #287215;
		text-decoration: underline;
	}
	.escondido{
		display: none;
	}
	#datos_medi{
		position: absolute;
		display: none;
		padding-bottom: 70px;
	}
	#conte_medi{
		padding: 10px;
		background: #fafafa;
		border: 1px solid #cccccc;
	}
	.data-point-label{
		font-size: 11px;
		padding: 0px 3px;
		font-weight: bold;
	}
</style>

