<?php
	 $tipo = '';
	 $ubicacion = '';
	 $plancha = '';
?>
<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<div class="informacion">
	
	<form name="miform" action="/planchas/consultar_material/index" method="POST">
		
		Seleccione el Tipo de Plancha: &nbsp; 
		<select name="codigo">
<?php
foreach($tipo_planchas as $Datos_planchas)
{
   $cod_plancha = $Datos_planchas["cod_plancha"];
?>
			<option value='<?=$cod_plancha?>'<?=($codigo == $cod_plancha)?' selected="selected"':''?>><?=$Datos_planchas["grosor"]."&nbsp;".$Datos_planchas["tipo"]?></option>
<?php
}

?>
		</select> &nbsp; 
		<input type="hidden" name="ordenar" value="nombre_tipo" />
		<input type="submit" class="boton" value="Crear Reporte" />
		<!--input type="button" value="Guardar Detalle" onclick="plancha_historial()" class="boton" title="Esta accion afecta a todos los tipos de plancha" /-->
	</form>
<?php
//Eleccion de un tipo de plancha.
if($codigo != "0" and $codigo != 'ok')
{
	foreach($planchas_especifica as $Datos)
	{
		$plancha = $Datos["grosor"];
		$tipo = $Datos["tipo"];
		$ubicacion = $Datos["ubicacion"];
	}
?>	
	<span>Plancha: <?php echo "$plancha &nbsp; $tipo"; ?> &nbsp; C&oacute;digo: <?=$codigo?></span>
	
	<table class="tabular" style='width: 80%;'>
		<tr>
			<th colspan="4"><strong>Bodega 9000 Material en Uso. &nbsp; <?=$ubicacion?></strong></th>
			<th colspan="3" style='text-align: center;'><a href="/planchas/plancha_agr/index/<?=$codigo?>"><strong>Agregar Retazos</strong></a></th>
		</tr>
		<tr>
			<td><strong>Cantidad</strong></td>
			<td><strong>Ancho</strong></td>
			<td><strong>Alto</strong></td>
			<td><strong>Subtotal</strong></td>
			<td><strong>Total</strong></td>
			<td><a href="/planchas/consultar_material/index/<?=$codigo?>/nombre_tipo" title="Ordenar por Tipo de Plancha"><strong>Tipo</strong></a></td>
			<td><a href="/planchas/consultar_material/index/<?=$codigo?>/fecha" title="Ordenar por Fecha de Ingreso"><strong>Fecha</strong></a></td>
		</tr>
<?php
	$total = 0;
	$i = 1;
foreach($retazos_planchas as $Datos_planchas)
{
		$total1 = $Datos_planchas["total"];
		$total += $total1;
	 if($Datos_planchas['cantidad'] != 0)
	 {
?>
		<tr>
			<td style='width: 10%;'>
			<a href="/planchas/plancha_mod/index/<?=$Datos_planchas["codigo"]?>"><strong><?=$Datos_planchas["cantidad"]?></strong></a></td>
			<td><?=$Datos_planchas["ancho"]?></td>
			<td><?=$Datos_planchas["alto"]?></td>
			<td><?=$Datos_planchas["subtotal"]?></td>
			<td><?=$total1?></td>
			<td><?=$Datos_planchas["nombre_tipo"]?></td>
			<td><?=$Datos_planchas["dia"]."/".$Datos_planchas["mes"]."/".$Datos_planchas["year"]?></td>
		</tr>
<?php
	 }
	 $i++;
}
?>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td><strong><?php echo $total; ?></strong></td>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table>
<?php
}
?>
</div>
