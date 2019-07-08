<?php
$id_usuario2 = $id_usuario;
foreach($usuarios as $Datos_usuario)
{
	$usu_nombre = $Datos_usuario["nombre"];
	$dpto = $Datos_usuario["departamento"];
}

echo "<?phpxml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Agregar Horas Extras</title>
	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
	<link href="/html/css/comun.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/html/js/extra.js?n=1"></script>
	<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
	<style type="text/css">
		#observacion{
			width: 462px;
			position: absolute;
			background: #ffffff;
			border: 1px solid #f8c773;
		}
	</style>
</head>

<body>

<div class="informacion_chica">
	
	<form name="miform" id="miform" action="/extras/extra_agr_ped/extra_agr_sql" method="post">
		<input type="hidden" name="id_usuario" value="<?=$id_usuario2?>" />
<?php
$inicio = "";
$fin = "";
$id_pedido_v = array();
$comentario_v = array();

if('' != $id_extra or 0 != $id_extra)
{
	foreach($inicio_fin_extra as $Datos_extras)
	{
		$inicio = $Datos_extras["inicio"];
		$fin = $Datos_extras["fin"];
		$fecha_extras = explode('-', $Datos_extras['fecha']);
	}
?>	
			<input type='hidden' name="id_extra" value="<?=$id_extra?>" />
<?php
	foreach($comentario_extra as $Datos_comen)
	{
		$id_pedido_v[$Datos_comen['id_pedido']]['comentario'] = $Datos_comen['comentario'];
	}
}
	
?>
		<strong>Agregar Trabajos para Horas Extras &nbsp; <?=$dia.'-'.$mes.'-'.$anho?></strong>
		
		<table class="tabular">
			<tr><th colspan="2"><?=$usu_nombre.' - '.$dpto?></th></tr>
			<tr><th colspan="2">
				Inicio: <input type="text" name="inicio" id="inicio" size="6" value="<?=$inicio?>" /> &nbsp;
				Fin: <input type="text" name="fin" id="fin" size="6" value="<?=$fin?>"  /> *
				</th>
			</tr>
			<tr>
				<td colspan="2">
					<label for="trabajos_todos"><strong>Todos los Trabajos</strong></label>
					<input type="checkbox" name="trabajos_todos" id="trabajos_todos" onclick="mostrar_trabajos(<?=$dia?>,<?=$mes?>, <?=$anho?>, <?=$id_usuario2?>, <?=$id_extra?>)"<?=('ok'==$Todos)?' checked="checked"':''?> />
				</td>
			</tr>
			<tr>
				<th width="23%">Proceso</th>
				<th>Descripci&oacute;n</th>
			</tr>
<?php
$id_ped_mat_v = array();
//Pedidos que estan activos.
foreach($pedido_activo as $Datos_activo)
{
	$id_ped_mat_v[$Datos_activo['id_pedido']] = true;
}


$para_extras_v = array();
foreach($pedido_usuario as $Datos_usuario)
{
	
	$para_extras_v[$Datos_usuario['id_pedido']] = array();
	$para_extras_v[$Datos_usuario['id_pedido']]['codigo_cliente'] = $Datos_usuario['codigo_cliente'];
	$para_extras_v[$Datos_usuario['id_pedido']]['proceso'] = $Datos_usuario['proceso'];
	$para_extras_v[$Datos_usuario['id_pedido']]['nombre'] = $Datos_usuario['nombre'];
	$para_extras_v[$Datos_usuario['id_pedido']]['fecha_entrega'] = $Datos_usuario['fecha_entrega'];
	$para_extras_v[$Datos_usuario['id_pedido']]['mostrar'] = '';
	if(!isset($id_ped_mat_v[$Datos_usuario['id_pedido']]))
	{
		$para_extras_v[$Datos_usuario['id_pedido']]['mostrar'] = " style='display:none;'";
	}
	
}

$i = 0;
foreach($para_extras_v as $id_pedido => $fila_v)
{
	$codigo_cliente = $fila_v['codigo_cliente'];
	$proceso = $fila_v['proceso'];
	$nombre = $fila_v['nombre'];
	$fecha_entrega = $fila_v['fecha_entrega'];
	$mostrar = $fila_v['mostrar'];
	
	$chequeado = '';
	$clase = '';
	$comentario = '';
	$adicion = '';
	if(isset($id_pedido_v[$id_pedido]))
	{
		$chequeado = 'checked="checked"';
		$clase = 'class="selec"';
		$comentario = $id_pedido_v[$id_pedido]['comentario'];
	}
?>
	<tr id='fila<?=$i?>' <?=$clase?>>
		<td style='width:25%;'>
			<input type='hidden' name='id_pedido<?=$i?>' value='<?=$id_pedido?>' />
			<input type='checkbox' name='check<?=$i?>' id='check<?=$i?>' onclick="cambia_color('fila<?=$i?>','check<?=$i?>')" <?=$chequeado?> />
			<label for='check<?=$i?>'><?=$codigo_cliente.'-'.$proceso?></label></td>
		<td>
			<?=$nombre?> <a href="javascript:ver_observa_extra('<?=$i?>');">(*)</a>
			<input type='hidden' name='comenta_<?=$i?>' id='comenta_<?=$i?>' value='<?=$comentario?>' />
		</td>
	</tr>
<?php
	$i++;
}

$otro = array();
$come_otro = array();

if('' != $id_extra or 0 != $id_extra)
{
	foreach($comentario_extra_otro as $Datos_comen_otro)
	{
		$otro[] = $Datos_comen_otro["otro"];
		$come_otro[] = $Datos_comen_otro["comentario"];
	}
}

for($o = 0; $o < 5; $o++)
{
	$chequeado = '';
	$clase = '';
	$trabajo = '';
	$comentario = '';
	if(isset($otro[$o]))
	{
		$chequeado = 'checked';
		$trabajo = $otro[$o];
		$clase = 'class="selec"';
		$comentario = $come_otro[$o];
	}
?>
		<tr id="fila<?=$i?>" <?=$clase?>>
			<td>
				<input type="hidden" name="id_pedido<?=$i?>" value="otro<?=$i?>" />
				<input type="checkbox" name="check<?=$i?>" id="check<?=$i?>" onclick="cambia_color('fila<?=$i?>','check<?=$i?>')" <?=$chequeado?> />
				<label for="check<?=$i?>">Adicional</label>
			</td>
			<td>
				<input type="text" name="nombre<?=$i?>" id="nombre<?=$i?>" size="55" value="<?=$trabajo?>" />
				<a href="javascript:ver_observa_extra('<?=$i?>');">(*)</a>
				<input type="hidden" name="comenta_<?=$i?>" id="comenta_<?=$i?>" value="<?=$comentario?>" />
			</td>
		</tr>
<?php
	$i++;
}
?>	
		</table>
		<input type="hidden" name="total" value="<?=$i?>" />
		<input type="hidden" name="fecha" value="<?=$anho.'-'.$mes.'-'.$dia?>" />
		<input type="button" class="boton" value="Guardar" onclick="envia_form()" />
		*Ingresar los tiempos en formatos de 24 horas.
	</form>
	<div id="observacion" style="visibility:hidden;display:none;top:50px;margin-left:150px;">
		<strong>Agregar Comentario... Corto!!!!</strong><br />
		<textarea name="observa" id="observa" cols="50" rows="2"></textarea><br />
		<input type="button" class="boton" value="Cancelar" onclick="ocu_observa_extra()" /> &nbsp;
		<input type="button" class="boton" value="Guardar" onclick="gua_observa_extra()" />
		<input type="hidden" name="caja_observa" id="caja_observa" />
	</div>
</div>
</body>
</html>