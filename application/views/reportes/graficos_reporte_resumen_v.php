<script>
function grafico()
{
	var entregas_tiempo = [];
	var entregas_atrasadas = [];
	var reprocesos = [];
	var info = '';
		
<?php
$e = 1;
for($a=0; $a<=12; $a++)
{
	if($a!=0)
	{
?>
		entregas_tiempo.push([<?=($a)?>,<?=(isset($Informacion_reportes[$a]) and $Informacion_reportes[$a]['ttiempo'] != 0 and $Informacion_reportes[$a]['ttrabajos'] != 0)?number_format(($Informacion_reportes[$a]['ttiempo'] / $Informacion_reportes[$a]['ttrabajos'] * 100) , 1):0?>]),
		entregas_atrasadas.push([<?=($a)?>,<?=(isset($Informacion_reportes[$a]) and $Informacion_reportes[$a]['tatrasados'] != 0 and $Informacion_reportes[$a]['ttrabajos'] != 0)?number_format(($Informacion_reportes[$a]['tatrasados'] / $Informacion_reportes[$a]['ttrabajos'] * 100) , 1):0?>]),
<?php	
	}
$e++;
}
?>
	info = $.plot($('#reporte-grafico'),
		[
			{
			data: [[0, 95], [12,95]],
			lines: { show: true },
			points: { show: true },
			label: 'Meta Cumplimiento <strong>95%</strong>'
			},
			{
				data: entregas_tiempo,
				points: { show: true },
				label: '% de Entregas a Tiempo'
			},
			{
				data: entregas_atrasadas,
				points: { show: true },
				label: '% de Entregas a Atrasadas'
			}
		],
		{
			xaxis:
			{
				min: 0,
				title:'Hola',
				ticks:
				[
					[0, ""],
					[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
					[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
					[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"],
					[13, ""]
				],
				max: 13
			},
			series:
			{
				lines: { show: true },
				points: { show: true }
			},
			grid: { hoverable: true },
			yaxis:
			{
				tickFormatter: function(valor, axis)
				{
					return '<label style="margin-left: -20px">'+valor+'</label>';
				},
				max: 100
			}
		}
	);
	
	function showTooltip(x, y, contents)
	{
		$('<div id="tooltip">' + contents + '</div>').css(
		{
			position: 'absolute',
			display: 'none',
			top: y + 10,
			left: x + 10,
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#fee',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}
	
	var total_item = info.getData();
	total_item = total_item.length;
	for(z = 0; z < total_item; z++)
	{
		
		$.each(info.getData()[z].data, function(i, el, infor)
		{
			if('' != el)
			{
				var o = info.pointOffset({x: el[0], y: el[1]});
				var mas = '';
				if(100 == el[1])
				{
					mas = '+';
				}
				$('<div class="data-point-label">' + el[1] + mas + '%</div>').css(
				{
					position: 'absolute',
					left: o.left + 4,
					top: o.top - 17,
					display: 'none',
					"font-size": "12px"
				}).appendTo(info.getPlaceholder()).fadeIn('slow');
			}
		});
	}
}


function grafico_extras_comp()
{
var entregas_tiempo = [];
var entregas_atrasadas = [];
var info = '';
		
<?php
$e = 1;
for($a=0; $a<=12; $a++)
{
	if($a!=0)
	{
?>
		entregas_tiempo.push([<?=($a)?>,<?=(isset($Informacion_reportes_comp[$a]) and $Informacion_reportes_comp[$a]['ttiempo'] != 0 and $Informacion_reportes_comp[$a]['ttrabajos'] != 0)?number_format(($Informacion_reportes_comp[$a]['ttiempo'] / $Informacion_reportes_comp[$a]['ttrabajos'] * 100) , 1):0?>]),
		entregas_atrasadas.push([<?=($a)?>,<?=(isset($Informacion_reportes_comp[$a]) and $Informacion_reportes_comp[$a]['tatrasados'] != 0 and $Informacion_reportes_comp[$a]['ttrabajos'] != 0)?number_format(($Informacion_reportes_comp[$a]['tatrasados'] / $Informacion_reportes_comp[$a]['ttrabajos'] * 100) , 1):0?>]),
<?php	
	}
$e++;
}
?>
	info = $.plot($('#reporte-grafico-cumpli-comp'),
	[
		{
		data: [[0, 95], [12,95]],
		lines: { show: true },
		points: { show: true },
		label: 'Meta Cumplimiento <strong>95%</strong>'
		},
		{
			data: entregas_tiempo,
			points: { show: true },
			label: '% de Entregas a Tiempo'
		},
		{
			data: entregas_atrasadas,
			points: { show: true },
			label: '% de Entregas a Atrasadas'
		}
	],
	{
		xaxis:
		{
			min: 0,
			ticks:
			[
				[0, ""],
				[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
				[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
				[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"],
				[13, ""]
			],
			max: 13
		},
		series:
		{
			lines: { show: true },
			points: { show: true }
		},
		grid: { hoverable: true },
		yaxis:
		{
			tickFormatter: function(valor, axis)
			{
				return '<label style="margin-left: -20px">'+valor+'</label>';
			},
			max: 100
		}
	});

	function showTooltip(x, y, contents)
	{
		$('<div id="tooltip">' + contents + '</div>').css(
		{
			position: 'absolute',
			display: 'none',
			top: y + 10,
			left: x + 10,
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#fee',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}
	
	var total_item = info.getData();
	total_item = total_item.length;
	for(z = 0; z < total_item; z++)
	{
		
		$.each(info.getData()[z].data, function(i, el, infor)
		{
			if('' != el)
			{
				var o = info.pointOffset({x: el[0], y: el[1]});
				var mas = '';
				if(100 == el[1])
				{
					mas = '+';
				}
				$('<div class="data-point-label">' + el[1] + mas + '%</div>').css(
				{
					position: 'absolute',
					left: o.left + 4,
					top: o.top - 17,
					display: 'none',
					"font-size": "12px"
				}).appendTo(info.getPlaceholder()).fadeIn('slow');
			}
		});
	}
}

function grafico_extras()
{
	var p = $.plot(
		$("#reporte-grafico-extras"),
		[
<?
for($a= 0; $a <= 12; $a++)
{
?>
			{ data: [[<?=$a?>, <?=(isset($Informacion_reportes[$a])?$Informacion_reportes[$a]['extras']:0)?>]], bars: { show: true } },
<?
}
?>
		],
		{
			xaxis:
			{
				min: 0,
				ticks:
					[
						[0, ""],
						[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
						[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
						[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"]
					],
				max: 13
			},
			yaxis:
			{
				tickFormatter: function(valor, axis)
				{
					return '<label style="margin-left: -25px">'+valor+'</label>';
				},
				min:0, max: <?=$Mayor_extras?>, tickSize: <?=floor($Mayor_extras/5)?>
			}
		}
	);
	
	var total_barras = p.getData();
	total_barras = total_barras.length;
	for(z = 0; z < total_barras; z++)
	{
		$.each(p.getData()[z].data, function(i, el)
		{
			var o = p.pointOffset({x: el[0], y: el[1]});
			$('<div class="data-point-label">' + formatNumber(el[1], '') + '</div>').css(
			{
				position: 'absolute',
				left: o.left + 4,
				top: o.top - 17,
				display: 'none',
				"font-size": "11px"
			}).appendTo(p.getPlaceholder()).fadeIn('slow');
		});
	}
}

	var p = $.plot(
		$("#reporte-grafico-extras-comp"),
		[
<?
for($a= 0; $a <= 12; $a++)
{
?>
			{ data: [[<?=$a?>, <?=(isset($Informacion_reportes_comp[$a])?$Informacion_reportes_comp[$a]['extras']:0)?>]], bars: { show: true } },
<?
}
?>
		],
		{
			xaxis:
			{
				min: 0,
				ticks:
					[
						[0, ""],
						[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
						[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
						[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"]
					],
				max: 13
			},
			yaxis:
			{
				tickFormatter: function(valor, axis)
				{
					return '<label style="margin-left: -25px">'+valor+'</label>';
				},
				min:0, max: <?=$Mayor_extras_comp?>, tickSize: <?=floor($Mayor_extras_comp/5)?>
			}
		}
	);
	
	var total_barras = p.getData();
	total_barras = total_barras.length;
	for(z = 0; z < total_barras; z++)
	{
		$.each(p.getData()[z].data, function(i, el)
		{
			var o = p.pointOffset({x: el[0], y: el[1]});
			$('<div class="data-point-label">' + formatNumber(el[1], '') + '</div>').css(
			{
				position: 'absolute',
				left: o.left + 4,
				top: o.top - 17,
				display: 'none',
				"font-size": "11px"
			}).appendTo(p.getPlaceholder()).fadeIn('slow');
		});
	}
	
	
function grafico_reprocesos()
{
var p = $.plot(
			$("#reporte-grafico-reprocesos"),
			[
<?
for($a= 0; $a <= 12; $a++)
{
?>
				{ data: [[<?=$a?>, <?=(isset($Informacion_reportes[$a])?$Informacion_reportes[$a]['reprocesos']:0)?>]], bars: { show: true } },
<?
}
?>
			],
			{
				xaxis:
				{
					min: 0,
					ticks:
						[
							[0, ""],
							[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
							[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
							[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"]
						],
					max: 13
				},
				yaxis:
				{
					tickFormatter: function(valor, axis)
					{
						return '<label style="margin-left: -25px">'+valor+'</label>';
					},
					min:0, max: <?=$Mayor_repro?>
				}
			}
		);
		
		var total_barras = p.getData();
		total_barras = total_barras.length;
		for(z = 0; z < total_barras; z++)
		{
			$.each(p.getData()[z].data, function(i, el)
			{
				var o = p.pointOffset({x: el[0], y: el[1]});
				$('<div class="data-point-label">' + formatNumber(el[1], '') + '</div>').css(
				{
					position: 'absolute',
					left: o.left + 4,
					top: o.top - 17,
					display: 'none',
					"font-size": "11px"
				}).appendTo(p.getPlaceholder()).fadeIn('slow');
			});
		}
}		



function grafico_reprocesos_com()
{
var p = $.plot(
			$("#reporte-grafico-repro-comp"),
			[
<?
for($a= 0; $a <= 12; $a++)
{
?>
				{ data: [[<?=$a?>, <?=(isset($Informacion_reportes_comp[$a])?$Informacion_reportes_comp[$a]['reprocesos']:0)?>]], bars: { show: true } },
<?
}
?>
			],
			{
				xaxis:
				{
					min: 0,
					ticks:
						[
							[0, ""],
							[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
							[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
							[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"]
						],
					max: 13
				},
				yaxis:
				{
					tickFormatter: function(valor, axis)
					{
						return '<label style="margin-left: -25px">'+valor+'</label>';
					},
					min:0, max: <?=$Mayor_repro_comp?>
				}
			}
		);
		
		var total_barras = p.getData();
		total_barras = total_barras.length;
		for(z = 0; z < total_barras; z++)
		{
			$.each(p.getData()[z].data, function(i, el)
			{
				var o = p.pointOffset({x: el[0], y: el[1]});
				$('<div class="data-point-label">' + formatNumber(el[1], '') + '</div>').css(
				{
					position: 'absolute',
					left: o.left + 4,
					top: o.top - 17,
					display: 'none',
					"font-size": "11px"
				}).appendTo(p.getPlaceholder()).fadeIn('slow');
			});
		}
}


function ventas_grafico()
{
		var Cafi = [];
		var Ccom = [];
		var Cdiv = [];
		var Csto = [];
		var info = '';
		
<?php
$e = 1;
for($a=0; $a<=12; $a++)
{
	if($a!=0)
	{
?>
		Cafi.push([<?=($a)?>,<?=(isset($Informacion_reportes[$a]) and $Informacion_reportes[$a]['cafi'] != 0)?$Informacion_reportes[$a]['cafi']:0?>]),
		Ccom.push([<?=($a)?>,<?=(isset($Informacion_reportes[$a]) and $Informacion_reportes[$a]['ccom'] != 0)?$Informacion_reportes[$a]['ccom']:0?>]),
		Cdiv.push([<?=($a)?>,<?=(isset($Informacion_reportes[$a]) and $Informacion_reportes[$a]['cdiv'] != 0)?$Informacion_reportes[$a]['cdiv']:0?>]),
		Csto.push([<?=($a)?>,<?=(isset($Informacion_reportes[$a]) and $Informacion_reportes[$a]['csto'] != 0)?$Informacion_reportes[$a]['csto']:0?>]),
<?php	
	}
$e++;
}
?>
	info = $.plot($('#reporte-grafico-ventas'),
		[
			{
				data: Cafi,
				points: { show: true },
				label: 'AFI'
			},
			{
				data: Ccom,
				points: { show: true },
				label: 'COM'
			},
			{
				data: Cdiv,
				points: { show: true },
				label: 'DIV'
			},
			{
				data: Csto,
				points: { show: true },
				label: 'STO'
			}
		],
		{
			xaxis:
			{
				min: 0,
				ticks:
				[
					[0, ""],
					[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
					[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
					[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"],
					[13, ""]
				],
				max: 13
			},
			series:
			{
				lines: { show: true },
				points: { show: true }
			},
			grid: { hoverable: true },
			yaxis:
			{
				tickFormatter: function(valor, axis)
				{
					return '<label style="margin-left: -45px">$'+valor+'</label>';
				},
				max: <?=$Mayor_ventas?>
			}
		}
	);
	
	
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
				opacity: 0.80,
				'z-index': 999
			}).appendTo("body").fadeIn(200);
		}
		
		
		var previousPoint = null;
		$("#reporte-grafico-ventas").bind("plothover", function (event, pos, item)
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
					showTooltip(item.pageX, item.pageY, formatNumber(y, '$'));
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
}


function ventas_grafico_comp()
{
		var Cafi = [];
		var Ccom = [];
		var Cdiv = [];
		var Csto = [];
		var info = '';
		
<?php
$e = 1;
for($a=0; $a<=12; $a++)
{
	if($a!=0)
	{
?>
		Cafi.push([<?=($a)?>,<?=(isset($Informacion_reportes_comp[$a]) and $Informacion_reportes_comp[$a]['cafi'] != 0)?$Informacion_reportes_comp[$a]['cafi']:0?>]),
		Ccom.push([<?=($a)?>,<?=(isset($Informacion_reportes_comp[$a]) and $Informacion_reportes_comp[$a]['ccom'] != 0)?$Informacion_reportes_comp[$a]['ccom']:0?>]),
		Cdiv.push([<?=($a)?>,<?=(isset($Informacion_reportes_comp[$a]) and $Informacion_reportes_comp[$a]['cdiv'] != 0)?$Informacion_reportes_comp[$a]['cdiv']:0?>]),
		Csto.push([<?=($a)?>,<?=(isset($Informacion_reportes_comp[$a]) and $Informacion_reportes_comp[$a]['csto'] != 0)?$Informacion_reportes_comp[$a]['csto']:0?>]),
<?php	
	}
$e++;
}
?>
	info = $.plot($('#reporte-grafico-ventas-comp'),
		[
			{
				data: Cafi,
				points: { show: true },
				label: 'AFI'
			},
			{
				data: Ccom,
				points: { show: true },
				label: 'COM'
			},
			{
				data: Cdiv,
				points: { show: true },
				label: 'DIV'
			},
			{
				data: Csto,
				points: { show: true },
				label: 'STO'
			}
		],
		{
			xaxis:
			{
				min: 0,
				ticks:
				[
					[0, ""],
					[1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"],
					[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Ago"],
					[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"],
					[13, ""]
				],
				max: 13
			},
			series:
			{
				lines: { show: true },
				points: { show: true }
			},
			grid: { hoverable: true },
			yaxis:
			{
				tickFormatter: function(valor, axis)
				{
					return '<label style="margin-left: -45px">$'+valor+'</label>';
				},
				max: <?=$Mayor_ventas_comp?>
			}
		}
	);
	
	
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
				opacity: 0.80,
				'z-index': 999
			}).appendTo("body").fadeIn(200);
		}
		
		
		var previousPoint = null;
		$("#reporte-grafico-ventas-comp").bind("plothover", function (event, pos, item)
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
					showTooltip(item.pageX, item.pageY, formatNumber(y, '$'));
				}
			}
			else
			{
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
}
</script>