<style type="text/css">
	.info_cuerpo{
		padding-top: 15px;
		padding-bottom: 15px;
		position: relative;
		background: #dddddd;
		border-radius: 5px;
		border: 1px solid #aaaaaa;
	}
	.info_titulo{
		color: #424242;
		font-size: 22px;
		margin-left: 10px;
		font-weight: bolder;
	}
	.info_etiqueta{
		width: 900px;
		height: 150px;
		position: relative;
		margin-bottom: 25px;
	}
	.info_texto{
		top: 0px;
		left: 35px;
		width: 773px;
		height: 148px;
		overflow-y: scroll;
		padding-left: 90px;
		position: absolute;
		background: #fefefe;
		border: 1px solid #dddddd;
		box-shadow: 4px 5px 5px #aaaaaa;
	}
	.info_numero{
		top: 15px;
		left: 10px;
		width: 110px;
		height: 120px;
		position: absolute;
		background: #d7aa1f;
		box-shadow: 3px 3px 5px #9d7b15;
	}
	.info_concepto{
		color: #fefefe;
		padding-top: 20px;
		line-height: 13px;
		font-weight: bold;
		letter-spacing: 1px;
		text-align: center;
	}
	.info_valor{
		color: #fefefe;
		padding-top: 5px;
		font-size: 40px;
		font-weight: bold;
		text-align: center;
	}
	.info_finalizados, .info_finalizados .info_texto{
		height: 200px;
	}

	.info_pendientes .info_numero{
		background: #3592a0;
		box-shadow: 3px 3px 5px #184950;
	}

	.info_reprocesos .info_numero{
		background: #57427e;
		box-shadow: 3px 3px 5px #2f2641;
	}

	.info_extras .info_numero{
		background: #c13835;
		box-shadow: 3px 3px 5px #79302f;
	}
</style>

<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<!--script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script-->
<script type="text/javascript" src="/html/js/venta.js?n=1"></script>


<div class="no_imprime">
	B&uacute;squeda: 
	<select id="puesto">
		<option value="0">Seleccionar</option>
<?php
foreach($Usuarios as $Dpto_Usuarios)
{
	if(
		'Gerente de Grupo' != $Dpto_Usuarios['dpto']
		&& 'Sistemas Inform&aacute;ticos' != $Dpto_Usuarios['dpto']
		&& 'Planificaci&oacute;n' != $Dpto_Usuarios['dpto']
		&& 'Ventas' != $Dpto_Usuarios['dpto']
		&& 'Grupo Externo' != $Dpto_Usuarios['dpto']
	)
	{
		foreach($Dpto_Usuarios['usuarios'] as $IUsuario => $NUsuario)
		{
?>
		<option value="<?=$IUsuario?>"<?=($Id_Usuario==$IUsuario)?' selected="selected"':''?>><?=$NUsuario['usuario']?></option>
<?php
		}
	}
}
?>
	</select>

	<select id="mes">
<?php
foreach($Meses as $iMes => $nMes)
{
?>
		<option value="<?=$iMes?>"<?=($iMes==$Mes)?' selected="selected"':''?>><?=$nMes?></option>
<?php
}
?>
	</select>

	<input type="text" id="anho" placeholder="A&ntilde;o" value="<?=$Anho?>" size="5" />

	<input type="button" id="btn_ir" value="Mostrar" />
	
</div>




<br />
<?php
if(0 < $Id_Usuario)
{
?>
<div class="info_cuerpo">

	<div class="info_titulo"><?=$Usuario[0]['nombre']?> - <?=$Meses[$Mes]?> / <?=$Anho?></div>

	<br />

<?php
if($Mes == date('m') && $Anho == date('Y'))
{
?>
	<div class="info_etiqueta info_pendientes">
		<div class="info_texto">
			<table style="width: 100%;" id="ver_carga" class="tabular">
				<thead>
					<tr>
						<th>Proceso</th>
						<th>Trabajo</th>
						<th>Ingreso</th>
						<th>Entrega</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div class="info_numero">
			<div class="info_concepto">Trabajos Asignados</div>
			<div class="info_valor"></div>
		</div>
	</div>
<?php
}
?>

	<div class="info_etiqueta info_finalizados">
		<div class="info_texto">
			<table style="width: 100%;" id="ver_carga_fin" class="tabular">
				<thead>
					<tr>
						<th>Proceso</th>
						<th>Trabajo</th>
						<th>Ingreso</th>
						<th>Entrega</th>
						<th>Entregado</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div class="info_numero">
			<div class="info_concepto">Trabajos Realizados</div>
			<div class="info_valor"></div>
		</div>
	</div>


	<div class="info_etiqueta info_reprocesos">
		<div class="info_texto">
			<table style="width: 100%;" id="ver_reprocesos" class="tabular">
				<thead>
					<tr>
						<th>Proceso</th>
						<th>Trabajo</th>
						<th>Raz&oacute;n</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div class="info_numero">
			<div class="info_concepto">Rechazos Internos</div>
			<div class="info_valor"></div>
		</div>
	</div>


	<div class="info_etiqueta info_extras">
		<div class="info_texto">
			<table style="width: 100%;" id="ver_extras" class="tabular">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Inicio</th>
						<th>Fin</th>
						<th>Horas</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div class="info_numero">
			<div class="info_concepto">Horas Extras</div>
			<div class="info_valor"></div>
		</div>
	</div>
	
</div>
<?php
}
?>


<script language='javascript' type='text/javascript'>

$('#btn_ir').click(function()
{
	if(0 == $('#puesto').val())
	{
		$('#puesto').focus();
		return false;
	}


	if('' == $('#anho').val())
	{
		$('#anho').focus();
		return false;
	}

	window.location = '/reportes/infografia/puestos/'+$('#puesto').val()+'/'+$('#anho').val()+'/'+$('#mes').val();
});

<?php
if(0 < $Id_Usuario)
{
?>
	$.ajax({
		type: "POST",
		url: "/carga/trabajos/finalizado/<?=$Id_Usuario?>/<?=$Anho?>/<?=$Mes?>",
		success: function(msg)
		{

			var Informacion = JSON.parse(msg);

			$('.info_finalizados .info_valor').append(Object.keys(Informacion).length);

			var Fila = '';

			for(i in Informacion)
			{
				Fila = '<tr>';
				Fila = Fila + '<td>';
				Fila = Fila + '<a href="/pedidos/especificacion/ver/'+Informacion[i].id_pedido+'/n" target="_blank" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>';
				Fila = Fila + '<strong><a href="/pedidos/pedido_detalle/index/'+Informacion[i].id_pedido+'" target="_blank">';
				Fila = Fila + Informacion[i].codigo_cliente+'-'+Informacion[i].proceso;
				Fila = Fila + '</a></strong></td>';
				Fila = Fila + '<td>'+Informacion[i].nombre+'</td>';
				Fila = Fila + '<td>'+Informacion[i].fecha_entrada+'</td>';
				Fila = Fila + '<td>'+Informacion[i].fecha_entrega+'</td>';
				Fila = Fila + '<td>'+Informacion[i].fecha_reale+'</td>';
				Fila = Fila + '</tr>';

				$('#ver_carga_fin tbody').append(Fila);
			}

			ver_reprocesos();
		},
		error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
	});
	

<?php
	if($Mes == date('m') && $Anho == date('Y'))
	{
?>
	$.ajax({
		type: "POST",
		url: "/carga/trabajos/listar/<?=$Id_Usuario?>",
		success: function(msg)
		{
			var Informacion = JSON.parse(msg);

			$('.info_pendientes .info_valor').append(Object.keys(Informacion).length);

			var Fila = '';

			for(i in Informacion)
			{
				Fila = '<tr>';
				Fila = Fila + '<td>';
				Fila = Fila + '<a href="/pedidos/especificacion/ver/'+Informacion[i].id_pedido+'/n" target="_blank" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>';
				Fila = Fila + '<strong><a href="/pedidos/pedido_detalle/index/'+Informacion[i].id_pedido+'" target="_blank">';
				Fila = Fila + Informacion[i].codigo_cliente+'-'+Informacion[i].proceso;
				Fila = Fila + '</a></strong></td>';
				Fila = Fila + '<td>'+Informacion[i].nombre+'</td>';
				Fila = Fila + '<td>'+Informacion[i].fecha_entrada+'</td>';
				Fila = Fila + '<td>'+Informacion[i].fecha_entrega+'</td>';
				Fila = Fila + '</tr>';

				$('#ver_carga tbody').append(Fila);
			}


		},
		error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
	});
<?php
	}
?>
	
	//Inicio ver_reprocesos();
	function ver_reprocesos()
	{
		$.ajax({
			type: "POST",
			url: "/carga/trabajos/rechazos/<?=$Id_Usuario?>/<?=$Anho?>/<?=$Mes?>",
			success: function(msg)
			{

				var Informacion = JSON.parse(msg);

				$('.info_reprocesos .info_valor').append(Object.keys(Informacion).length);

				var Fila = '';

				for(i in Informacion)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>';
					Fila = Fila + '<a href="/pedidos/especificacion/ver/'+Informacion[i].id_pedido+'/n" target="_blank" class="iconos idocumento toolizq"><span>Hoja de Planificaci&oacute;n</span></a>';
					Fila = Fila + '<strong><a href="/pedidos/pedido_detalle/index/'+Informacion[i].id_pedido+'" target="_blank">';
					Fila = Fila + Informacion[i].codigo_cliente+'-'+Informacion[i].proceso;
					Fila = Fila + '</a></strong></td>';
					Fila = Fila + '<td>'+Informacion[i].nombre+'</td>';
					Fila = Fila + '<td>'+Informacion[i].explicacion+'</td>';
					Fila = Fila + '</tr>';

					$('#ver_reprocesos tbody').append(Fila);
				}

				ver_extras();
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	}
	//Fin ver_reprocesos();

	
	//Inicio ver_extras();
	function ver_extras()
	{
		$.ajax({
			type: "POST",
			url: "/carga/trabajos/extras/<?=$Id_Usuario?>/<?=$Anho?>/<?=$Mes?>",
			success: function(msg)
			{

				var Informacion = JSON.parse(msg);

				var Fila = '';
				var Horas = 0;

				for(i in Informacion)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>'+Informacion[i].fecha+'</td>';
					Fila = Fila + '<td>'+Informacion[i].inicio+'</td>';
					Fila = Fila + '<td>'+Informacion[i].fin+'</td>';
					Fila = Fila + '<td>'+Informacion[i].total_h+'</td>';
					Fila = Fila + '</tr>';

					$('#ver_extras tbody').append(Fila);

					Horas = Horas + parseFloat(Informacion[i].total_h);
				}

				$('.info_extras .info_valor').append(Horas);

				//ver_extras();
			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	}
	//Fin ver_extras();
<?php
}
?>
	

</script>





<style>
	.datos_infografia div{
		text-align: center;
		float: left;
		width: 270px;
		padding: 10px 5px;
		margin: 5px;
		background: #f9f9f9;
		border: 2px solid #979797;
		-moz-border-radius: 5px; -khtml-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
	}
	.grande{
		font-size: 25px;
	}
	blockquote{
		border-top: 1px solid #8f8f8f;
		margin-top: 10px;
	}
</style>

<style media="print">
	.datos_infografia div{
		width: 220px;
	}
	#encabezado_ocul h1{
		color: #353941;
		font-size: 35px;
	}
</style>




