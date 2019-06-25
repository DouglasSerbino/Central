<style type="text/css">
	.tabla_diad{
		width: 75%;
	}
	.derecha{
		text-align: right;
	}
</style>

<style type="text/css" media="print">
	.tabla_diad{
		margin: auto;
	}
	.quebrar_pagina
	{
		page-break-after: always;
	}
	.solo_print img{
		float: right;
	}
	.solo_print h1{
		float: left;
	}
	.solo_print br{
		clear: both;
	}
	i{
		font-style: italic;
	}
	#encabezado_ocul{
		height: 0px;
		overflow: hidden;
	}
</style>



<?
$Rango = '01-'.date('m-Y').' al '.'15-'.date('m-Y');
if(15 < date('d'))
{
	$Rango = '16-'.date('m-Y').' al '.date('t').'-'.date('m-Y');
}
?>


<?
foreach($Salarios as $Fila)
{
?>


<div class="solo_print">
	<br /><br />
	<h1>Boleta de pago</h1>
	<img src="/html/img/lgtps/<?=$this->session->userdata('grupo')?>_sis.png" alt="" />
	<br />
</div>

<strong><?=$Fila['Empleado']?></strong>

<br /><br class="solo_print" /><br class="solo_print" />
<table class="tabla_diad tabular">
	<tr>
		<td colspan="2">Detalle correspondiente al Periodo: <?=$Rango?>.</td>
	</tr>
	<tr>
		<th>Salario:</th>
		<th class="derecha">$<?=number_format($Fila['Salario'], 2)?></th>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp; &nbsp; AFP:</td>
		<td class="derecha">$<?=number_format($Fila['AFP'], 2)?></td>
	</tr>
	<tr>
		<td>&nbsp; &nbsp; ISSS:</td>
		<td class="derecha">$<?=number_format($Fila['ISSS'], 2)?></td>
	</tr>
	<tr>
		<td>&nbsp; &nbsp; Renta:</td>
		<td class="derecha">$<?=number_format($Fila['Renta'], 2)?></td>
	</tr>
	<tr>
		<td>&nbsp; &nbsp; <strong>Total Retenciones:</strong></td>
		<td class="derecha"><strong>$<?=number_format($Fila['Retenciones'], 2)?><strong></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>A Recibir:</th>
		<th class="derecha">$<?=number_format($Fila['Recibir'], 2)?></th>
	</tr>
</table>

<div class="solo_print">
	<br /><br />
	<i>Central Graphics S.A de C.V.</i>
</div>

<div class="quebrar_pagina">&nbsp;</div>

<?
}
?>


<!--
Notas:<br />
Falta agregar conteo de Extras<br />
Que otras retenciones se realizan?<br />
Que otros bonos se proporcionan?<br />
<br /><br />
-->


