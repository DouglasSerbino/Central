<form id="crear_usuario" action="/usuarios/crear/usuario" method="post" onsubmit="return validar('crear_usuario')">
	<table>
		<tr>
			<td>Oficina</td>
			<td colspan="3">
				<select name="upais">
<?php
foreach($Paises_C as $pCod => $pNomb)
{
?>
					<option value="<?=$pCod?>"><?=$pNomb?></option>
<?php
}
?>
				</select>
				<span class="pais_sv" id="span_pais"></span>
			</td>
		</tr>
		<tr>
			<td>Usuario</td>
			<td><input type="text" name="usuario" value="" class="requ" />*</td>
		</tr>
		<tr>
			<td>Contrase&ntilde;a</td>
			<td><input type="text" name="password" value="" class="requ" />*</td>
		</tr>
		<tr>
			<td>C&oacute;digo de empleado</td>
			<td><input type="text" name="cod_empleado" value="" class="requ" />*</td>
		</tr>
		<tr>
			<td>Nombre</td>
			<td><input type="text" name="nombre" value="" class="requ" size="45" />*</td>
		</tr>
		<tr>
			<td>Puesto</td>
			<td><input type="text" name="puesto" value="" class="requ" />*</td>
		</tr>
		<tr>
			<td>Departamento</td>
			<td>
				<select name="departamento">
<?php
foreach($Departamentos as $Dpto){
?>
					<option value="<?=$Dpto['id_dpto']?>"><?=$Dpto['departamento']?> [<?=$Dpto['codigo']?>]</option>
<?php
}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>email</td>
			<td><input type="text" name="email" value="" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="Guardar" /></td>
		</tr>
	</table>
	
	<br />
	* Campos requeridos
</form>


<script>
	$('[name="upais"]').change(function()
	{
		$('#span_pais').removeClass();
		$('#span_pais').addClass('pais_'+$(this).val());
	});
</script>