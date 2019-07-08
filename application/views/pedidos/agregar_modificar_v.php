<?php
/**
 *Nota aclaratoria:
 *En este codigo se vera mucho la condicion "isset".
 *Esta pagina sirve tanto para agregar como para modificar pedidos.
 *La condicion isset valida si estamos en agregar (FALSE) o modificar (TRUE).
 *Para que?
 *Evito tener dos paginas con el formulario y asi no olvido hacer un cambio
 *por el problema de tener dos formularios.*/
?>
<script type="text/javascript" src="/html/js/thickbox.js"></script>
<script type="text/javascript" src="/html/js/carga.js?n=1"></script>
<script type="text/javascript" src="/html/js/pedido.003.js?n=1"></script>
<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/html/css/pedido.css" />
<script>
	$('#form_scan input[type=file]').bind('change', function()
	{
		cambio_scan($(this).attr('id'));
	});
</script>
</script>

<form action="<?=$Formulario?><?=(isset($Tipo_Ir)&&'i'==$Tipo_Ir)?'/'.$Tipo_Ir:''?>" method="post" id="form_pedido">


<table>
	<tr>
		<td rowspan="2"><?php
if('' == $Miniatura)
{
	echo '&nbsp;';
}
else
{
?><img style="width: 85px;height: 85px;" src="<?=$Miniatura?>" /><?php
}
?></td>
		<td>
			<strong style="font-size:19px;"><?=$Info_Proceso['codigo_cliente'].'-'.$Info_Proceso['proceso']?></strong>
			<br />
			<strong><?=$Info_Proceso['nombre_proceso']?></strong>
			<br />
			<?=$Info_Proceso['nombre']?>
			<br />
			Fecha de Ingreso: <strong><?=date('d-m-Y')?></strong>
			&nbsp; &nbsp; Fecha de Entrega
			<strong><span id="aqui_va_la_fecha"></span></strong>
			<input type="text" id='fecha_entrega' readonly="readonly" name="fecha_entrega" size="12" value="<?=isset($Pedido['fecha_entrega'])?$this->fechas_m->fecha_ymd_dmy($Pedido['fecha_entrega']):''?>" />
			&nbsp;
			&nbsp; &nbsp; <a href="javascript:ver_agregar_scan('<?=$Info_Proceso['id_proceso']?>-imagen_proceso');" class="iconos iscan toolizq"><span>Agregar Miniatura</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Agregar&nbsp;Miniatura.</a>
		</td>
	</tr>
	<tr>
		<td>
			Tipo de trabajo
			<select name="tipo_trabajo">
<?php
//Tipo de trabajo (Modificar)
$Tipo_Trab = 0;
if(isset($Pedido['id_tipo_trabajo']))
{
	$Tipo_Trab = $Pedido['id_tipo_trabajo'];
}

foreach($Tipos_Trabajo as $Tipo)
{
?>
				<option value="<?=$Tipo['id_tipo_trabajo']?>"<?=($Tipo_Trab==$Tipo['id_tipo_trabajo'])?' selected="selected"':''?>><?=$Tipo['trabajo']?></option>
<?php
}
?>
			</select>

			<select name="id_usu_rechazo" id="id_usu_rechazo" style="display:none;">
				<option value="0">Seleccionar Responsable</option>
<?php
//Usuario responsable del Rechazo (Modificar)
$Usu_Rechazo = 0;
if(isset($Pedido['id_responsable']))
{
	$Usu_Rechazo = $Pedido['id_responsable'];
}

foreach($Usuarios as $Usuario)
{
?>
				<option value="<?=$Usuario['id_usuario']?>"<?=($Usu_Rechazo==$Usuario['id_usuario'])?' selected="selected"':''?>><?=$Usuario['usuario']?></option>
<?php
}
?>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<strong id='motivo' style='display: none;'>Motivo</strong>
			<select name='reproceso_razon' id='reproceso_razon' style='display: none;'>
<?php
$Detalle_repro = 0;
if(isset($Pedido['id_repro_deta']))
{
	$Detalle_repro = $Pedido['id_repro_deta'];
}
foreach($Detalle_reproceso as $Datos)
{
?>
				<option value='<?=$Datos['id_repro_deta']?>' <?=($Datos['id_repro_deta']==$Detalle_repro)?' selected="selected"':''?>><?=$Datos['detalle']?></option>
<?php
}

?>
			</select>
		</td>
	</tr>
</table>







