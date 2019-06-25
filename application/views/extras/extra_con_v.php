<script>
	$(document).ready(function(){
		$("#contrasena").val('');
		$("#contrasena").focus();
});
</script>
<div class="informacion">
	<strong>Confirmar Usuario</strong><br />
	<form name="miform" method="post" action="/extras/extra_con/verificar_con">
		Contrase&ntilde;a: &nbsp; <input type="password" id="contrasena" name="contrasena" /> &nbsp; 
		<input type="submit" class="boton" value="Ingresar" />
	</form>
</div>