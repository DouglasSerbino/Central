<style>
	.nombre_material{
		border: none;
		border-bottom: 1px solid #555555;
	}
	.nombre_material:focus{
		border: none;
		background-color: #fff;
		border-bottom: 1px solid #555555;
	}
	.nombre_material:disabled{
		background: none;
	}
	table{
		line-height: 10px;
	}
</style>

<script type="text/javascript" src="/html/js/req_sel_add.js?n=1"></script>
<script type="text/javascript" src="/html/js/detalle.js?n=1"></script>

<script>
	function validar_info()
	{
		var select = $("select option:selected").val();
	
		if($('#codigo_material_0').val() != '')
		{
			if($('#cantidad_material_0').val() != '')
			{
				if(select != '')
				{
					$('form').submit();
				}
				else
				{
					alert('Debe seleccionar el tipo');
					$('#tipo_0').focus();
					return false;
				}
			}
			else
			{
				alert('Debe de ingresar una cantidad');
				$('#cantidad_material_0').focus();
				return false;
			}
		}
		else
		{
			alert('Debe de ingresar un codigo');
			$('#codigo_material_0').focus();
			return false;
		}
	}
</script>

<form name="form" id='form' action="/herramientas_sis/sol_material/solicitar_material/" method="post">
	<table>
		<tr>
			<th style='text-align: center;'>Material</th>
			<th>Descripci&oacute;n</th>
			<th>Cantidad</th>
			<th style='text-align: center;'>Tipo</th>
			<th>Observaciones</th>
		</tr>
<?php
$tipos = array('1' => 'PULGADAS', '2' => 'PIEZAS', '3' => 'PLIEGOS', '4' => 'UNIDADES', '5' => 'GALONES','6' => 'CAJAS');
$opciones = '';
for($a = 1; $a <= 6; $a++)
{
	$opciones .= '<option value='.$tipos[$a].'>'.$tipos[$a].'</option>';
}
?>
		<tr>
			<td style='text-align: center;'>
				<input class="nombre_material" type="text" size="12" name="codigo_material_0" id="codigo_material_0" onblur="ver_material('_0')" />
			</td>
			<td>
				<input class="nombre_material" type="text" size="40" name="nombre_material_0" id="nombre_material_0" value="" readonly="readonly" />
				<input type="hidden" id="id_material_0" name="id_material_0" value="" />
			</td>
			<td>
				<input class="nombre_material" type="text" size="12" name="cantidad_material_0" id="cantidad_material_0" />
			</td>
			<td style='text-align: center;'>
				<select name="tipo_0" id="tipo_0">
				<option value=''>&nbsp;&nbsp;--</option>
					<?=$opciones?>
				</select>
			</td>
			<td>
				<input type='text' name='observacion' id='observacion' size='40px'>
			</td>
		</tr>
		<tr>
			<td colspan='3'>
				<br />
				<input type='button' value='Guardar' onclick='validar_info();'>
			</td>
		</tr>
	</table>
</form>

<?php
$info = array();
foreach($MatOperador as $Datos)
{
	if(($Datos['id_usuario'] == $this->session->userdata('id_usuario')) or 'Plani' == $this->session->userdata('codigo') or 'Sistemas' == $this->session->userdata('codigo'))
	{
		$info[$Datos['activo']][$Datos['id_solicitud']]['id_solicitud'] = $Datos['nombre_material'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['codigo_sap'] = $Datos['codigo_sap'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['nombre'] = $Datos['nombre_material'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['cantidad'] = $Datos['cantidad'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['fecha'] = $Datos['fecha'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['tipo'] = $Datos['tipo'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['observaciones'] = $Datos['observaciones'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['deshabilitado'] = $Datos['deshabilitado'];
		$info[$Datos['activo']][$Datos['id_solicitud']]['usuario'] = $Datos['usuario'];
	}
}
?>

<br />
<strong>Pedidos Activos</strong>
<table class='tabular' style='width: 95%;'>
	<tr>
		<th>Codigo</th>
		<th>Nombre del Material</th>
		<th>Cantidad</th>
		<th>Tipo</th>
		<th>Responsable</th>
		<th>Fecha</th>
		<th>Observaciones</th>
	</tr>
	<tr>
<?php
if(isset($info['s']))
{
	foreach($info['s'] as $tipo => $Datos)
	{
?>
	<tr>
		<td><?=$Datos['codigo_sap']?></td>
		<td><?=$Datos['nombre']?></td>
		<td><?=$Datos['cantidad']?></td>
		<td><?=$Datos['tipo']?></td>
		<td><?=$Datos['usuario']?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha'])?></td>
		<td style='width: 20%;'><?=$Datos['observaciones']?></td>
	</tr>
<?php
	}
}
?>
	</tr>
</table>

<br /><br />

<strong>Pedidos Inactivos</strong>
<table class='tabular' style='width: 95%;'>
	<tr>
		<th>Codigo</th>
		<th>Nombre del Material</th>
		<th>Cantidad</th>
		<th>Tipo</th>
		<th>Responsable</th>
		<th>Fecha</th>
		<th>Observaciones</th>
		<th>Desh</th>
	</tr>
	<tr>
<?php
if(isset($info['n']))
{
	foreach($info['n'] as $tipo => $Datos)
	{
?>
	<tr>
		<td><?=$Datos['codigo_sap']?></td>
		<td><?=$Datos['nombre']?></td>
		<td><?=$Datos['cantidad']?></td>
		<td><?=$Datos['tipo']?></td>
		<td><?=$Datos['usuario']?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha'])?></td>
		<td style='width: 20%;'><?=$Datos['observaciones']?></td>
		<td><?=(''!=$Datos['deshabilitado'])?'S':''?></td>
	</tr>
<?php
	}
}
?>
	</tr>
</table>