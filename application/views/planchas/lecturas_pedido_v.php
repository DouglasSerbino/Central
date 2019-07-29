

<script type="text/javascript" src="/html/js/jquery.flot.js"></script>


<br /><br />
<form id="lecturas_form" action="/planchas/lecturas/agregar" method="post" enctype="multipart/form-data">
	
	<input type="hidden" name="med_version" id="med_version" value="1" />
	<input type="hidden" name="id_pedido" value="<?=$Id_Pedido?>" />
	<input type="hidden" name="redir" value="<?=$Redireccion?>" />
	<input type="hidden" name="cliente" id="cliente" value="<?=(isset($Proceso))?$Proceso['id_cliente']:$Info_Proceso['id_cliente']?>">
	
	<strong style="font-size: 16px;">Mediciones de Placas</strong>
	
	
	<br />
	
	<div style="float:left;margin-right:15px;">
		<strong>Par&aacute;metros</strong>
		
		
		<br />
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
		
		<br />
		
		<strong>Reporte ZIP</strong>
		<input type="file" name="pdf" size="10" />
	</div>
	
	
	<div style="float:left;">
		<strong>Medici&oacute;n</strong>
		&nbsp; <cite class="italica">(Todos los campos son requeridos)</cite>
		
		<br />
		<table class="tabla_i" id="camp_lecturas" style="text-align: right;">
			<tr>
				<th>&nbsp;</th>
				<th>3%</th>
				<th>25%</th>
				<th>50%</th>
				<th>75%</th>
				<th>100%</th>
			</tr>
<?php
if(isset($Info_Proceso))
{
	$Proceso_Ped = $Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso'];
}
if(isset($Proceso))
{
	$Proceso_Ped = $Proceso['codigo_cliente'].'-'.$Proceso['proceso'];
}


$mostrar_boton = false;
$Colores_v = array('C', 'M', 'Y', 'K', 'R', 'G', 'B');
$Colores_Nom_v = array('C' => 'cyan', 'M' => 'magenta', 'Y' => 'amarillo', 'K' => 'negro', 'R' => 'rojo', 'G' => 'verde', 'B' => 'azul');
$Porcentajes = array(5 => 3, 25 => 25, 50 => 50, 75 => 75, 100 => 100);
if(
		9 == $this->session->userdata('id_dpto')//Planchas
		|| 29 == $this->session->userdata('id_dpto')//Sistemas
		|| 'Gerencia' == $this->session->userdata('codigo')
		|| 'Plani' == $this->session->userdata('codigo')
	)
{
	$mostrar_boton= true;
}
foreach($Colores_v as $Index => $Color)
{
	$Color_min = strtolower($Color);
	if(
		3 == $this->session->userdata('id_dpto')
		|| 'Gerencia' == $this->session->userdata('codigo')
		|| 'Plani' == $this->session->userdata('codigo')
	)
	{
?>
			<tr>
				<th><span class="lec_ref_form" col="<?=$Color_min?>"><?=$Color?></span></th>
<?php
foreach($Porcentajes as $Index => $Porce)
{
?>
				<td>
<?php
	if(isset($Medicion_Ped['ipl']))
	{
?>
					<a href="/mediciones/<?=$Medicion_Ped['ipl'].'/'.$Proceso_Ped.' '.$Colores_Nom_v[$Color]?> <?=$Porce?>.pdf" target="_blank"><?=$Medicion_Ped[$Color_min][$Index]?></a>
<?php
	}
	else
	{
?>
					0
<?php
	}
?>
					<input type="hidden" name="<?=$Color_min?>_<?=$Index?>" id="<?=$Color_min?>_<?=$Index?>" value="<?=$Medicion_Ped[$Color_min][$Index]?>" />
				</td>
<?php
}
?>
			</tr>
<?php
	}
	else
	{
?>
			<tr id="fil_<?=$Color_min?>">
				<th><span class="lec_ref_form" col="<?=$Color_min?>"><?=$Color?></span></th>
				<td><input type="text" size="5" name="<?=$Color_min?>_5" id="<?=$Color_min?>_5" class="requ num" value="<?=$Medicion_Ped[$Color_min][5]?>" color="<?=$Color_min?>" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_25" id="<?=$Color_min?>_25" class="requ num" value="<?=$Medicion_Ped[$Color_min][25]?>" color="<?=$Color_min?>" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_50" id="<?=$Color_min?>_50" class="requ num" value="<?=$Medicion_Ped[$Color_min][50]?>" color="<?=$Color_min?>" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_75" id="<?=$Color_min?>_75" class="requ num" value="<?=$Medicion_Ped[$Color_min][75]?>" color="<?=$Color_min?>" /></td>
				<td><input type="text" size="5" name="<?=$Color_min?>_100" id="<?=$Color_min?>_100" class="requ num" value="<?=$Medicion_Ped[$Color_min][100]?>" color="<?=$Color_min?>" /></td>
			</tr>
<?php
	}
}
?>
		</table>
		
		<br />
		<?php
			if($mostrar_boton)
			{
			?>
		<input type="button" value="Agregar" class="boton" onclick="guarda_medicion()" />
		<?php
			}
			?>
	</div>
	
	
	<div style="clear: both;margin-left:35px;height:350px;">
		
		<?php
if(isset($Medicion_Ped_Ant['tra']))
{
?>
		<select id="elije_referencia">
			<option value="ref">Lecturas de Referencia</option>
			<option value="ant">Trabajos Anteriores</option>
		</select>
<?php
}
else
{
?>
		<input type="hidden" id="elije_referencia" value="ref" />
<?php
}
?>
		
		<strong>Curva: <span id="form_nombre_col"></span></strong>
		
		<div id="grafico-linea" style="width:500px;height:225px;"></div>
	</div>
	
	
</form>






<script>
	var Color_Act = 'c';
	var Mediciones = {<?php
$Contador = 1;
foreach($Referencias as $Id_Medicion => $Medicion)
{
	if('formulas' == $Id_Medicion)
	{
		continue;
	}
	
	if(1 < $Contador)
	{
		echo ',';
	}
	
	echo '"'.$Medicion['pla'].'-'.$Medicion['sis'].'-'.$Medicion['com'].'-'.$Medicion['alt'].'-'.$Medicion['tra'].'-'.$Medicion['lin'].'-'.$Medicion['cli'].'"';
	echo ':{';
	$Colores_v = array('c', 'm', 'y', 'k', 'r', 'g', 'b');
	foreach($Colores_v as $Color)
	{
		if('c' != $Color)
		{
			echo ',';
		}
		echo '"'.$Color.'":';
		echo json_encode($Medicion[$Color]);
	}
	echo '}';
	
	$Contador++;
}

?>};


	var Anterior = <?=(isset($Medicion_Ped_Ant['colores']))?json_encode($Medicion_Ped_Ant['colores']):'{}'?>;
	var Fechas_A = <?=(isset($Medicion_Ped_Ant['fechas']))?json_encode($Medicion_Ped_Ant['fechas']):'{}'?>;

	
	var Formulas = <?=(isset($Referencias['formulas']))?json_encode($Referencias['formulas']):'[]'?>;
	var Medi_Ped = "<?php
//Que parametros se utilizaran para poner "selected" en los selects?
//Es posible utilizar los parametros del trabajo anterios;
//Pero si ya se han guardado los parametros para este pedido deben utilizarse esos

//Hay una medicion para este pedido?
if(isset($Medicion_Ped['formula']))
{
	echo $Medicion_Ped['formula'];
}
elseif(isset($Medicion_Ped_Ant['formula']))
{
	//Hay una medicion anterior a este pedido
	echo $Medicion_Ped_Ant['formula'];
}
?>";
	
	if('' !== Medi_Ped)
	{
		Medi_Ped = Medi_Ped.split('-');
		$('#plancha option[value="'+Medi_Ped[0]+'"]').attr('selected', 'selected');
		$('#sistema option[value="'+Medi_Ped[1]+'"]').attr('selected', 'selected');
		$('#compensacion option[value="'+Medi_Ped[2]+'"]').attr('selected', 'selected');
		$('#altura option[value="'+Medi_Ped[3]+'"]').attr('selected', 'selected');
		$('#trama option[value="'+Medi_Ped[4]+'"]').attr('selected', 'selected');
		$('#lineaje option[value="'+Medi_Ped[5]+'"]').attr('selected', 'selected');
	}
	
	function guarda_medicion()
	{
		
		$('#med_version').val(1);
		
		if(!confirm('\xbfYa agreg\xf3 el archivo Zip conteniendo las mediciones?'))
		{
			return false;
		}
		
		
		var Se_Puede = validar('lecturas_form');
		if(Se_Puede)
		{
			$('#lecturas_form').submit();
		}
	}
	
	
	
	
	
	
	
	var Colores_v = {'1':'','c':'Cyan','m':'Magenta','y':'Amarillo','k':'Negro','r':'Rojo','g':'Verde','b':'Azul'}
	
	$('#camp_lecturas .requ').on('blur', function()
	{
		Color_Act = $(this).attr('color');
		tomar_datos($(this).attr('color'));
	});
	
	$('.lec_ref_form').click(function()
	{
		
		Color_Act = $(this).attr('col');
		tomar_datos($(this).attr('col'));
		
	});
	
	$('#elije_referencia').change(function()
	{
		tomar_datos(Color_Act);
	});
	
	function tomar_datos(color)
	{
		var series = [{},{}];
		var meta = [[0,0],[1,0],[2,0],[3,0],[4,0]];
		
		//if('ref' == $('#elije_referencia').val())
		//{
			var Formula = $('#plancha').val()+'-'+$('#sistema').val()+'-'+$('#compensacion').val();
			Formula = Formula +'-'+$('#altura').val()+'-'+$('#trama').val();
			Formula = Formula +'-'+$('#lineaje').val()+'-'+$('#cliente').val();
			
			
			if(undefined != Mediciones[Formula])
			{
				meta = [
					[0,Mediciones[Formula][color][5]],
					[1,Mediciones[Formula][color][25]],
					[2,Mediciones[Formula][color][50]],
					[3,Mediciones[Formula][color][75]],
					[4,Mediciones[Formula][color][100]]
				];
			}
			
			series.push({data: meta, label:'Meta'});
		//}
		//else
		if('ref' != $('#elije_referencia').val())
		{
			for(i in Anterior)
			{
				meta = [
					[0, Anterior[i][color][5]],
					[1, Anterior[i][color][25]],
					[2, Anterior[i][color][50]],
					[3, Anterior[i][color][75]],
					[4, Anterior[i][color][100]]
				];
				series.push({data: meta, label:Fechas_A[i]});
			}
		}
		
		
		var real = [
			[0,$('#'+color+'_5').val()],
			[1,$('#'+color+'_25').val()],
			[2,$('#'+color+'_50').val()],
			[3,$('#'+color+'_75').val()],
			[4,$('#'+color+'_100').val()]
		];
		
		
		series.push({data: real, label:'Actual'});
		
		hacer_grafica('grafico-linea', color, series, 'form_nombre_col');
	};
	
	
	
	
	function hacer_grafica(div, color, series, etiqueta)
	{
		$('#'+etiqueta).empty().append(Colores_v[color]);
		$('#'+div).empty();
		
		
		var Grafico = $.plot(
			$('#'+div),
			series,
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
					//points: { show: true },
					shadowSize:0
				},
				/*grid: { hoverable: true },*/
				yaxis: { min: 0, max: 100 }
			}
		);
		
		
		var color_fondo = {2:'6B0808', 3:'004900', 4:'351e4d'};
		var total_barras = Grafico.getData();
		total_barras = total_barras.length;
		for(z = 2; z < total_barras; z++)
		{
			$.each(Grafico.getData()[z].data, function(i, el, infor)
			{
				if('' != el)
				{
					var o = Grafico.pointOffset({x: el[0], y: el[1]});
					var Izquierda = o.left - 25;
					var Arriba = o.top;
					if(3 == z)
					{
						Izquierda = o.left + 15;
					}
					if(4 == z)
					{
						Arriba = Arriba + 20;
						Izquierda = o.left;
					}
					
					$('<div class="data-point-label">' + el[1] + '</div>').css(
						{
							position: 'absolute',
							left: Izquierda,
							top: Arriba,
							color: '#'+color_fondo[z],
							'text-shadow': '2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff'
						}
					).appendTo(
						Grafico.getPlaceholder()
					);
				}
			});
		}
		
		
	};
	
	tomar_datos(Color_Act);
	
	
	
	
	
	
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

