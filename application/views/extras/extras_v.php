<script type="text/javascript" src="/html/js/extra.js?n=1"></script>
<link rel="stylesheet" type="text/css" media="all" href="/html/css/calendario.css" />
<link rel="stylesheet" type="text/css" media="all" href="/html/css/extra_rep.css" />
<?
$dia = date("d");
$meses_v = array(" ",
								 "Enero",
								 "Febrero",
								 "Marzo",
								 "Abril",
								 "Mayo",
								 "Junio",
								 "Julio",
								 "Agosto",
								 "Septiembre",
								 "Octubre",
								 "Noviembre",
								 "Diciembre");

$dias_semana = array("Domingo",
										 "Lunes",
										 "Martes",
										 "Mi&eacute;rcoles",
										 "Jueves",
										 "Viernes",
										 "S&aacute;bado");

//Listado de los años
//=============================================
$primer = 2008;
$cmb_anho = "";
$inicio = date("Y");
$inicio++;
$inicio = $inicio - $primer;
$inicio = $inicio + 2;

//Mes anterior
$mes_ant = $mes - 1;
$anho_ant = $anho;
if($mes_ant < 1){
	$mes_ant = "12";
	$anho_ant--;
}

//Mes siguiente
$mes_sig = $mes + 1;
$anho_sig = $anho;
if($mes_sig > 12)
{
	$mes_sig = 1;
	$anho_sig++;
}

if($mes_ant < 10)
{
	$mes_ant = "0$mes_ant";
}
if($mes_sig < 10)
{
	$mes_sig = "0$mes_sig";
}
?>
<div class="informacion">
	<div>
		<table class="estructura_calendario">
		<tr>
			<th colspan="7">
				<a href="/extras/extras/index/<?=$anho_ant.'/'.$mes_ant?>" title="Mes Anterior">&lt;&lt;&lt;</a> &nbsp;
				
				<select name="mes" id="mes" onchange="ir_mes()">
<?php
for($i = 1; $i <= 12; $i++)
{
	if($i < 10)
	{
		$iii = "0$i";
	}
	else
	{
		$iii = $i;
	}
?>
					<option value="<?=$iii?>"<?=($mes == $i)?' selected="selected"':''?>><?=$meses_v[$i]?></option>
<?php
}
?>
				</select>
				
				<select name="anho" id="anho" onchange="ir_mes()">
<?php
for($i = 0; $i < $inicio; $i++)
{
?>
					<option value="<?=$primer?>"<?=($anho == $primer)?' selected="selected"':''?>><?=$primer?></option>
<?php	
	$primer++;
}
?>
				</select> &nbsp;
				
				<a href="/extras/extras/index/<?=$anho_sig.'/'.$mes_sig?>" title="Mes Siguiente">&gt;&gt;&gt;</a>
			</th>
		</tr>

		<tr>
<?php
//Que dia de la semana inicia el mes?
$dia_inicio = date("w", @mktime(0, 0, 0, $mes, 1, $anho)) + 1;

foreach($dias_semana as $index => $dia)
{
?>
			<td class="dia_titulo"><?=$dia?></td>
<?php
}
?>
		</tr>
<?php
for($i = 1; $i <= $dias_mes; $i++)
{
	
	$dia_c = $i;
	if($dia_c < 10)
	{
		$dia_c = "0$dia_c";
	}
	$fecha_compare = "$anho-$mes-$dia_c";
	
	if($dia_inicio == 1)
	{
?>
		<tr>
<?php
	}
	
	if($dia_inicio > 1 && $i == 1)
	{
?>
	<tr>
<?php
		for($o = 1; $o < $dia_inicio; $o++)
		{
?>
			<td>&nbsp;</td>
<?php
		}
	}
	$dia_hoy = $i;
	if($fecha_compare == date("Y-m-d"))
	{
		$dia_hoy = "[$i]";
	}
	if($this->session->userdata('codigo') != 'SAP')
	{
?>

		<td class="dia_normal"><a href='/extras/extra_agr/index/<?=$dia_c.'/'.$mes.'/'.$anho?>' title='Ver Agregar Horas extras'><strong><?=$dia_hoy?></strong></a><br />
<?php
	}
	else
	{
?>
		<td class="dia_normal"><strong><?=$dia_hoy?></strong><br />
<?php
	}
?>
		
<?php
	//Exta variable es el resultado de la consulta que se hace en el modelo.
	//Esta variable contendra los dias que tienen procesos con horas extras.
	$extras_dias = $dias_extras[$i];
	
	//Verificamos si hay extras para el dia que corresponde.
	if($extras_dias > 0)
	{
?>
			<div><a href='/extras/extra_agr/index/<?=$dia_c.'/'.$mes.'/'.$anho?>' title='Reporte para este dia'>VER REPORTE</a></div>
<?php	
	}
?>
		</td>
<?php	
	if($i == $dias_mes && $dia_inicio < 7)
	{
		for($o = $dia_inicio; $o < 7; $o++)
		{
?>
		<td>&nbsp;</td>
<?php
		}
	}
	
	$dia_inicio++;
	if($dia_inicio == 8 || $i == $dias_mes)
	{
		$dia_inicio = 1;
	}
	
	if($dia_inicio == 1)
	{
?>
	</tr>
<?php
	}
}

?>
		</table>
	</div>
	
	<br />
	<strong><a href="/extras/extra_rep" title="Generar Reporte">Reporte General de Horas Extras</a></strong>	
</div>

<?
if($this->session->userdata('codigo') != 'SAP')
{
?>
<!-- div id="porcentaje_extra">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<strong>Porcentaje de Proyecci&oacute;n</strong> <a style='margin-left: 12em;' href="javascript:porcentaje_ocu();"><img src="/html/img/cerrar.png" onmouseover="javascript:this.src='/html/img/cerrarh.png'" onmouseout="javascript:this.src='/html/img/cerrar.png'" alt="cerrar" width="25" /></a><br />
	
	<table class="tabular" style='width: 100%;'>
		<tr>
			<td><strong>Departamento</strong></td>
			<td><strong>Proyectado</strong></td>
			<td><strong>Utilizado</strong></td>
			<td><strong>Porcentaje</strong></td>
		</tr>
<?php
foreach($proyecciones as $Informacion)
{
		$id_dpto = $Informacion["id_dpto"];
		$codigo = $Informacion["codigo"];
		$departamento = $Informacion["departamento"];
		$proyeccion = $Informacion["proyeccion"];
	
		$total_h = $Informacion["horas"];
		$total_m = $Informacion["t_d"];
		
		$porcentaje_m = 0;
		if($proyeccion > 0)
		{
			$porcentaje_m = ($total_m * 100) / $proyeccion;
		}
		$clase = "";
		if($porcentaje_m >= 50 && $porcentaje_m < 70 )
		{
			$clase = " class=\"verde1\"";
		}
		if($porcentaje_m >= 70 && $porcentaje_m < 90 )
		{
			$clase = " class=\"amarillo1\"";
		}
		if($porcentaje_m >= 90 )
		{
			$clase = " class=\"rojo1\"";
		}
?>
		<tr>
			<td>&raquo; <?=$codigo.' - '.$departamento?></td>
			<td>$ <?=number_format($proyeccion, 2)?></td>
			<td style='width:75px;'>$ <?=number_format($total_m, 3)?></td>
			<td<?=$clase?>><?=number_format($porcentaje_m, 2)?> %</td>
		</tr>
<?php	
		$total_h = 0;
		$total_m = 0;
}
?>
	</table>
	
</div -->

<?php
}
?>