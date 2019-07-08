<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Central Graphics" />
	<!-- meta name="codename" content="Fenix, Chicken Run" /-->
	<meta name="author" content="Daniel Echeverria y Marvin Pocasangre" />
	<title>Sistema de Seguimiento - Central Graphics</title>
	<link rel="shortcut icon" href="/html/img/ico-cg.png" />
	
	<style type="text/css">
		img{ border: none; }
		body{ padding: 20px 0; background: #ffffff; font-family: verdana; font-size: 12px; line-height: 25px; color: #909090; text-align: left;}
		#contenedor{ width: 960px; margin: auto; }
		
		
		#div_logo{
			border-bottom: 2px solid #ecbe60;
			padding-left: 65px;
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
			background: url('/html/img/flecha-inicio.png') repeat-x;
		}
		input[type=submit]:hover,input[type=button]:hover{
			color: #333333;
		}
		
		hr{
			border: 1px solid #aaa9a9;
		}
		
		#pre-carrousel{
			width: 953px;
			height: 307px;
			margin: auto;
		}
		#carrousel{
			width: 953px;
			height: 307px;
			position: relative;
		}
		#carrousel li{
			position: absolute;
			top: 0;
			left: 0;
			display: none;
		}
		
		#iconos{
			text-align: center;
		}
		.iconos{
			width: 148px;
			height: 165px;
			margin-right: 47px;
			display: inline-block;
			background: url('/html/img/ico-principal.png');
		}
		.iconos:last-child{
			margin-right: 0px;
		}
		
		.iconos:hover .burbujas{
			visibility: visible;
			opacity: 1;
			margin-left: 0px;
			transition: opacity .3s;
			-moz-transition: opacity .3s;
			-webkit-transition: opacity .3s;
			-o-transition: opacity .3s;
		}
		
		#ico-usuario{
			background-position: 0px 0px;
		}
		#ico-color{
			background-position: -148px 0px;
		}
		#ico-consulta{
			background-position: -297px 0px;
		}
		#ico-flexo{
			background-position: -445px 0px;
		}
		#ico-blog{
			background-position: -595px 0px;
		}
		
		.burbujas{
			width: 330px;
			height: 123px;
			visibility: hidden;
			opacity: 0;
			margin-left: -70px;
			margin-top: -155px;
			position: absolute;
			text-align: left;
			line-height: 15px;
			font-family: Arial, Verdana;
			background: url('/html/img/ico-principal.png');
		}
		.burbujas p{
			margin-top: 15px;
			margin-left: 22px;
			font-size: 15px;
		}
		.burbujas span{
			color: #c8c7c7;
			font-size: 11px;
			margin-top: -3px;
			margin-left: 22px;
			display: inline-block;
		}
		#bur-consulta{
			background-position: -3px -165px;
		}
		#bur-color{
			background-position: -333px -165px;
		}
		#bur-usuario{
			background-position: -3px -290px;
		}
		#bur-flexo{
			background-position: -333px -290px;
		}
		#bur-blog{
			background-position: -3px 0px;
		}
		
		#bur-consulta p{
			color: #e1a5ad;
		}
		#bur-color p{
			color: #c3ce97;
		}
		#bur-usuario p{
			color: #c0c0c0;
		}
		#bur-flexo p{
			color: #b57a9d;
		}
		#bur-blog p{
			color: #c0c0c0;
		}
		
		
	</style>
</head>


<body>
	
	<div id="contenedor">
		
		
		<div id="div_logo"><img src="/html/img/lgtps/central-g_inicio.png" /></div>
		
		<br />
		<div id="pre-carrousel">
			<ul id="carrousel">
				<li><img src="/html/img/carrousel1.jpg" /></li>
				<li><img src="/html/img/carrousel2.jpg" /></li>
			</ul>
		</div>
		
		
		<br /><br /><br />
		
		
		<div id="iconos">
			<a href="/ingresar/grupo/central-g" id="ico-usuario" class="iconos">
				<span class="burbujas" id="bur-usuario">
					<p>Usuarios</p>
					<span>Ingrese su usuario y clave para accesar a su cuenta y visualizar el estatus de sus trabajos o ingresarlos al flujo de trabajo</span>
				</span>
			</a>
			<a href="#" id="ico-color" class="iconos">
				<span class="burbujas" id="bur-color">
					<p>Estandarizaci&oacte;n de Color</p>
					<span>Los buenos resultados impresos dependen de una planta estandarizada.<br />Contamos con las herramientas y experiencia para ayudarle.</span>
				</span>
			</a>
			<a href="#" id="ico-consulta" class="iconos">
				<span class="burbujas" id="bur-consulta">
					<p>Consultor&iacute;as</p>
					<span>En todo lo referente a la impresi&oacute;n flexogr&aacute;fica, rotograbado y offset.</span>
				</span>
			</a>
			<a href="#" id="ico-flexo" class="iconos">
				<span class="burbujas" id="bur-flexo">
					<p>Servicios Flexo</p>
					<span>Desarrollo de arte y preprensa, planchas digitales en diferentes calibres, con tecnología HD, Punto plano.</span>
				</span>
			</a>
			<i href="#" id="ico-blog" class="iconos">
				<span class="burbujas-" style="display: none;" id="bur-blog">
					<p>Blog</p>
					<span>Noticias, recursos, tutoriales y mucho m&aacute;s!</span>
				</span>
			</i>
		</div>
		
	</div>
	
	
	
	<script type="text/javascript" src="/html/js/jquery-1.7.1.js"></script>
	<script>
		var $Carrousel = $('#carrousel');//$slider
		var $Pagina = 'li';//$slide
		var $Tiempo_Trans = 1000;
		var $Tiempo_Espera = 4000;
		
		function paginas()
		{
			return $Carrousel.find($Pagina);
		}
		
		paginas().fadeOut();
		
		paginas().first().addClass('active');
		paginas().first().fadeIn($Tiempo_Trans);
		
		
		$Intervalo = setInterval(function()
		{
			var $i = $Carrousel.find($Pagina + '.active').index();
			
			paginas().eq($i).removeClass('active');
			paginas().eq($i).fadeOut($Tiempo_Trans);
			
			if(paginas().length == $i + 1)
			{
				$i = -1;
			}
			
			paginas().eq($i + 1).fadeIn($Tiempo_Trans);
			paginas().eq($i + 1).addClass('active');
		},
		$Tiempo_Trans + $Tiempo_Espera);
		
		/*var $Carrousel = $('#carrousel');//$slider
		var $Pagina = 'li';//$slide*/
	</script>
	
	
</body>
</html>