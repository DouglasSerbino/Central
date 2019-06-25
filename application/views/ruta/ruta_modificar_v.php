<script type="text/javascript" src="/html/js/flowchart.min.js"></script>
<link rel="stylesheet" href="/html/css/flowchart.min.css" />
<style type="text/css">
	
	#menu-lateral{
		display: none;
	}
	#cont-pagina{
		width: 1105px;
	}
	.ruta-dinamica-contenedor{
		height: 200px;
		border: 1px solid #BBB;
		margin-bottom: 10px;
	}
	.flowchart-operator{
		width: 105px;
		border-radius: 5px;
		border: 2px solid #899a19;
		box-shadow: 2px 2px 5px #d2e151;
	}
	.flowchart-operator .flowchart-operator-title{
		height: 20px;
		background: #899a19;
	}
	.flowchart-operator .flowchart-operator-inputs-outputs{
		margin-top: 15px;
		margin-bottom: 15px;
	}
	.flowchart-operator-connector-arrow{
		top: 4px;
		border-left: 10px solid #6a7619;
	}
	.flowchart-operator-connector-small-arrow{
		top: 9px;
	}
	.ruta-dinamica pre{
		display: none;
	}
	#create_operator{
		margin-bottom: 10px;
	}
	.ruta-dinamica-event{
		margin-top: 10px;
		margin-bottom: 10px;
		color: #3366FF;
	}
	#last_event_example_6{
		display: block;
		overflow-y: auto;
		height: 100px;
	}
	#flowchart_data{
		width: 100%;
		margin-top: 20px;
		margin-bottom: 40px;
		height: 140px;
	}
	h4{
		margin-top: 40px;
	}
	#operator_properties, #link_properties{
		display: none;
		margin-top: 20px;
		margin-bottom: 20px;
		border: 4px solid;
		padding: 10px;
	}
	#envoltura_ruta{
		width: 1200px;
		height: 180px;
		top: 20px;
		left: 10px;
	}
	#envoltura_ruta span{
		display: block;
		height: 15px;
		width: 1170px;
		font-size: 15px;
		padding: 0px 15px;
		line-height: 10px;
		color: #fdfdfd;
		font-weight: bold;
		background: #888888;
		border: 1px solid #888888;
		cursor: e-resize;
		z-index: 9000;
	}
	#ruta_grafico{
		width: 100%;
		height: 140px;
		background: #fefefe;
	}
	#ruta_contenedor{
		width: 100%;
		height: 200px;
		overflow: hidden;
		background: repeating-linear-gradient(
			45deg,
			#eee,
			#eee 10px,
			#e5e5e5 10px,
			#e5e5e5 20px
		);
		border: 1px solid #aaaaaa;
	}
	.ruta_departamento{
		background: #f2f8d8;
		display: inline-block;
		padding: 4px 10px;
		border-radius: 5px;
		border: 2px solid #899a19;
		cursor: grab;
		-webkit-touch-callout: none;	/* iOS Safari */
		-webkit-user-select: none;	/* Chrome/Safari/Opera */
		-khtml-user-select: none;	/* Konqueror */
		-moz-user-select: none;	/* Firefox */
		-ms-user-select: none;	/* IE/Edge */
		user-select: none;	/* non-prefixed version, currently
		not supported by any browser */
	}
	.ruta_elementos{
		width: 100%;
		margin-top: 0px;
		background: #dce1e7;
		border: 1px solid #aaaaaa;
		/*border-bottom: none;*/
	}
	.ruta_elementos_label{
		padding: 15px 15px 5px;
		margin-bottom: 5px;
	}
	.ruta_elementos_divs{
		padding: 0px 15px 15px;
	}
	.delete_selected_button{
		margin-bottom: 20px;
	}
	.flowchart-operator-connector-label{
		font-size: 12px;
	}
</style>



<div class="ruta-dinamica">

	<div class="ruta_elementos">
		<div class="ruta_elementos_label">



			<form action="/ruta/ruta_dinamica/actualizar" method="post" id="ruta_form">

				<textarea name="ruta_texto" id="ruta_texto" style="display: none;"></textarea>
				<input type="hidden" name="id_ruta" value="<?=$Id_Ruta?>" />

				<table>
					<tr style="display: none;">
						<th>Ruta:</th>
						<td>
							<input type="text" name="ruta_nombre" size="45" value="<?=$Ruta['info']['observacion']?>" />
						</td>
					</tr>
					<tr>
						<th>Elemento:</th>
						<td>
							<select name="ruta_elemento">
								<option value="PDF"<?=('PDF'==$Ruta['info']['elemento']?' selected="selected"':'')?>>PDF</option>
								<option value="PCD"<?=('PCD'==$Ruta['info']['elemento']?' selected="selected"':'')?>>PCD</option>
								<option value="Plancha"<?=('Plancha'==$Ruta['info']['elemento']?' selected="selected"':'')?>>Plancha</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Cliente</th>
						<td>
							<select name="ruta_cliente">
								<!--option value="">Seleccionar cliente</option-->
<?
foreach($Clientes as $Fila)
{
?>
								<option value="<?=$Fila['id_cliente']?>"<?=($Fila['id_cliente']==$Ruta['info']['id_cliente'])?' selected="selected"':''?>><?=$Fila['codigo_cliente'].' - '.$Fila['nombre']?></option>
<?
}
?>
							</select>
						</td>
					</tr>
				</table>
				

			</form>
			

		</div>

		<div class="ruta_elementos_divs">
<?
$Puestos = array(
	2 => 'Arte',
	5 => 'PrePrensa',
	28 => 'Supervision',
	9 => 'Planchas',
	15 => 'Administracion',
	10 => 'Despacho'
);

foreach($Puestos as $Index => $Valor)
{
?>
			<div class="ruta_departamento" info="<?=$Index?>"><?=$Valor?></div>
<?
}
?>
		</div>

	</div>

	<div id="ruta_contenedor">
		<div id="envoltura_ruta">
			<span>&#x27F7;</span>
			<div class="ruta-dinamica-contenedor" id="ruta_grafico">
			</div>
		</div>
	</div>

	<div class="ruta_elementos">
		<div class="ruta_elementos_label derecha">
			<input type="button" value="Cancelar" id="ruta_cancelar" />
			<input type="button" value="Modificar Ruta" id="ruta_guardar" />
		</div>
	</div>

</div>


<script type="text/javascript">

	$(document).ready(function()
	{

		$('[name="ruta_nombre"]').keypress(function(e)
		{
			if('13' == e.which)
			{
				return false;
			}
		});

		$('#ruta_cancelar').click(function()
		{
			window.location = '/ruta/ruta_dinamica/listar';
		});

		$('#ruta_guardar').click(function()
		{

			var elemento = {};

			$('.flowchart-operator').each(function()
			{
				if('' != $(this).attr('info'))
				{
					elemento[$(this).css('left')] = $(this).attr('info');
				}
			});

			$('#ruta_texto').val(JSON.stringify(elemento));
			$('#ruta_form').submit();

		});

		$('#envoltura_ruta').draggable({
			axis: "x",
			handle: "span"
		});


		var Espacio_Left = 5;

		var Inicio_Fin = { operators: {}, links: {} };

<?
$Operador = 0;
$Operador_Anterior = '';
foreach($Ruta['dptos'] as $Fila)
{
	$Operador_Activo = 'A'.$Operador;
?>
		Inicio_Fin.operators['<?=$Operador_Activo?>'] = {
			top: 10,
			left: Espacio_Left,
			properties: {
				title: '',
				inputs: {
					input: {
						label: '<?=$Puestos[$Fila['id_dpto']]?>'
					}
				},
				outputs: {
					output: {
						label: ''
					}
				},
				miinfo: '<?=$Fila['id_dpto']?>'
			}
		};

		if('' != '<?=$Operador_Anterior?>')
		{
			Inicio_Fin.links['<?=$Operador?>'] = {
				fromOperator: '<?=$Operador_Anterior?>',
				fromConnector: 'output',
				toOperator: '<?=$Operador_Activo?>',
				toConnector: 'input'
			};
		}

		Espacio_Left = Espacio_Left + 160;

<?
	$Operador++;
	$Operador_Anterior = $Operador_Activo;

}
?>



		/*Inicio_Fin.links['<?=$Operador?>'] = {
			fromOperator: '<?=$Operador_Anterior?>',
			fromConnector: 'output',
			toOperator: 'fin',
			toConnector: 'input'
		};

		Inicio_Fin.operators['inicio'] = {
			top: 10,
			left: 5,
			properties: {
				title: '',
				inputs: {},
				outputs: {
					output: {
						label: 'Inicio'
					}
				}
			}
		};

		Inicio_Fin.operators['fin'] = {
			top: 10,
			left: Espacio_Left,
			properties: {
				title: '',
				inputs: {
					input: {
						label: 'Fin'
					}
				},
				outputs: {}
			}
		};*/
		

		// Apply the plugin on a standard, empty div...
		var $flowchart = $('#ruta_grafico');
		var $container = $flowchart.parent();

		$flowchart.flowchart(
		{
			data: Inicio_Fin
		});



		$(document).keyup(function(tecla)
		{
			if(
				'inicio' != $flowchart.flowchart('getSelectedOperatorId')
				&& 'fin' != $flowchart.flowchart('getSelectedOperatorId')
			)
			{
				if('46' == tecla.which || '8' == tecla.which)
				{
					$flowchart.flowchart('deleteSelected');
				}
			}
		});


		
		function getOperatorData($element)
		{

			var data ={
				properties:{
					title: '',
					inputs:{},
					outputs:{},
					miinfo: $element.attr('info')
				}
			};

			data.properties.inputs['input'] ={ label: $element.text() };
			data.properties.outputs['output'] ={ label: ' ' };

			return data;

		}

		var operatorId = 0;


		var $draggableOperators = $('.ruta_departamento');

		$draggableOperators.draggable({
			cursor: "move",
			opacity: 0.7,

			helper: 'clone',
			appendTo: 'body',
			zIndex: 1000,

			helper: function(e){
				var $this = $(this);
				var data = getOperatorData($this);
				return $flowchart.flowchart('getOperatorElement', data);
			},
			stop: function(e, ui){
				var $this = $(this);
				var elOffset = ui.offset;
				var containerOffset = $container.offset();
				if(
					elOffset.left > containerOffset.left &&
					elOffset.top > containerOffset.top &&
					elOffset.left < containerOffset.left + $container.width() &&
					elOffset.top < containerOffset.top + $container.height()
				)
				{

					var flowchartOffset = $flowchart.offset();

					var relativeLeft = elOffset.left - flowchartOffset.left;
					var relativeTop = elOffset.top - flowchartOffset.top;

					var positionRatio = $flowchart.flowchart('getPositionRatio');
					relativeLeft /= positionRatio;
					relativeTop /= positionRatio;

					var data = getOperatorData($this);
					data.left = relativeLeft;
					data.top = relativeTop;

					$flowchart.flowchart('addOperator', data);

				}
			}

		});

	});
</script>


