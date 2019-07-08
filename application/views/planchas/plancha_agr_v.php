<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<?php

//=============Ingeso de Retazos==================
foreach($planchas_especifica as $Datos)
{
	$altura = $Datos["grosor"];
	$tipo = $Datos["tipo"];
}
//=========================================================

?>

<div class="informacion">
	<form name="miform" id='guardar' method="post" action="/planchas/plancha_agr/agregar_planchas" onsubmit="return validar('guardar');">
		
		<strong>Ingresar: &nbsp; <?php echo "$altura &nbsp; $tipo"; ?></strong><br />
		
		<table>
			<tr>
				<td>Cantidad:</td>
				<td><input type="text" value="" name="cantidad" id='cantidad' class="requ" onblur='agregar_f();' />*</td>
				<td>Tipo:</td>
				<td>
					<select name="tipo">
<?php
foreach($plancha_tipo as $Datos)
{
?>
						<option value="<?=$Datos["cod_tipo"]?>"><?=$Datos["nombre_tipo"]?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Ancho:</td>
				<td><input type="text" value="" name="ancho" class="requ" id='ancho' onblur='agregar_f();' />*</td>
				<td>Alto:</td>
				<td><input type="text" value="" name="alto" class="requ" id='alto' onblur='agregar_f();' />*</td>
			</tr>
			<tr>
				<td colspan="4">
					<input type="hidden" value="<?=$codigo?>" name="codigo" id='codigo' />
					<input type="button" class="boton" value="Regresar" onclick="de_regre('<?=$codigo?>')" /> &nbsp; 
					<input type="submit" class="boton" value="Agregar" />
				</td>
			</tr>
		</table>
		
	</form>
	
</div>