<?php
if(count($mostrar_hoja) != 0)
{
?>
<script>
		window.print();
</script>
<div class="informacion">
	<table>
<?

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
$fecha_real = "";
$rev_calidad = false;

//Si el resultado es positivo se mostrara la imagen de ok.
$ok = "<img src=\"/html/img/ok.png\" width=\"20\" alt=\"OK\" />";
//Si el resultado es negativo se mostrara la imagen de N/A
$N_A = "<img src=\"/html/img/na.png\" width=\"20\" alt=\"N/A\" />";

$disenop = $N_A;
$fotop = $N_A;
$textop = $N_A;
$guiap = $N_A;
$codp = $N_A;

foreach($mostrar_hoja as $Datos)
{
	if($Datos["id_usuario"] == "24")
	{
		$rev_calidad = true;
		$puesto = $Datos["puesto"];
	}
	else
	{
		if($Datos["diseno"] == "OK")
		{
			$disenop = $ok;
		}
		if($Datos["fotocelda"] == "OK")
		{
			$fotop = $ok;
		}
		if($Datos["texto_diseno"] == "OK")
		{
			$textop = $ok;
		}
		if($Datos["color"] == "OK")
		{
			$guiap = $ok;
		}
		if($Datos["cod_barra"] == "OK")
		{
			$codp = $ok;
		}
		$fecha_real = $Datos["fecha"];
		$puesto = $Datos["puesto"];
	}
}
?>
	</table>

	<table>
	
	<tr>
		<td><br /><strong>1. DISE&Ntilde;O CONTRA EL ARTE</strong></td>
		<td>Proceso de Calidad</td>
	</tr>
	<tr>
		<td>Textos.</td>
		<td style="text-align: right;"><?=$disenop?></td>
	</tr>
	<tr>
		<td>Medidas de Arte.</td>
		<td style="text-align: right;"><?=$disenop?></td>
	</tr>
	<tr>
		<td>Dise&ntilde;o.</td>
		<td style="text-align: right;"><?=$disenop?></td>
	</tr>
	<tr>
		<td>Logotipo de Cliente.</td>
		<td style="text-align: right;"><?=$disenop?></td>
	</tr>

	<tr>
		<td><br /><strong>2. FOTOCELDA</strong></td>
	</tr>
	<tr>
		<td>Tama&ntilde;o.</td>
		<td style="text-align: right;"><?=$fotop?></td>
	</tr>
	<tr>
		<td>Color.</td>
		<td style="text-align: right;"><?=$fotop?></td>
	</tr>
	<tr>
		<td>Posici&oacute;n.</td>
		<td style="text-align: right;"><?=$fotop?></td>
	</tr>

	<tr>
		<td><br /><strong>3. TEXTOS Y DISE&Ntilde;O</strong></td>
	</tr>
	<tr>
		<td>Dise&ntilde;o.</td><td style="text-align: right;"><?=$textop?></td>
	</tr>
	<tr>
		<td>Traslapes.</td>
		<td style="text-align: right;"><?=$textop?></td>
	</tr>

	<tr>
		<td><br /><strong>4. GUIA DE COLOR</strong></td>
	</tr>
	<tr>
		<td># Repeticiones en Ancho.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td># Repeticiones en Circunferencia.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td>Embobinado.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td>Secuencia de Colores.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td>Gu&iacute;as Beck.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td>Gu&iacute;as de Registro Manual.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td>Gu&iacute;as Grafikontrol.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td>G&iacute;a de Corte.</td>
		<td style="text-align: right;"><?=$guiap?></td>
	</tr>
	<tr>
		<td><br /><strong>5. C&Oacute;DIGO DE BARRAS</strong></td>
	</tr>
	<tr>
		<td>Tipo.</td>
		<td style="text-align: right;"><?=$codp?></td>
	</tr>
	<tr>
		<td>N&uacute;mero.</td>
		<td style="text-align: right;"><?=$codp?></td>
	</tr>
	<tr>
		<td>Color.</td>
		<td style="text-align: right;"><?=$codp?></td>
	</tr>
	<tr>
		<td>Factor de Magnificaci&oacute;n.</td>
		<td style="text-align: right;"><?=$codp?></td>
	</tr>
	<tr>
		<td>Posici&oacute;n (MD o TD).</td>
		<td style="text-align: right;"><?=$codp?></td>
	</tr>

	<tr><td><br /><br />Certificamos que el Producto se revis&oacute; de acuerdo al proceso adjunto por<br />Central Graphics: Preprensa <?=$puesto?> en esta Fecha 
	<?
	if($fecha_real != '')
	{
		echo $this->fechas_m->fecha_ymd_dmy($fecha_real);
	}
	
	if($rev_calidad)
	{
		echo '<br />Este trabajo fue revisado adicionalmente por el departamento de calidad.';
	}
	
	?>
	</td></tr>

	</table>
</div>
<?php
}
else
{
?>
	<strong>No se encontro hoja de revision</strong>
<?php
}
?>