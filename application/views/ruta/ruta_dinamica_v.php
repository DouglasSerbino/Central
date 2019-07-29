<script type="text/javascript" src="/html/js/flowchart.min.js"></script>
<link rel="stylesheet" href="/html/css/flowchart.min.css" />
<style type="text/css">

	.ruta-dinamica-contenedor{
		height: 200px;
		border: 1px solid #BBB;
		margin-bottom: 10px;
	}
	.flowchart-operator{
		width: 115px;
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
		top: 10px;
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
		height: 165px;
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
		border-bottom: none;
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

			<select>
				<option>PDF</option>
				<option>PCD</option>
				<option>Plancha</option>
			</select>
			<br />

			<select>
				<option value="">Seleccionar cliente</option>
				<option value="331">0000 - CORINFAR</option>
				<option value="327">00000 - PRUEBAS SISTEMA</option>
				<option value="193">AB - ALBACROME, S.A. DE C.V.</option>
				<option value="194">AD - AVERY DENNISON</option>
				<option value="313">ADH - AVERY DENNISON HONDURAS</option>
				<option value="195">AE - ASHEBORO ELASTIC CENTRAL AMERICA, S.A DE C.V.</option>
				<option value="297">AGP - Artes Graficas Publicitarias</option>
				<option value="196">AL - ALGIER</option>
				<option value="325">AS - Alimento S. de R.L.</option>
				<option value="304">ATS - ATLAS</option>
				<option value="197">BC - BOLSA DE CENTROAMERICA</option>
				<option value="198">BL - BOLPACK</option>
				<option value="305">BM - BEMISAL</option>
				<option value="199">BO - BOCADELI</option>
				<option value="200">BP - BEST PLAST</option>
				<option value="201">BS - BEMISAL</option>
				<option value="316">CAR - CARTONESA</option>
				<option value="323">CC - CUADERNOS COPAN HONDURAS</option>
				<option value="202">CD - CODIPA</option>
				<option value="204">CEG - CEGSA</option>
				<option value="203">CF - CLIENTES DIVER. CONS. FINAL</option>
				<option value="300">CGP - CENTRAL GRAPHICS PRUEBAS</option>
				<option value="294">CI - CONTAC IMPRESORES</option>
				<option value="205">CO - CORPPLASA</option>
				<option value="206">CP - CORINDPLAST HONDURAS</option>
				<option value="332">CRF - CORINFAR</option>
				<option value="207">CT - CARTONESA</option>
				<option value="208">CV - COMERCIAL VICTORIA</option>
				<option value="209">CVP - CONVERPLAST, S.A. DE C.V.</option>
				<option value="210">DD - DEUDORES DIVER. EXTERIOR</option>
				<option value="211">DE - DACSA ETIFLEX</option>
				<option value="295">DEM - DEMPAQUE</option>
				<option value="298">DGME - FAES DEL MJSP, Direcci&oacute;n General de Migraci&oacute;n y Extranger&iacute;a</option>
				<option value="212">DI - DIANA</option>
				<option value="213">DK - DARKOLOR</option>
				<option value="214">DU - DEUDORES DE UNICA VEZ</option>
				<option value="319">EB - EQUIBARRAS</option>
				<option value="215">EC - ETICOLOR</option>
				<option value="216">EG - EVERGREEN PACKAGING</option>
				<option value="217">EM - EMESA</option>
				<option value="218">EP - EMPAQUES PLASTICOS</option>
				<option value="320">EQ - EQUIBARRAS</option>
				<option value="219">ET - ETIPLAST GT</option>
				<option value="220">EU - EMPACADORA LA UNION</option>
				<option value="221">EV - ENVASEAL</option>
				<option value="222">FC - FORCON</option>
				<option value="223">FG - FLEXO GAMMA</option>
				<option value="224">FL - FLEXIGRAF</option>
				<option value="225">FN - FINOTEX</option>
				<option value="226">FP - FLEXOPACK</option>
				<option value="227">FS - FLEXSAL</option>
				<option value="228">FT - FLEXOPRINT</option>
				<option value="291">FTC - FERTICA</option>
				<option value="229">FX - FLEXSAL</option>
				<option value="230">G7 - GRUPO SIETE</option>
				<option value="231">GB - GLOBAL S.A.</option>
				<option value="326">GC - Grupo Castillo</option>
				<option value="232">GD - GRAF DEPOT</option>
				<option value="233">GM - GEORGE MOORE</option>
				<option value="330">GQS - GRUPO QS</option>
				<option value="234">GZ - VICTOR GALVEZ</option>
				<option value="309">HB - HB Trim El Salvador, S.A. de C.V.</option>
				<option value="235">HP - IMPRESIONES INDIGO</option>
				<option value="236">IA - INTERAMER</option>
				<option value="237">ID - ALIMENTOS IDEAL</option>
				<option value="329">IDP - DISTRIBUIDORA SALVADORENA DEL PLASTICO S.A. DE C.V.</option>
				<option value="238">IHP - IN HOUSE PRINT</option>
				<option value="239">IM - IMPRESOS MULTIPLES</option>
				<option value="314">IMT - IMET</option>
				<option value="240">IN - IMPRENTA NACIONAL</option>
				<option value="241">IP - INTERPLAST</option>
				<option value="324">IPS - IPSA</option>
				<option value="242">IT - IMT</option>
				<option value="293">JS - JAGUAR SPORTIC</option>
				<option value="243">KC - KIMBERLY CLARK</option>
				<option value="244">KT - KONTEIN</option>
				<option value="321">LA - Laboratorios Arsal</option>
				<option value="301">LB - LABELS</option>
				<option value="245">LC - LISTONES DE CENTROAMERICA</option>
				<option value="246">LG - LOGAN S.A.</option>
				<option value="307">LMK - LABORATORIOS MEDIKEM</option>
				<option value="247">LP - INDUSTRIAS LA POPULAR</option>
				<option value="248">LT - LIBERTY TECH</option>
				<option value="249">MC - MOORE COMERCIAL</option>
				<option value="250">MF - MULTIFILM, S.A.</option>
				<option value="306">MG - INDUSTRIAS MELGEES GUATEMALA, S.A</option>
				<option value="251">MI - GRUPO MIGUEL</option>
				<option value="252">MK - MULTIPACK</option>
				<option value="253">MP - MULTIPRINT</option>
				<option value="254">MT - METALTRO</option>
				<option value="299">NT - ETIROLL</option>
				<option value="255">NU - NUTRIVA</option>
				<option value="256">NX - NEMTEX</option>
				<option value="257">OL - OLEFINAS</option>
				<option value="258">OP - FLEXOPACK</option>
				<option value="259">OR - OREPLAST</option>
				<option value="260">PB - POLIBAG</option>
				<option value="261">PC - PRINTCRAFT</option>
				<option value="318">PCB - PCB LABEL-EL SALVADOR, S.A DE C.V.</option>
				<option value="322">PEU - PLASTICOS EUROPEOS S. de R.L.</option>
				<option value="262">PF - POLIFISA</option>
				<option value="263">PG - PLASTICOS GAMOZ</option>
				<option value="308">PGP - PEGAPRINT</option>
				<option value="264">PI - PAPELERA INTERNACIONAL</option>
				<option value="265">PK - P&G KOLOR</option>
				<option value="266">PL - POLIPRINT</option>
				<option value="267">PM - PLASTICOS MODERNOS</option>
				<option value="268">PN - PLASTINOVA</option>
				<option value="328">PO - PRUEBAS SISTEMA</option>
				<option value="269">PP - PACK PRINT</option>
				<option value="270">PR - PREPAC</option>
				<option value="271">PS - PLASAL</option>
				<option value="272">PT - PROTAPE</option>
				<option value="273">PX - PAXAR</option>
				<option value="274">PY - POLYPRINT</option>
				<option value="275">QG - QUALITY GRAINS</option>
				<option value="317">R - R-PAC</option>
				<option value="276">RAF - RAF</option>
				<option value="191">repro - Repro</option>
				<option value="277">RM - R Y M</option>
				<option value="278">RR - RR DONNELLEY</option>
				<option value="292">RUA - RUA SA DE CV</option>
				<option value="279">RY - RAYONES DE EL SALVADOR</option>
				<option value="310">SA - SACOS AGROINDUSTRIALES HONDURAS</option>
				<option value="280">SD - ALAS DORADAS</option>
				<option value="281">SE - SUMINISTROS Y EMPAQUES</option>
				<option value="311">SF - SUMIFLEX EL SALVADOR</option>
				<option value="282">SML - SML</option>
				<option value="283">SS - SACOS SINTETICOS CENTROAMERICANOS, S.A. DE C.V.</option>
				<option value="284">STJ - ST JACKS</option>
				<option value="285">TC - TECUN S.A. DE C.V.</option>
				<option value="286">TE - TERMOENCOGIBLE</option>
				<option value="315">TM - TAMOSA DE CV</option>
				<option value="296">TP - TAPAMETAL</option>
				<option value="287">TT - TOTO</option>
				<option value="288">UCA - UCA</option>
				<option value="312">UHI - UH INTERNACIONAL</option>
				<option value="289">VT - VIMTAZA</option>
				<option value="290">WB - WALTER BARRIENTOS</option>
			</select>
			<br />

		</div>
		<div class="ruta_elementos_divs">
			<div class="ruta_departamento" info="">Arte</div>
			<div class="ruta_departamento" info="">PrePrensa</div>
			<div class="ruta_departamento" info="">Supervisi&oacute;n</div>
			<div class="ruta_departamento" info="">Planchas</div>
			<div class="ruta_departamento" info="">Administraci&oacute;n</div>
			<div class="ruta_departamento" info="">Despacho</div>
		</div>
	</div>

	<div id="ruta_contenedor">
		<div id="envoltura_ruta">
			<span>&#x27F7;</span>
			<div class="ruta-dinamica-contenedor" id="ruta_grafico">
			</div>
		</div>
	</div>

</div>


<script type="text/javascript">

	$(document).ready(function()
	{


		$('#envoltura_ruta').draggable({
			axis: "x",
			handle: "span"
		});

		var Inicio_Fin = {
			operators: {
				operator1: {
					top: 10,
					left: 10,
					properties: {
						title: '',
						inputs: {},
						outputs: {
							output_1: {
								label: 'Inicio'
							}
						}
					}
				},
				operator2: {
					top: 10,
					left: 740,
					properties: {
						title: '',
						inputs: {
							input_1: {
								label: 'Fin'
							}
						},
						outputs: {}
					}
				}
			}
		};

		// Apply the plugin on a standard, empty div...
		var $flowchart = $('#ruta_grafico');
		var $container = $flowchart.parent();

		$flowchart.flowchart(
		{
			data: Inicio_Fin
		});



		$(document).keyup(function(tecla)
		{
			if('46' == tecla.which || '8' == tecla.which)
			{
				$flowchart.flowchart('deleteSelected');
			}
		});


		
		function getOperatorData($element)
		{

			var data ={
				properties:{
					title: '',
					inputs:{},
					outputs:{}
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




<!--

		var ruta ={};
			for(i in data.links)
			{
				ruta[i] ={
					'desde': data.links[i]['fromConnector'],
					'hacia': data.links[i]['toConnector']
				};
			}
			$('#flowchart_data').val(JSON.stringify(ruta, null, 2));
			-->