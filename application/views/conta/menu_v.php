<link rel="stylesheet" href="/html/css/estilo-conta.css" />

<div id="menu-conta">
	
	<select id="conta_anho">
<?
$Anho_fin = date('Y') + 1;
for($i = 2016; $i <= $Anho_fin; $i++)
{
?>
		<option value="<?=$i?>"<?=($i==$Anho)?' selected="selected"':''?>><?=$i?></option>
<?
}
?>
	</select>
	
	<select id="conta_mes">
<?
foreach($Meses as $iMes => $nMes)
{
?>
		<option value="<?=$iMes?>"<?=($iMes==$Mes)?' selected="selected"':''?>><?=$nMes?></option>
<?
}
?>
	</select>
	
	<a href="#" id="conta_cambia_fecha" style="font-size: 20px;">&raquo;</a>
	
	<ul>
		<li><a href="/conta/infografico/index/<?=$Anho?>/<?=$Mes?>">Infogr&aacute;fico Contable</a></li>
		<li><a href="/conta/sin_factura/index/<?=$Anho?>/<?=$Mes?>">Pedidos sin Facturar</a></li>
		<li><a href="/conta/sin_quedan/index/<?=$Anho?>/<?=$Mes?>">Pedidos sin Quedan</a></li>
		<li><a href="/conta/sin_cobro/index/<?=$Anho?>/<?=$Mes?>">Pedidos sin Cobrar</a></li>
		<li><a href="/conta/cobrado/index/<?=$Anho?>/<?=$Mes?>">Pedidos Cobrados</a></li>
	</ul>
</div>



<script>
	$('#menu-conta').height($(document).height());
	$('#conta_cambia_fecha').click(function()
	{
		window.location = '/conta/<?=$Pagina?>/index/'+$('#conta_anho').val()+'/'+$('#conta_mes').val();
	});
</script>


