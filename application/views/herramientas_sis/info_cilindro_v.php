<script type="text/javascript" src="/html/js/info_cilindro.js?n=1"></script>
<style>
	fieldset
	{
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
	}
	
	.prueba
	{
		float: left;
		margin-left: 10px;
		width: 200px;
	}
	
	.tamanho
	{
		float: right;
		margin-right: 80px;
	}
	
	.celda
	{
		width: 230px;
		text-align: center;
	}
</style>
<?php
	$polimero = array('1' => '0.045', '2' => '0.067', '3' => '0.107', '4' => '0.112');
	$stickyback = array('1' => '0.015', '2' => '0.020', '3' => '0.060');
?>
<div class='contenido'>
	<fieldset style='border: solid 1px #aaaabb; width: 99%;'>
		<legend>&nbsp;Calcular largo de Impresi&oacute;n &nbsp;</legend>
		<form method='post' id='cilindro' action='/herramientas_sis/info_cilindro/index'>
			<strong>Ingrese el # de Milimetros.</strong> <input type='text' name='pulgas' id='pulgas' value="<?=$pulgas?>" size='7'>
			<strong>Fotopolimero</strong>
			<select name="polimero" id="polimero">
				<option value="">Polimero</option>
<?php
foreach($polimero as $Datos)
{
?>
				<option value="<?=$Datos?>" <?=($Datos==$polimero2)?' selected="selected"':''?>><?=$Datos?></option>
<?php
}
?>
			</select>
			<strong>Sticky Back</strong>
			<select name="stickyback" id="stickyback">
				<option value="0">StickyBack</option>
<?php
foreach($stickyback as $Datos)
{
?>
				<option value="<?=$Datos?>" <?=($Datos==$stickyback2)?' selected="selected"':''?>><?=$Datos?></option>
<?php
}
?>
			</select>
			<label for='mostrar_div2'>Tolerancias</label>
			<input type='checkbox' id='mostrar_div2' name='mostrar_div2' onclick="ver_tolerancia2()" <?=($mostrar_div2 != '')?' checked="checked"':''?>>
			<span class='tamanho' id='mostrar2' <?=($mostrar_div2 != '')?' style="display:block; "':'style="display:none;"'?>>
				<strong>-</strong> <input type='text' name='menos2' id='menos2' style='width: 25px;' value="<?=$menos?>">
				<strong>+</strong> <input type='text' name='mas2' id='mas2' style='width: 25px;' value="<?=$mas?>">
			</span>
			<br />
				<label for='mostrar_informacion1'>Tabla de Conversi&oacute;n de Cilindros</label>
				<input type='checkbox' id='mostrar_informacion1' onclick="ver_tabla(1)">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			<input type='button' value='Buscar' onclick='validar();'>
		</form>	
	</fieldset>
	
<?php
if(isset($Informacion_cilindro))
{
?>
<br />
<table class='tabular' style='width: 780px;'>
	<tr style='text-align: center; font-size: 10px;'>
		<th></th>
		<th>Desarrollo Total</th>
		<th>Cantidad</th>
		<th># de Repeticiones</th>
		<th>Circunferencia de la repetici&oacute;n</th>
		<th>Tipo de Manga</th>
	</tr>
<?php
$a = 0;
	foreach($Informacion_cilindro as $Datos)
	{
		$num = ($Datos['mili'] + (($stickyback2 * 25.4) * 2) + (($polimero2 * 25.4) * 2)) * 3.1416;
		$Repe = number_format($num / $pulgas, 0);
?>
	<tr style='font-size: 12px;'>
		<td class="celda"><?=number_format($num / 25.4, 2)?> in</td>
		<td class="celda"><?=number_format($num, 2)?> mm</td>
		<td class="celda"><?=$Datos['num_cilindros']?></td>
		<td class="celda"><?=$Repe?></td>
		<td class="celda"><?=number_format($num / $Repe, 3)?></td>
		<td class="celda"><?=strtoupper($Datos['tipo'])?></td>
	</tr>
<?php
	$a++;
	}
?>
	<tr>
		<td colspan='6' style='text-align: right;'>TOTAL DE TRABAJOS&nbsp;&nbsp;&nbsp;&nbsp;<strong><?=$a?></strong>&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
<?php
}
/*
 *Primera tabla de conversion
*/
?>

<div id='mostrar_tabla_conversion1' style='display: none; position: absolute; top: 120px; left: 25px; background-color: #ffffff; width: 100%;'>
	<br />
	<input type='submit' value='Ocultar Tabla' id='ocultar_tabla1' onclick='ocultar_tabla();'>
	<br />
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Desnudo</th>
			<th>0.067</th>
			<th>0.045</th>
			<th>Mangas</th>
		</tr>
<?php
$e= 1;
$fin = count($Cilindro_Desnudo);
$a = 1;
foreach($Cilindro_Desnudo as  $Datos)
{
	if($a == 26 or $a == 51 or $a == 76)
	{
?>
	</table>
<?php
	if($a == 76)
	{
?>
	<br style="clear:both;" /><br />&nbsp;
<?php
	}
?>
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Desnudo</th>
			<th>0.067</th>
			<th>0.045</th>
			<th>Mangas</th>
		</tr>
<?php
	}
?>
		<tr style='text-align: center;'>
			<td style='background-color: #feebc5;'><?=$e?></td>
			<td><?=number_format(($Datos['mili'] * 3.1416) , 3)?></td>
			<td style='background-color: #feebc5;'><?=number_format((($Datos['mili'] + (((0.067 * 2) + (0.020 * 2)) * 25.4)) * 3.1416), 3)?></td>
			<td><?=number_format((($Datos['mili'] + (((0.045 * 2) + (0.020 * 2)) * 25.4)) * 3.1416), 3)?></td>
			<td><?=$Datos['num_cilindros']?></td>
		</tr>
<?php
	
	if($a == $fin)
	{
?>
	</table>
	
<?php
	}
	$e++;
	$a++;
}
?>
<br style="clear:both;" /><br /><br />&nbsp;
</div>




<?php
/*
 *Segunda tabla de conversion

?>

<!--div id='mostrar_tabla_conversion2' style='display: none; position: absolute; top: 120px; left: 25px; background-color: #ffffff; width: 100%;'>
	<br />
		<strong>Tabla de Conversion. Cilindros Desnudos Mg_Magma</strong>
	<br />
	
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Pulgadas</th>
			<th>Milimetros</th>
		</tr>
<?php
/*
$e= 1;
$fin = count($Cilindro_Desnudo['mg_magma']);
foreach($Cilindro_Desnudo['mg_magma'] as $a => $Datos)
{
	if($e == 11 or $e == 21)
	{
?>
	</table>
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Pulgadas	</th>
			<th>Milimetros</th>
		</tr>
<?php
	}
?>
		<tr style='text-align: center;'>
			<td><?=$e?></td>
			<td><?=number_format(($Datos * 3.1416 / 25.4) , 2)?></td>
			<td><?=number_format(($Datos * 3.1416) , 2)?></td>
		</tr>
<?php
	
	if($e == $fin)
	{
?>
	</table>
<?php
	}
	$e++;
}
?>
	<input type='submit' value='Ocultar Tabla' id='ocultar_tabla2' onclick='ocultar_tabla();'>
</div>


<?php
/*
 *Tercera tabla de conversion

?>
<div id='mostrar_tabla_conversion3' style='display: none; position: absolute; top: 120px; left: 25px; background-color: #ffffff; width: 100%;'>
	<br />
		<strong>Tabla de Conversion. Cilindros Desnudos Sirio</strong>
	<br />
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Pulgadas</th>
			<th>Milimetros</th>
		</tr>
<?php
/*
$e= 1;
$fin = count($Cilindro_Desnudo['sirio']);
foreach($Cilindro_Desnudo['sirio'] as $a => $Datos)
{
	if($e == 11 or $e == 21)
	{
?>
	</table>
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Pulgadas	</th>
			<th>Milimetros</th>
		</tr>
<?php
	}
?>
		<tr style='text-align: center;'>
			<td><?=$e?></td>
			<td><?=number_format(($Datos * 3.1416 / 25.4) , 2)?></td>
			<td><?=number_format(($Datos * 3.1416) , 2)?></td>
		</tr>
<?php
	
	if($e == $fin)
	{
?>
	</table>
<?php
	}
	$e++;
}

?>
	<input type='submit' value='Ocultar Tabla' id='ocultar_tabla1' onclick='ocultar_tabla();'>
</div>

<?php
/*
$numeros = array(
1 => 45, 
2 => 46, 
3 => 48, 
4 => 49, 
5 => 50, 
6 => 52, 
7 => 53, 
8 => 54, 
9 => 55, 
10 => 56, 
11 => 58, 
12 => 60, 
13 => 61, 
14 => 62, 
15 => 63, 
16 => 64, 
17 => 66, 
18 => 68, 
19 => 70, 
20 => 72, 
21 => 74, 
22 => 76, 
23 => 82, 
24 => 84, 
25 => 86, 
26 => 88
);

$cantidaaa = array(
1=>10,
2=>20,
3=>30,
4=>10,
5=>30,
6=>30,
7=>10,
8=>20,
9=>10,
10=>40,
11=>10,
12=>20,
13=>10,
14=>20,
15=>20,
16=>30,
17=>30,
18=>10,
19=>10,
20=>10,
21=>20,
22=>10,
23=>10,
24=>10,
25=>10,
26=>20
);

echo '<br >';	
foreach($numeros as $a => $datos)
{
	$diamas = ($datos * 10 / 3.1416) - ((0.045 * 25.4) * 2) - ((0.020 * 25.4) * 2);
	
	echo 'insert into info_cilindronew values(null, "'.$diamas.'",'.$cantidaaa[$a].',"mg_magma"); <br />';
}

echo '<br>';


$numeros = array(
1 => 36,
2 => 42,
3 => 44,
4 => 45,
5 => 46,
6 => 48,
7 => 50,
8 => 52,
9 => 53,
10 => 54,
11 => 56,
12 => 57,
13 => 58,
14 => 60,
15 => 62,
16 => 63,
17 => 64,
18 => 66,
19 => 68
);


$cantidaaa = array(
1 => 8 ,
2 => 8 ,
3 => 8 ,
4 => 18 ,
5 => 26 ,
6 => 16 ,
7 => 18 ,
8 => 18 ,
9 => 16 ,
10 => 8 ,
11 => 18 ,
12 => 16 ,
13 => 8 ,
14 => 16 ,
15 => 16 ,
16 => 8 ,
17 => 16 ,
18 => 16 ,
19 => 8 
);

foreach($numeros as $a => $datos)
{
	$diamas = ($datos * 10 / 3.1416) - (0.112 * 25.4* 2) - (0.020 * 25.4 * 2);
	
	echo 'insert into info_cilindronew values(null, "'.$diamas.'",'.$cantidaaa[$a].',"sirio"); <br />';
	//echo $a.'-- '.$datos.' -- '.($datos / 3.1416).' -- '.($diamas).' -- '.($diamas / 10).'<br />';
}
*/
?>