<?php
if(count($mostrar_hoja) != 0)
{
?>
<script>
		window.print();
</script>
<div class="informacion">
	
	<table>
<?php
foreach($Cliente_Procesos as $Datos)
{
?>
	<tr>
			<td width="10%">Proceso:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><strong><?=$Datos["codigo_cliente"]."-".$Datos["proceso"]?></strong></td>
			<td width="30%" rowspan="3">
				CODIGO: FP73103<br />
				PAG.: 1 de 1<br />
				FECHA: 24/02/2003<br />
				REVISION: 1
			</td>
		</tr>
		<tr>
			<td>Cliente:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><strong><?=$Datos["cliente"]?></strong></td>
		</tr>
		<tr>
			<td>Producto:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><strong><?=$Datos["nombre"]?></strong></td>
		</tr>
<?php
}
//Si el resultado es positivo se mostrara la imagen de ok.
$ok = "<img src=\"/html/img/ok.png\" width=\"20\" alt=\"OK\" />";
//Si el resultado es negativo se mostrara la imagen de N/A
$N_A = "<img src=\"/html/img/na.png\" width=\"20\" alt=\"N/A\" />";

$medida = $N_A;
$disenho = $N_A;
$cod_barra = $N_A;
$color = $N_A;
$aprobado = $N_A;

foreach($mostrar_hoja as $Datos)
{
	if('on' == $Datos['medida'])
	{
		$medida = $ok;
	}
	
	if('on' == $Datos['diseno'])
	{
		$disenho = $ok;
	}
	
	if('on' == $Datos['cod_barra'])
	{
		$cod_barra = $ok;
	}
	
	if('on' == $Datos['color'])
	{
		$color = $ok;
	}
	
	$tipo_impresion = explode('-', $Datos['tipo_impresion']);
	$observacion = $Datos['observacion'];
	$fecha_real = $Datos['fecha'];
	$puesto = $Datos["puesto"];
}
?>
	</table>
	<br />
	<table>
	<tr>
		<td width="60%"><strong>1. NEGATIVO CONTRA EL ARTE</strong></td><td>&nbsp;</td></tr>
	<tr>
		<td>Medidas A-B y C-D</td>
		<td><?=$medida?></td>
	</tr>
	<tr>
		<td>Dise&ntilde;o</td>
		<td><?=$disenho?></td>
	</tr>
	<tr>
		<td>C&oacute;digo de Barras</td>
		<td><?=$cod_barra?></td>
	</tr>
	<tr>
		<td>Secuencia de Color</td>
		<td><?=$color?></td>
	</tr>
	
	<tr>
		<td colspan="2"><br /><strong>2. PLANCHA POR COLOR</strong></td></tr>
<?php

foreach($mostrar_colores as $Datos)
{
	if('OK' == $Datos['aprobado'])
	{
		$aprobado = $ok;
	}
?>
		<tr>
			<td><?=$Datos["color"]?></td>
			<td><?=$aprobado?></td>
		</tr>
<?php
}

?>
	
	<tr><td colspan="2"><br /><strong>3. M&Aacute;QUINA IMPRESORA</strong></td></tr>
	<tr><td>
<?php
	if('1' == $tipo_impresion[0])
	{
		echo "&raquo;GTOV<br />";
	}
	
	if('1' == $tipo_impresion[1])
	{
		echo '&raquo;SPEEDMASTER 74<br />';
	}

	if('1' == $tipo_impresion[2])
	{
		echo '&raquo;ZORS Z<br />';
	}
	if('1' == $tipo_impresion[3])
	{
		echo '&raquo;SPEEDMASTER 72<br />';
	}
?>
		</td>
	</tr>
	
	<tr><td colspan="2"><br /><strong>4. OBSERVACIONES</strong></td></tr>
	<tr><td colspan="2"><table class="tabla_revision" width="90%"><tr><td><?php echo $observacion; ?></td></tr></table></td></tr>
	
	<tr><td colspan="2"><br />Certificamos que el Producto se revis&oacute; de acuerdo al proceso adjunto por<br />Central Graphics: Planchas Offset <?php echo $puesto; ?> en esta Fecha 
	<?=$this->fechas_m->fecha_ymd_dmy($fecha_real); ?></td></tr>
	
	</table>
	
	</div>
</div>
<?php
}
else
{
?>
	<strong>No se encontro hoja de Revision</strong>
<?php
}
?>