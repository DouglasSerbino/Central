<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<div class="informacion">
	
	<form name="miform" method="post" id='miform' action="/planchas/plancha_buscar/index" onsubmit="return busque_f();">
		
		<strong>Digite las dimensiones:</strong> &nbsp; Alto: &nbsp; 
		<input type="text" value="<?=$Alto?>" name="alto" id='alto' size="9" /> &nbsp; Ancho: &nbsp; 
		<input type="text" value="<?=$Ancho?>" name="ancho" id='ancho' size="9" /> &nbsp; 
		<select name="codigo">
<?php
foreach($planchas as $Datos)
{
	$cod_plancha = $Datos["cod_plancha"];
?>
			<option value="<?=$cod_plancha?>"<?=($Codigo == $cod_plancha)?' selected="selected"':''?>><?=$Datos["grosor"]."&nbsp;".$Datos["tipo"]?></option>
<?phpphp
}

?>
		</select>
		<input type="submit" class="boton" value="Buscar" />
		
	</form>
<?php
$grosor = '';
$tipo = '';
$ubicacion = '';
if($buscar == "1")
{
	foreach($plancha_especifica as $Datos)
	{
		$grosor = $Datos["grosor"];
		$tipo = $Datos["tipo"];
		$ubicacion = $Datos["ubicacion"];
	}
?>
	
	<hr width="80%" />
	
	<strong>Plancha: <?php echo "$grosor &nbsp; $tipo &nbsp; $ubicacion"; ?> &nbsp; &nbsp; C&oacute;digo: <?php echo $Codigo; ?></strong>
	<table class='tabular' style='width: 50%;'>
		<tr>
			<th >Cantidad</th>
			<th>Ancho</th>
			<th>Alto</th>
			<th>Subtotal</th>
			<th>Tipo</th>
			<th>Fecha</th>
		</tr>
<?php
	foreach($retazos as $Datos)
	{
		if($Datos['cantidad'] != 0)
		{
?>
		<tr>
			<td><strong><a href="/planchas/plancha_mod/index/<?=$Datos["codigo"]?>" title="Modificar Retazos"><?=$Datos["cantidad"]?></a></strong></td>
			<td><?=$Datos["ancho"]?></td>
			<td><?=$Datos["alto"]?></td>
			<td><?=$Datos["subtotal"]?></td>
			<td><?=$Datos["nombre_tipo"]?></td>
			<td><?=$Datos['dia'].'-'.$Datos['mes'].'-'.$Datos['year'];?></td>
		</tr>
<?phpphp	
		}
	}
}
?>
	</table>

	
</div>