<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<?
if(count($info_retazos) != 0)
{
	foreach($info_retazos as $Datos)
	{
		$cod_plancha = $Datos["cod_plancha"];
		$altura = $Datos["grosor"];
		$tipo = $Datos["tipo"];
		$cantidad = $Datos["cantidad"];
		$ancho = $Datos["ancho"];
		$alto = $Datos["alto"];
		$tipo = $Datos["cod_tipo"];
		$tipo_pla = $Datos["tipo"];
	}

?>

<div class="informacion">
	<form name="miform" id='modificar' method="post" action="/planchas/plancha_mod/modificar_planchas" onsubmit="return validar('modificar');">
		
		<strong>Modificar: &nbsp; <?="$altura &nbsp; $tipo_pla"?></strong><br />
		
		<table>
			<tr>
				<td>Cantidad:</td>
				<td><input type="text" name="cantidad" value="<?=$cantidad?>" class="requ" onblur='agregar_f();' />*</td>
				<td>Tipo:</td>
				<td>
					<select name="tipo">
<?
foreach($plancha_tipo as $Datos)
{
	$cod_tipo = $Datos["cod_tipo"];
?>
						<option value="<?=$cod_tipo?>"<?=($tipo == $cod_tipo)?' selected="selected"':''?>><?=$Datos["nombre_tipo"]?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Ancho:</td>
				<td><input type="text"  name="ancho" class="requ" value='<?=$ancho?>' onblur='agregar_f();' />*</td>
				<td>Alto:</td>
				<td><input type="text"  name="alto" class="requ" value='<?=$alto?>' onblur='agregar_f();' />*</td>
			</tr>
			<tr>
				<td colspan="4">
					<input type="hidden" value="<?=$codigo?>" name="codigo" />
					<input type='hidden' value='<?=$cod_plancha?>' name='cod_plancha'>
					<input type="button" class="boton" value="Regresar" onclick="de_regre(<?=$cod_plancha?>)" /> &nbsp; 
					<input type="submit" class="boton" value="Modificar" />
				</td>
			</tr>
		</table>
	</form>
</div>

<?php

}
else
{
	echo '<strong>El material no existe</strong>';
}

?>