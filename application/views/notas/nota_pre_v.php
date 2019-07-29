<?php
//Establecemos la fecha actual.
$fecha = date("d-m-Y");
echo "<?phpxml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>NOTAS DE ENVIO</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
	<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
	<link href="/html/css/extra.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/html/js/envio.js?n=1"></script>
</head>
<style>
.normal
{
	width: 100%;
}
.normal th, .normal td {
  border: 1px solid #000;
}
</style>
<body>

<div id="contenedor-pagina">

<div id="encabezado"><img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" width="125" alt="<?=$this->session->userdata('grupo')?>" /></div>
<div id="titulo">NOTA DE ENVIO &nbsp; &nbsp; <?php echo $fecha; ?></div>
<br />
<?php
//Exploramos el array para obtener el nombre del cliente y el Id.
foreach($Clientes as $Datos_clientes)
{
	$cliente = $Datos_clientes["nombre"];
	$id_cliente = strtoupper($id_cliente);
}
?>
<form name="envios_form" action="/notas/nota_pre/nota_sql" method="post">
<fieldset>
<legend>Sr.(es): <?=$cliente?></legend>

<input type="hidden" name="id_cliente" id="id_cliente" value="<?=$id_cliente?>" />
	<table>
		<tr>
			<td colspan="3">Estamos remitiendo lo siguiente:</td>
		</tr>
		<tr>
			<td><strong><span>Proceso</span></strong></td>
			<td><strong><span>Cantidad &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Descripcci&oacute;n</span></strong></td
		</tr>
<?php
$i = 0;
//Exploramos el array para obtener la informacion.
foreach($especificacion as $Datos_especificacion)
{
	//Establecemos el id de la especificacion general en una variable.
		$id_especificacion_general = $Datos_especificacion["id_especificacion_general"];
		//Asignamos el id del pedido a una variable.
		$id_pedido = $pedidos[$i];
		//Mandamos un campo oculto.
?>
		<input type='hidden' name='oculto[<?=$i?>]' id='oculto<?=$i?>' value='0' />

		<tr>
			<td valign="top" style='width: 45%;'>
		<!-- Mostramos el codigo del cliente, proceso y nombre del proceso.
				Ademas mostramos la opcion para agregar una nueva caja
				para un nuevo material. -->
			<strong><?=$Datos_especificacion["codigo_cliente"]?>-<?=$Datos_especificacion["proceso"]?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:agregar(<?=$id_pedido?>,<?=$i?>);"><?=$Datos_especificacion["nombre"]?></a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			</td>		
		<td id="agrego<?=$i?>" style='width: 55%;'>
		<table>
<?php
//Verificamos si hay una hoja de especificacion para este pedido.

		if($id_especificacion_general != "")
		{
			if(count($Datos_especificacion['material_recibido'] != 0))
			{
			//Exploramos el array para mostrar los materiales recibidos.
				foreach($Datos_especificacion['material_recibido'] as $Id_material => $material_rec)
				{
				//Mostramos los materiales recibidos, ademas la opcion para eliminarlos.
?>
			<tr>
				<td>
					<span id='tr-mr_<?=$Id_material.'_'.$id_pedido?>'>
					<span class='scantidad'><input type="text" size="5" name="mr_<?=$Id_material?>_<?=$id_pedido?>" /></span>
					<span class='seliminar'><a href='javascript:borrar(0, 0, "tr-mr_<?=$Id_material?>_<?=$id_pedido?>");'> * </a></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$material_rec?>
					</span>
				</td>
			</tr>
<?php
				}
			}
		}
		//Exploramos el array para extraer los materiales de la hoja de especificaciones.
		//Estos materiales se agregan como otros.
		foreach($Datos_especificacion['material_rec_otro'] as $Id_material_rec => $Material_rec_otro)
		{
			//Verificamos si hay otros materiales recibidos.
			//Si los hay mostramos la informacion y las opciones.
			if($Material_rec_otro != "")
			{
?>
			<tr>
				<td>
					<span id='tr-rp_<?=$id_especificacion_general.'_'.$id_pedido?>'>
					<span class='scantidad'><input type="text" size="5" name="rp_<?=$id_especificacion_general.'_'.$id_pedido?>" /></span>
					<span class='seliminar'><a href='javascript:borrar(0, 0, "tr-rp_<?=$id_especificacion_general.'_'.$id_pedido?>");'> * </a></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$Material_rec_otro?>
					</span>
				</td>
			</tr>
<?php
			}
		}
		//Exploramos el array para obtener los materiales solicitados.
		//Verificamos si hay materiales para mostrar.
		//Si los hay los mostramos la informacion y las opciones.
		foreach($Datos_especificacion['material_sol_otro'] as $Id_material_sol_otro => $Material_sol_otro)
		{
			if($Material_sol_otro != "")
			{
?>
			<tr>
				<td>
				<span id='tr-sp_<?=$id_especificacion_general.'_'.$id_pedido?>'>
					<span class='scantidad'><input type="text" size="5" name="sp_<?=$id_especificacion_general.'_'.$id_pedido?>" /></span>
					<span class='seliminar'><a href='javascript:borrar(0, 0, "tr-sp_<?=$id_especificacion_general.'_'.$id_pedido?>");'> * </a></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$Material_sol_otro?>
					</span>
				</td>
			</tr>
<?php	
			}
		}
		//Verificamos si en la hoja de planificacion hay materiales solicitados.
		if($id_especificacion_general != "")
		{
			//Exploramos el array para conocer los materiales solicitados.
			foreach($Datos_especificacion['material_solicitado'] as $Id_material_sol => $material_sol)
			{
				//Si hay materiales los mostramos, ademas mostramos las opciones. Eliminar y cantidad.
?>
			<tr>
				<td>
					<span id='tr-ms_<?=$Id_material_sol.'_'.$id_pedido?>'>
					<span class='scantidad'><input type="text" size="5" name="ms_<?=$Id_material_sol.'_'.$id_pedido?>" /></span>
					<span class='seliminar'><a href='javascript:borrar(0, 0, "tr-ms_<?=$Id_material_sol.'_'.$id_pedido?>");'> * </a></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$material_sol?>
					</span>
				</td>
			</tr>
<?php
			}
		}
?>
		</table>
			</td>
		</tr>
<?php
	$i++;
	}
?>
		<tr>
			<td colspan="3"><input type="button" onclick="guarda_imprime()" value="Guardar e Imprimir" /></td>
		</tr>
		</table>
</div>

</form>
</fieldset>
<br />
</div>

</body>

</html>