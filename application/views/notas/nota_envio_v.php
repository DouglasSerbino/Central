<?php
$fecha = date("d-m-Y");
?>
<div class="informacion">
	
	<div class="informacion_top"><div></div></div>
	
	<div class="inf_titulo">Nota de Env&iacute;o -  &nbsp; Fecha: <? echo $fecha; ?></div>
	
	<div class="informacion_cont">
<?
//Exploramos el array para mostrar los datos del cliente.
	foreach($Clientes as $Datos_clientes)
	{
		$cliente = $Datos_clientes["nombre"];
		$id_cliente = strtoupper($id_cliente);
	}
?>
<table>
	<tr>
		<th colspan="2">Sr.(es): <?=$cliente?>.</th>
	</tr>
	<tr>
		<td colspan="2">Estamos remitiendo lo siguiente:</td>
	</tr>
	<tr>
		<th>Proceso:</th>
		<th>Descripcci&oacute;n:</th>
	</tr>
<?php
$id_pedido = '';
//Exploramos el array para obtener los id de los pedidos sin lo guiones.
foreach($pedidos as $pedido)
{
	$id_pedido .= $pedido.'-';
}
//Exploramos el array para mostrar la informacion.
//print_r($especificacion);
foreach($especificacion as $Datos_especificacion)
{
		$id_especificacion_general = $Datos_especificacion["id_especificacion_general"];
		
		//Declaramos la variables que utilizaremos.
		$trabajo_i = "";
		$recibido = "";
		$recibido_o = "";
		$solicitado = "";
		$solicitado_o = "";

		//Asignamos los procesos a una variable
		$trabajo_i .= "<tr><td><strong>".$Datos_especificacion["proceso"]."</strong></td><td><strong>".$Datos_especificacion["nombre"]."</strong></td></tr>\n";
		
		//Verificamos si hay una hoja de especificaciones.
		if($id_especificacion_general != "")
		{
			//Si la hay exploramos el array y asignamos la informacion a una variable.
			foreach($Datos_especificacion['material_recibido'] as $Id_material => $material_rec)
			{
				$recibido .=  "<tr><td>&nbsp;</td><td>".$material_rec."</td></tr>\n";
			}
		}
		
		//Verificamos si hay una hoja de especificaciones.
		if($id_especificacion_general != "")
		{
			//Exploramos el array y asignamos la informacion a una variable.
			foreach($Datos_especificacion['material_rec_otro'] as $Id_material_rec => $Material_rec_otro)
			{
				//Verificamos si hay informacion.
				if($Material_rec_otro != "")
				{
					$recibido_o = "<tr><td>&nbsp;</td><td>".$Material_rec_otro."</td></tr>\n";
				}
			}
			//Exploramos el array y asignamos la informacion a una variable.
			foreach($Datos_especificacion['material_sol_otro'] as $Id_material_sol_otro => $Material_sol_otro)
			{
				//Verificamos si hay informacion.
				if($Material_sol_otro != "")
				{
					$solicitado_o = "<tr><td>&nbsp;</td><td>".$Material_sol_otro."</td></tr>\n";
				}
			}
		}
		
		//Verificamos si hay una hoja de especificaciones.
		if($id_especificacion_general != "")
		{
			//Exploramos el array y asignamos la informacion a una variable.
			foreach($Datos_especificacion['material_solicitado'] as $Id_material_sol => $material_sol)
			{
				$solicitado .= "<tr><td>&nbsp;</td><td>".$material_sol."</td></tr>\n";
			}
		}
	//Imprimimos las variables.	
		echo $trabajo_i;
		echo $recibido;
		echo $recibido_o;
		echo $solicitado;
		echo $solicitado_o;	
		
//fin Bucle FOR
}
?>
	</table>
	
	<br /><strong><a href="/notas/nota_pre/index/<?=$cajas.'/'.$id_cliente.'/'.$id_pedido?>" target="_blank">Versi&oacute;n para Imprimir</a></strong>
	
	</div>
	
	<div class="informacion_bot"><div></div></div>
	
</div>