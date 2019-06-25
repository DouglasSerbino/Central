<?php
if('mostrar' == $this->generar_cache_m->preparar_cache($Cache))
{
?>
<script type="text/javascript" src="/html/js/jquery.flot.js"></script>
<script type="text/javascript" src="/html/js/jquery.flot.pie.js"></script>
<style>
	.cursorc
	{
		cursor: pointer;
	}
</style>
<div class="informacion">	
	<strong>Reporte de Cumplimiento</strong>
	
	<form action="/reportes/cumplimiento/index/" method="post" name="miform">
		
		<select name="mes1">
<?
foreach($Meses as $iMes => $nMes){
?>
			<option value="<?=$iMes?>"<?=($mes==$iMes)?' selected="selected"':''?>><?=$nMes?></option>
<?
}
?>
		</select> &nbsp;
		<input type="text" name="anho1" size="8" value="<?=$anho; ?>" /> &nbsp;
		<input type="radio" name="tipo" id="tipo1" value="grafico" <?=($tipo == "grafico")?' checked="checked"':''?> /><label for="tipo1">Gr&aacute;fico</label> &nbsp;
		<input type="radio" name="tipo" id="tipo2" value="texto" <?=($tipo == "texto" || $tipo == "")?' checked="checked"':''?> /><label for="tipo2">Texto</label> &nbsp
		<input type="submit" class="boton" value="Generar Reporte" />&nbsp;&nbsp;
		<input type="hidden" name="cod_cliente" value="<?=$cod_cliente; ?>" />
	</form>
	
	<table class="tabular">
		<tr>
			<th style='width: 125px;'>Cliente</th>
			<th style='width: 125px;'>Total Pedidos</th>
			<th style='width: 150px;'>Entregas a Tiempo</th>
			<th style='width: 150px;'>Entregas Atrasadas</th>
			<th>Productividad</th>
		</tr>
<?

if(0 < count($cumplimiento_general))
{
	$tt_puntualg = 0;
	$tt_atrasadog = 0;
	foreach($cumplimiento_general as $Index => $Datos)
	{
		$tt_puntualg += $Datos['puntual'];
		$tt_atrasadog += $Datos['atrasado'];
?>
		<tr>
			<td><a href="/reportes/cumplimiento_rep/index/<?=$anho?>/<?=$mes?>/<?=$Datos['codigo_cliente']?>" title="Ver Grafico de este Cliente" target="_blank"><strong><?=$Datos['codigo_cliente']?></strong></a>&nbsp;</td>
			<td><?=$Datos['pedido']?></td>
			<td><?=$Datos['puntual']?></td>
			<td><?=$Datos['atrasado']?></td>
			<td><?=$Datos['porcentaje']?> %</td>
		</tr>
<?php
	}

	$despachadosg = $tt_puntualg + $tt_atrasadog;
	$tt_porcentaje = 0;
	if($despachadosg > 0)
	{
		if($despachadosg > 0)
		{
			$tt_porcentaje = round(($tt_puntualg * 100) / $despachadosg);
		}
	}
?>
		<tr style='width: 800px;'>
			<th>Despachados</th>
			<th><?=$despachadosg?></th>
			<th><?=$tt_puntualg?></th>
			<th><?=$tt_atrasadog?></th>
			<th><?=$tt_porcentaje?> %</th>
		</tr>
	</table>
</div>
<?php
}


	$this->generar_cache_m->generar_cache($Cache);
}
?>