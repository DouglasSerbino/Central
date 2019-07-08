<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Central Graphics Prepress Company" />
	<!-- meta name="codename" content="Fenix, Chicken Run" /-->
	<meta name="author" content="Daniel Echeverria y Marvin Pocasangre" />
	<title>Sistema de Seguimiento - <?=$Informacion['nombre_grupo']?></title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<style type="text/css">
		img{ border: none; }
		body{ padding: 30px 0; background: #ffffff; font-family: verdana; font-size: 12px; line-height: 25px; color: #909090; text-align: center;}
		#contenedor{ width: 950px; margin: auto; }
		a{ color: #38464F; }
		
		#div_logo{
			border-bottom: 4px solid #ecbe60;
		}
		
		input[type="text"], input[type="password"]{
			background: #ffffff url('/html/img/txt-inicio.png') repeat-x;
			font-family: Verdana, Arial;
			font-size: 11px;
			font-weight: bold;
			line-height: 20px;
			color: #555555;
			padding: 2px;
			border: 1px solid #939598;
		}
		input[type="text"]:focus, textarea[type="password"]:focus{
			border: 1px solid #6fa1d9;
		}
		
		input[type=submit],input[type=button]{
			border: none;
			color: #555555;
			font: bold 12px "helvetica neue", helvetica, arial, sans-serif;
			line-height: 1;
			text-align: left;
			width: 100px;
			height: 45px;
			cursor: pointer;
			background: url('/html/img/<?=$Grupo?>_flecha-inicio.png') repeat-x;
		}
		input[type=submit]:hover,input[type=button]:hover{
			color: #333333;
		}
		
		hr{
			border: 1px solid #aaa9a9;
		}
	</style>
</head>
<body onload="document.ingreso.usuario.focus();">
	
	<div id="contenedor">
		
<?php
$this->load->helper('url');
$Url_Principal = site_url();
$Url_Principal = str_replace('seguimiento.', '', $Url_Principal);
$Url_Principal = str_replace('/corp.php?', '', $Url_Principal);
?>
		
		<div id="div_logo">
			<img id='logo' src="/html/img/lgtps/<?=$Grupo?>_inicio.png" alt="<?=$Informacion['nombre_grupo']?>" />
		</div>
		
		<br /><br /><br />
		
		<form name="ingreso" method="post" action="/ingresar/validar/<?=$Grupo?>">
			
			<label class="datos" for="usuario">Usuario</label>
			<br />
			<input type="text" name="usuario" id="usuario" />
			
			<br />
			<label for="password">Password</label>
			<br />
			<input type="password" name="password" id="password" />
			
			<br /><br />
			<input type="submit" value="Entrar" /></td>
			
		</form>
		
		<br /><br />
		<hr />
		
		<a href="<?=$Url_Principal?>">Regresar a inicio</a>
		
	</div>
	

</body>
</html>