<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Central Graphics" />
	<!-- meta name="codename" content="Fenix, Chicken Run" /-->
	<meta name="author" content="Central Graphics" />
	<title><?=$Titulo_Pagina?> - <?=$this->session->userdata('nombre_grupo')?></title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="/html/js/jquery-ui-1.8.17.js"></script>
	<script type="text/javascript" src="/html/js/acciones.js?n=1"></script>
	<link rel="stylesheet" href="/html/css/estilo.003.css?v=010" />
	<link rel="stylesheet" href="/html/css/jquery-ui-1.8.17.css" />
	<link rel="stylesheet" type="text/css" href="/html/css/bootstrap.min.css" />
	<?php

	if(3 == $this->session->userdata('id_usuario') || 'central.com' == $_SERVER['SERVER_NAME'])
	{
		?>
		<style>
			body{
				margin: 0px;
				border: 0px;
				color: #333333;
			}
			
			#navegacion a{
				color: #bc933b;
			}
			#navcontent{
				background: white;
				position: absolute;
				width: 71.3%;

			}
			#cont-pagina{
				padding-top: 130px;
			}
			.tabular th{
				background: #555555;
			}
			input, select, button, textarea, input[type="button"], input[type="submit"]{
				color: #333333;
			}
		</style>
		<?php
	}
	?>
</head>
<body>

	<div id="contenedor">
		<div id="navcontent" >
		<?=$this->session->userdata('menu')?>

		<a href="http://home.centralgraphics-cg.com/">
			<img id="logo_grupo" src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>_sis.png" alt="" />
		</a>

		<div class="limpiar"></div>

		<h1 class="no_imprime encabezado"><?=$Titulo_Pagina?></h1>


		<div id="encabezado_ocul">
			<img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>.png" alt="" />
			<h1><?=$Titulo_Pagina?></h1>
			<br /><br />
		</div>

		<div id="nombre_usuario">

			<?php
			if('Sistemas' == $this->session->userdata('codigo'))
			{
				$Grupos_Lista_if = $this->ver_sesion_m->listado_grupos();
				?>
				<select name="cambia_grupo_ipsofacto" id="cambia_grupo_ipsofacto">
					<?php
					foreach($Grupos_Lista_if as $Grupo_if)
					{
						?>
						<option value="<?=$Grupo_if['id_grupo']?>"<?=($Grupo_if['id_grupo']==$this->session->userdata('id_grupo'))?' selected="selected"':''?>><?=$Grupo_if['abreviatura']?></option>
						<?php
					}
					?>
				</select> &nbsp;
				<?php
			}
			?>

			<?php
			if('' == $this->session->userdata('id_cliente'))
			{
				?>
				<span class="pais_<?=$this->session->userdata('pais')?>"></span>
				<?php
			}
			?>
			<?=$this->session->userdata('nombre')?>
			&nbsp; 
			<a href='/salir'>[Salir]</a>
		</div>
		<br />

		</div>
		<!--div id='corte_pagina'></div-->
		<?php
//$Mensaje .= 'Se realizar&aacute; un mantenimiento de 8:15 p.m. a 9:00 p.m.';
//$Mensaje .= '<br />Estimado Usuario, en este momento se esta llevando a cabo una Actualizacion al Sistema. Por lo que es probable que experimente algunos inconvenientes.<br />Hora de finalizacion: 11:30 p.m.';
		?>


		<?php
		if(
			'Sistemas' == $this->session->userdata('codigo')
			|| 'Gerencia' == $this->session->userdata('codigo')
			|| 'Plani' == $this->session->userdata('codigo')
		)
		{
			?>
<!--div id='manto' style="background: #F9F4B6; color: #000; border: 1px solid #BAA51F; border-radius: 5px; padding: 2px;margin-top: -30px;width:750px;">
Se realizar&aacute; una actualizaci&oacute;n desde las 7:00 p.m. en adelante.
</div-->
<?php
}
?>


<div class="msj_usuario"<?php if(''==$Mensaje){ echo 'style="display:none;"'; } ?>>
	<?=$Mensaje?>
</div>







<?=$this->session->userdata('menula')?>


<div id="cont-pagina">
