



<input type="text" id="anho" size="5" value="<?=$Anho?>" />
<select id="mes">
<?
foreach($Meses as $Imes => $Nmes)
{
?>
	<option value="<?=$Imes?>"<?=($Imes==$Mes)?' selected="selected"':''?>><?=$Nmes?></option>
<?
}
?>
</select>


<select id="cliente">
<?
foreach($Clientes as $Cliente)
{
?>
	<option value="<?=$Cliente['id_cliente']?>"<?=($Cliente['id_cliente']==$Id_Cliente)?' selected="selected"':''?>><?=$Cliente['codigo_cliente'].' -- '.$Cliente['nombre']?></option>
<?
}
?>
</select>


<input type="button" value="Cambiar" id="rep_prod" />




<br /><br />
<strong>Productos</strong>
<table class="tabular" style="width:70%;">
	<tr>
		<th>Producto</th>
		<th style="width:14%;">Total</th>
		<th style="width:17%;">Cantidad</th>
	</tr>
<?
$Total = 0;
foreach($Productos['coti'] as $Prod)
{
	$Total += $Prod['tot'];
?>
	<tr>
		<td><?=$Prod['pro']?></td>
		<td style="text-align:right;">$<?=number_format($Prod['tot'],0)?> &nbsp;</td>
		<td style="text-align:right;"><?=number_format($Prod['can'],0).' '.strtolower($Prod['con'])?> &nbsp;</td>
	</tr>
<?
}
?>
	<tr>
		<td style="text-align: right;"><strong>Total</strong></td>
		<td style="text-align: right;"><strong>$<?=number_format($Total,0)?></strong> &nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<br />


<strong>Materiales Utilizados</strong>
<table class="tabular" style="width:70%;">
	<tr>
		<th>Material</th>
		<th style="width:14%;">Utilizado</th>
	</tr>
<?
foreach($Productos['mate']['normal'] as $Mate)
{
?>
	<tr>
		<td><?=$Mate['nombre_material']?></td>
		<td style="text-align: right;"><?=number_format($Mate['cantidad'],0).' '.strtolower($Mate['tipo'])?> &nbsp;</td>
	</tr>
<?
}
?>
</table>
<br />



<strong>Materiales Utilizados en  Reprocesos</strong>
<table class="tabular" style="width:70%;">
	<tr>
		<th>Material</th>
		<th style="width:14%;">Utilizado</th>
	</tr>
<?
foreach($Productos['mate']['repro'] as $Mate)
{
?>
	<tr>
		<td><?=$Mate['nombre_material']?></td>
		<td style="text-align: right;"><?=number_format($Mate['cantidad'],0).' '.strtolower($Mate['tipo'])?> &nbsp;</td>
	</tr>
<?
}
?>
</table>
<br />




<strong>Tiempos</strong>
<table class="tabular" style="width:70%;">
	<tr>
		<th>Usuario</th>
		<th style="width:14%;">Utilizado</th>
	</tr>
<?
$Total = 0;
foreach($Productos['tiem'] as $Usuario)
{
	$Total += $Usuario['tiempo'];
	$Horas = floor($Usuario['tiempo'] / 60);
	$Minutos = ($Usuario['tiempo'] % 60);
	if(10 > $Minutos)
	{
		$Minutos = '0'.$Minutos;
	}
	$Horas .= ':'.$Minutos;
?>
	<tr>
		<td><?=$Usuario['usuario']?></td>
		<td style="text-align: right;"><?=$Horas?>h &nbsp;</td>
	</tr>
<?
}


$Horas = floor($Total / 60);
$Minutos = ($Total % 60);
if(10 > $Minutos)
{
	$Minutos = '0'.$Minutos;
}
$Horas .= ':'.$Minutos;
?>
	<tr>
		<td style="text-align: right;"><strong>Total</strong></td>
		<td style="text-align: right;"><strong><?=$Horas?>h</strong> &nbsp;</td>
	</tr>
</table>




<script>
$('#rep_prod').click(function()
{
	window.location = '/reportes/producto/index/'+$('#anho').val()+'/'+$('#mes').val()+'/'+$('#cliente').val();
});
</script>



