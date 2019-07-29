<script type="text/javascript" src="/html/js/carga.js?n=1"></script>

<!--strong style="font-size: 15px;">Informaci&oacute;n General</strong-->
<form id="modificar_usuario" action="/usuarios/modificar/usuario/<?=$Usuario[0]['id_usuario']?>" method="post" onsubmit="return validar('modificar_usuario')">
	
		<table>
		<tr>
			<td>Oficina</td>
			<td colspan="3">
				<select name="upais">
<?php
foreach($Paises_C as $pCod => $pNomb)
{
?>
					<option value="<?=$pCod?>"<?=($pCod==$Usuario[0]['upais'])?' selected="selected"':''?>><?=$pNomb?></option>
<?php
}
?>
				</select>
				<span class="pais_<?=$Usuario[0]['upais']?>" id="span_pais"></span>
			</td>
		</tr>
		<tr>
			<td>Usuario</td>
			<td><input type="text" name="usuario" value="<?=$Usuario[0]['usuario']?>" class="requ" />*</td>
		</tr>
		<tr>
			<td>Contrase&ntilde;a</td>
			<td><input type="text" name="password" value="<?=$Usuario[0]['contrasena']?>" class="requ" />*</td>
		</tr>
		<tr>
			<td>C&oacute;digo de empleado</td>
			<td><input type="text" name="cod_empleado" value="<?=$Usuario[0]['cod_empleado']?>" class="requ" />*</td>
		</tr>
		<tr>
			<td>Nombre</td>
			<td><input type="text" name="nombre" value="<?=$Usuario[0]['nombre']?>" class="requ" size="45" />*</td>
		</tr>
		<tr>
			<td>Puesto</td>
			<td><input type="text" name="puesto" value="<?=$Usuario[0]['puesto']?>" class="requ" />*</td>
		</tr>
		<tr>
			<td>Departamento</td>
			<td>
				<select name="departamento">
<?php
foreach($Departamentos as $Dpto)
{
	if(
		'Gerencia' != $this->session->userdata('codigo')
		&& 'Sistemas' != $this->session->userdata('codigo')
		&& (
			$Dpto['codigo'] == 'Gerencia'
			|| $Dpto['codigo'] == 'Sistemas'
		)
	)
	{
		continue;
	}
?>
					<option value="<?=$Dpto['id_dpto']?>"<?php if($Dpto['id_dpto']==$Usuario[0]['id_dpto']){ echo ' selected="selected"'; } ?>><?=$Dpto['departamento']?> [<?=$Dpto['codigo']?>]</option>
<?php
}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>email</td>
			<td><input type="text" name="email" value="<?=$Usuario[0]['email']?>" /></td>
		</tr>
		<tr>
			<td>Puesto Programable</td>
			<td>
				<label for='programable'>Si</label><input type='radio' value='s' name='usu_prog' id='usu_prog' <?=($Usuario[0]['usu_prog']=='s')?' checked="checked"':''; ?>>
				&nbsp;&nbsp;&nbsp;
				<label for='programable'>No</label><input type='radio' value='n' name='usu_prog' id='usu_prog' <?=($Usuario[0]['usu_prog']=='n')?' checked="checked"':''; ?>>
			</td>
		</tr>
		<tr>
			<td><input type="submit" value="Modificar" /></td>
		</tr>
	</table>


	<br style="clear: both;" />
</form>


<script>
	$('[name="upais"]').change(function()
	{
		$('#span_pais').removeClass();
		$('#span_pais').addClass('pais_'+$(this).val());
	});
</script>



<br />
<strong style="font-size: 15px;">Asignar Men&uacute;</strong>