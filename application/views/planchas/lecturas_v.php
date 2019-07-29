

<script type="text/javascript" src="/html/js/jquery.flot.js"></script>


<form id="lecturas_form" action="/planchas/lecturas/agregar" method="post" enctype="multipart/form-data">
	
	<input type="hidden" name="med_version" id="med_version" value="1" />
	<input type="hidden" name="id_pedido" value="0" />
	<input type="hidden" name="redir" value="lect" />
	
	<strong style="font-size: 15px;">Agregar Medici&oacute;n</strong>
	
	
	<br /><br />
	
	<strong>Cliente:</strong> <select name="cliente" id="cliente">
<?php
$Cliente = array();
foreach($Clientes as $Datos)
{
	$Cliente[$Datos['id_cliente']] = $Datos['nombre'];
?>
		<option value="<?=$Datos['id_cliente']?>"><?=$Datos['codigo_cliente'].' - '.$Datos['nombre']?></option>
<?php
}
?>
	</select>
	
	<br />
	
	<div style="float:left;margin-right:15px;">
		<strong>Par&aacute;metros</strong>
		
		<table>
			<tr>
				<td>Compensaci&oacute;n</td>
				<td>
					<select name="compensacion" id="compensacion">
<?php
foreach($Compensacion as $Id => $Datos)
{
?>
						<option value="<?=$Id?>"><?=$Datos?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Plancha</td>
				<td>
					<select name="plancha" id="plancha">
<?php
foreach($Plancha as $Id => $Datos)
{
?>
						<option value="<?=$Id?>"><?=$Datos?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Sistema</td>
				<td>
					<select name="sistema" id="sistema">
<?php
foreach($Sistema as $Id => $Datos)
{
?>
						<option value="<?=$Id?>"><?=$Datos?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Altura</td>
				<td>
					<select name="altura" id="altura">
<?php
foreach($Altura as $Id => $Datos)
{
?>
						<option value="<?=$Id?>"><?=$Datos?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Trama</td>
				<td>
					<select name="trama" id="trama">
<?php
foreach($Trama as $Id => $Datos)
{
?>
						<option value="<?=$Id?>"><?=$Datos?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Lineaje</td>
				<td>
					<select name="lineaje" id="lineaje">
<?php
foreach($Lineaje as $Id => $Datos)
{
?>
						<option value="<?=$Id?>"><?=$Datos?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
		</table>
		
		<br /><br />
		
		<strong>Reporte ZIP</strong>
		
		<br />
		<input type="file" name="pdf" size="10" />
		
	</div>
	
	
	
	
	<div style="float:left;margin-right: 20px;">
		<strong>Medici&oacute;n</strong>
		&nbsp; <cite class="italica">(Todos los campos son requeridos)</cite>
		
		<br />
		<table class="tabla_i">
			<tr>
				<th>&nbsp;</th>
				<th>3%</th>
				<th>25%</th>
				<th>50%</th>
				<th>75%</th>
				<th>100%</th>
			</tr>
<?php
$Colores_v = array('C', 'M', 'Y', 'K', 'R', 'G', 'B');
foreach($Colores_v as $Index => $Color)
{
	$Color_min = strtolower($Color);
?>
			<tr id="fil_<?=$Color_min?>">
				<th><span class="lec_ref_form" col="<?=$Color_min?>"><?=$Color?></span></th>
				<td><input type="text" size="5" name="<?=$Color_min?>_5" id="<?=$Color_min?>_5" class="requ num" value="" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_25" id="<?=$Color_min?>_25" class="requ num" value="" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_50" id="<?=$Color_min?>_50" class="requ num" value="" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_75" id="<?=$Color_min?>_75" class="requ num" value="" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_100" id="<?=$Color_min?>_100" class="requ num" value="" /></td>
			</tr>
<?php
}
?>
		</table>
		
		<br />
		<input type="button" value="Agregar" class="boton" onclick="guarda_medicion()" />
	</div>
	
	
	<div style="float:left;">
		<strong>Curva: <span id="form_nombre_col"></span></strong>
		<div id="grafico-linea" style="width:185px;height:225px;"></div>
	</div>
	
	
	<br style="clear:both;" /><br />
	
</form>







<strong style="font-size: 15px;">Mediciones de Referencia</strong>

<br />

<select class="ver_grafico">
<?php
foreach($Referencias as $Id_Medicion => $Medicion)
{
	if('formulas' == $Id_Medicion)
	{
		continue;
	}
	echo '<option value="'.$Medicion['com'].'-'.$Medicion['pla'].'-'.$Medicion['sis'].'-'.$Medicion['alt'].'-'.$Medicion['tra'].'-'.$Medicion['lin'].'-'.$Medicion['cli'].'">'.$Compensacion[$Medicion['com']].' - '.$Plancha[$Medicion['pla']].' - '.$Sistema[$Medicion['sis']].' - '.$Altura[$Medicion['alt']].' - '.$Trama[$Medicion['tra']].' - '.$Lineaje[$Medicion['lin']].' - '.$Cliente[$Medicion['cli']].'</option>';
}
?>
</select>


<br /><br />

<div id="no-existe" style="height:400px;">
	<strong>No se han realizado lecturas con los par&aacute;metros especificados</strong>
</div>


<?php
$Contador = 1;
foreach($Referencias as $Id_Medicion => $Medicion)
{
	if('formulas' == $Id_Medicion)
	{
		continue;
	}
?>
<div style="display:none;" class="cont-datos" id="par-<?=$Medicion['com'].'-'.$Medicion['pla'].'-'.$Medicion['sis'].'-'.$Medicion['alt'].'-'.$Medicion['tra'].'-'.$Medicion['lin'].'-'.$Medicion['cli']?>">
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
					<span class="lec_ref_list" info="<?=$Id_Medicion?>-<?=$Color?>"><?=strtoupper($Color)?>&nbsp;</span>
					<span id="medi-<?=$Id_Medicion?>-<?=$Color?>" class="escondido"><?=$Medicion[$Color][5].'-'.$Medicion[$Color][25].'-'.$Medicion[$Color][50].'-'.$Medicion[$Color][75].'-'.$Medicion[$Color][100]?></span>
				</strong>
			</td>
			<!--td><a href="/mediciones/<?=$Id_Medicion?>/<?=$Color?>_5.pdf"><?=$Medicion[$Color][5]?></a></td>
			<td><a href="/mediciones/<?=$Id_Medicion?>/<?=$Color?>_25.pdf"><?=$Medicion[$Color][25]?></a></td>
			<td><a href="/mediciones/<?=$Id_Medicion?>/<?=$Color?>_50.pdf"><?=$Medicion[$Color][50]?></a></td>
			<td><a href="/mediciones/<?=$Id_Medicion?>/<?=$Color?>_75.pdf"><?=$Medicion[$Color][75]?></a></td>
			<td><a href="/mediciones/<?=$Id_Medicion?>/<?=$Color?>_100.pdf"><?=$Medicion[$Color][100]?></a></td-->
			<td><?=$Medicion[$Color][5]?></td>
			<td><?=$Medicion[$Color][25]?></td>
			<td><?=$Medicion[$Color][50]?></td>
			<td><?=$Medicion[$Color][75]?></td>
			<td><?=$Medicion[$Color][100]?></td>
		</tr>
<?php
	}
?>
	</table>
	
	<div style="margin-left:25px;float:left;height:400px;">
		<strong>Curva: <span id="etiq_<?=$Id_Medicion?>"></span></strong>
		<div id="grafico-<?=$Id_Medicion?>" style="width:500px;height:300px;"></div>
	</div>
	
</div>
<?php
	$Contador++;
}
?>



<script>
	
	var Formulas = <?=(isset($Referencias['formulas']))?json_encode($Referencias['formulas']):'[]'?>;
	
	function guarda_medicion()
	{
		
		$('#med_version').val(1);
		
		if(!confirm('\xbfYa agreg\xf3 el archivo Zip conteniendo las mediciones?'))
		{
			return false;
		}
		
		var Formu_Agregar = $('#plancha').val()+'-'+$('#sistema').val()+'-'+$('#compensacion').val();
		Formu_Agregar = Formu_Agregar +'-'+$('#altura').val()+'-'+$('#trama').val();
		Formu_Agregar = Formu_Agregar +'-'+$('#lineaje').val()+'-'+$('#cliente').val();
		var Existe = false;
		var Contador = 1;
		for(i in Formulas)
		{
			if(Formulas[i] == Formu_Agregar)
			{
				Existe = true;
				Contador++;
			}
		}
		
		if(Existe)
		{
			//if(!confirm('Los par\xe1metros de fabricaci\xf3n indicados ya existen.\r\n\xbfDesea crear una nueva versi\xf3n?'))
			if(!confirm('Los par\xe1metros de fabricaci\xf3n indicados ya existen.\r\n\xbfDesea actualizalos?'))
			{
				//$('#med_version').val(1);
				return false;
			}
			//$('#med_version').val(Contador);
		}
		
		var Se_Puede = validar('lecturas_form');
		if(Se_Puede)
		{
			$('#lecturas_form').submit();
		}
	}
	
	
	
	
	
	
	
	var Colores_v = {'1':'','c':'Cyan','m':'Magenta','y':'Amarillo','k':'Negro','r':'Rojo','g':'Verde','b':'Azul'}
	
	$('.lec_ref_form').click(function()
	{
		
		var color = $(this).attr('col');
		var meta = [[0,3], [1,25], [2,50], [3,75], [4,100]];
		var real = [
			[0,$('#'+color+'_5').val()],
			[1,$('#'+color+'_25').val()],
			[2,$('#'+color+'_50').val()],
			[3,$('#'+color+'_75').val()],
			[4,$('#'+color+'_100').val()]
		];
		
		hacer_grafica('grafico-linea', color, meta, real, 'form_nombre_col');
	});
	
	
	$('.lec_ref_list').click(function()
	{
		
		var div = $(this).attr('info');
		div = div.split('-');
		
		var meta = [[0,3], [1,25], [2,50], [3,75], [4,100]];
		var real = $('#medi-'+div[0]+'-'+div[1]).text();
		real = real.split('-');
		real = [
			[0,real[0]],
			[1,real[1]],
			[2,real[2]],
			[3,real[3]],
			[4,real[4]]
		];
		
		hacer_grafica('grafico-'+div[0], div[1], meta, real, 'etiq_'+div[0]);
	});
	
	
	
	function hacer_grafica(div, color, meta, real, etiqueta)
	{
		$('#'+etiqueta).empty().append(Colores_v[color]);
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
					lines: { show:true, lineWidth:2, shadowSize:0 }/*,
					points: { show: true }*/,
					shadowSize:0
				},
				/*grid: { hoverable: true },*/
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
					var Izquierda = o.left - 25;
					if(3 == z)
					{
						Izquierda = o.left + 15;
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
	
	hacer_grafica('grafico-linea', 1, [], [], 'form_nombre_col');
	
	
	function showTooltip(x, y, contents)
	{
		$('<div id="tooltip">' + contents + '</div>').css(
		{
			position: 'absolute',
			display: 'none',
			top: y + 5,
			left: x + 5,
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#fee',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}
	
	
	var previousPoint = null;
	$("#grafico-linea").bind("plothover", function (event, pos, item)
	{
		$("#x").text(pos.x.toFixed(2));
		$("#y").text(pos.y.toFixed(2));
		
		if(item)
		{
			if(previousPoint != item.dataIndex)
			{
				previousPoint = item.dataIndex;
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2);
				showTooltip(item.pageX, item.pageY, y);
			}
		}
		else
		{
			$("#tooltip").remove();
			previousPoint = null;
		}
	});
	
	
	
	
	
	
	
	function mostrar_graficos()
	{
		$('#no-existe').hide();
		$('.cont-datos').hide();
		/*var id_div = 'par-' + $('#gra_comp').val();
		id_div = id_div + '-' + $('#gra_plan').val();
		id_div = id_div + '-' + $('#gra_sist').val();
		id_div = id_div + '-' + $('#gra_altu').val();*/
		var id_div = 'par-'+$('.ver_grafico').val();
		
		if(id_div === $('#'+id_div).attr('id'))
		{
			$('#'+id_div).show();
		}
		else
		{
			$('#no-existe').show();
		}
	}
	
	mostrar_graficos();
	
	
	$('.ver_grafico').change(function()
	{
		mostrar_graficos();
	});
	
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
	.data-point-label{
		font-size: 11px;
		padding: 0px 3px;
		font-weight: bold;
	}
</style>

