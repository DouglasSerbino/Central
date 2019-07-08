<script type="text/javascript" src="/html/js/extra.js?n=1"></script>
<style type="text/css" media="all">@import "/html/css/jquery.tooltip.screen.css";</style>
<?php
$hoy = date("Y-m-d");
if($this->session->userdata('codigo') != 'SAP')
{
?>
<div class="informacion">
	
	<div class="informacion_top"><div></div></div>
	
	<div class="informacion_cont">
		
		<a href="/extras/extras/index/<?=$anho.'/'.$mes?>" title="Regresar al Tablero de Horas Extras"><strong>&laquo;&laquo; Regresar &laquo;&laquo;</strong></a><br />
		<strong>Seleccionar Usuario</strong>
		
		<select name="id_usuario" id="id_usuario">
<?php
foreach($Usuarios as $Datos_usuario)
{
	$IdUsu = $Datos_usuario["id_usuario"];
	$nombre = $Datos_usuario["nombre"];
?>
			<option value="<?=$IdUsu?>"><?=$nombre?></option>
<?php
}
?>
		</select> &nbsp; 
		<input type="button" class="boton" id="abrir" value="Mostrar" onclick="abrir_ventana(<?=$dia.','.$mes.','.$anho?>)" />
	</div>
	<hr width="80%" />
<?php
}
$numero = count($buscar_extras);
if($numero > 0)
{
?>
	<div class="informacion_cont">
		
		<strong>Horas Extras Ingresadas <?php echo "$dia/$mes/$anho"; ?></strong>
		
		<table class="tabular">
			<tr>
				<th style='width: 15%;'>OPERADOR</th>
				<th>PROCESO</th>
				<th style='width: 42%;'>DESCRIPCI&Oacute;N</th>
				<th style='width: 12%;'>FECHA ENTREGA</th>
				<th style='width: 12%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HORA</th>
				<th>&nbsp;</th>
			</tr>
<?php
	$o = 0;
	$usu_adm = explode('-', $Administradores);
	$total_m = 0;
	foreach($buscar_extras as $Datos_extras)
	{
		$id_extra = $Datos_extras["id_extra"];
		$id_usuario_v = $Datos_extras["id_usuario"];
		$nombre = $Datos_extras["nombre"];
		$hora_v = $Datos_extras["hora"];
		$inicio = $Datos_extras["inicio"];
		$fin = $Datos_extras["fin_real"];
		$total_h = $Datos_extras["total_h"];
		$total_m = $Datos_extras["total_m"];
		$usu_adm_v = "[ ".$usu_adm[$o]." ]";
		
?>
			<tr>
				<td colspan="2">
<?php
if($this->session->userdata('codigo') != 'SAP')
{
		$ventana = "abrir_ventana2";
		 $fecha_comparar = $anho.'-'.$mes.'-'.$dia;
		 if($fecha_comparar < $hoy)
		{
			$ventana = "abrir_ventana3";
		}
		//Exploramos el array para determinar cuales extras ya tienen reportado el flete.
		foreach($Datos_extras['Flete'] as $Datos_flete)
		{
			$flete_rep = $Datos_flete['id_extra_rec'];
		}
		//Verificamos si el resultado es diferente de 0 y asi podremos
		//Mostrar la opcion de reportar el flete.
		if('' == $flete_rep or 0 == $flete_rep)
		{
			echo "<img src=\"/html/img/hoja_verde.png\" alt=\"Flete\" title=\"Reportar flete\" id=\"img-ext$o\" onclick=\"agregar_flete_extra('".$id_extra."','".$dia."','".$mes."','".$anho."')\" /> &nbsp;";
		}
		else
		{
			echo "(*) ";
		}

?>		
				<a href="javascript:<?=$ventana?>('<?=$id_extra?>','<?=$id_usuario_v?>','<?=$dia?>','<?=$mes?>','<?=$anho?>')" title="Modificar Horas">
				<strong><?=$nombre?>. &nbsp; <?=$usu_adm_v?></strong></a></td>
<?php
}
else
{
?>
				<strong><?=$nombre?></strong></a></td>
<?php
}
?>
				<td colspan="2">&nbsp;</td>
				<td><?=$inicio?> a <?=$fin?></td>
<?php
if($this->session->userdata('codigo') != 'SAP')
{
?>
				<td><a href="javascript:eliminar_e('<?=$id_extra?>','<?=$dia?>','<?=$mes?>','<?=$anho?>','<?=$nombre?>')"><strong>Eliminar</strong></a></td>
<?php
}
?>
			</tr>
<?php
//Exploramos el array con los valores que obtenemos de la tabla extra_pedido.
			foreach($Datos_extras['extra_pedido'] as $Datos_ext)
			{
				$codigo_cliente = $Datos_ext["codigo_cliente"];
				$proceso = $Datos_ext["proceso"];
				$producto = $Datos_ext["nombre_traba"];
				$fecha_entrega = $Datos_ext["fecha_entrega"];
				$comentario = $Datos_ext["comentario"];

				if($comentario != "")
				{	
					$comentario = "Nota: - $comentario";
				}
?>
				<tr>
					<td>&nbsp;</td>
					<td><strong><?=$codigo_cliente?>-<?=$proceso?></strong></td>
					<td title="<?=$comentario?>"><?=$producto?></td>
<?php
$fecha_entre = explode("-",$fecha_entrega);
?>
					<td><?=$fecha_entre[2].'-'.$fecha_entre[1].'-'.$fecha_entre[0]?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
<?php	
			}
		//Mostramos la informacion de los pedidos que no estan programados.
		foreach($Datos_extras['extra_otro'] as $Datos_extra_otro)
		{
			$otro = $Datos_extra_otro["otro"];
			$comentario = $Datos_extra_otro["comentario"];
			if($comentario != "")
			{
				$comentario = "Nota: - $comentario";
			}
?>	
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td title="<?=$comentario?>"><?=$otro?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
<?php
		}
?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td>Totales:</td>
					<td><strong><?=number_format($total_h, 2)?> horas </strong></td>
<?php
if($this->session->userdata('codigo') != 'SAP')
{
?>
					<td><strong>$ <?=number_format($total_m, 4)?></strong></td>
<?php
}
?>
				</tr>
				<tr>
					<td colspan="4">&nbsp;</td>
					<td colspan="2">
						<strong><a href="/extras/extra_imp/index/indi/<?=$dia.'/'.$mes.'/'.$anho.'/'.$id_usuario_v?>" target="_blank">Imprimir Hoja</a> &nbsp; &nbsp; &nbsp;</strong>
						<br /><br />
					</td>
				</tr>
<?php
	$o++;
	}
?>
		</table>
		<a href="/extras/extra_imp/index/todos/<?=$dia.'/'.$mes.'/'.$anho?>" target="_blank"><strong>Imprimir Hoja Completa</strong></a>
<?php
if($this->session->userdata('codigo') != 'SAP')
{
?>		
		<br />(*) Ya ha sido reportado el flete para este usuario.
<?php
}
?>
	</div>
<?php
}
?>
</div>