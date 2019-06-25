

<script src="/html/js/raphael-2.1.4.min.js"></script>
<script src="/html/js/justgage.js"></script>


<div id="contenido-conta">
	
	<strong class="conta_subtitulo">Mensual</strong>
	
	<div class="sin_facturar">
		<span class="conta_total"><?=$Sin_Factura['mensual']['total']?></span>
		<span class="conta_leyenda">Pedidos sin Facturar</span>
		<span class="conta_valor">$<?=number_format($Sin_Factura['mensual']['vendido'], 0)?></span>
		<a href="/conta/sin_factura/index/<?=$Anho?>/<?=('anual'==$Mes)?date('m'):$Mes?>" class="conta_enlace">Ver Detalle</a>
	</div>
	
	<div class="sin_quedan">
		<span class="conta_total"><?=$Sin_Quedan['mensual']['total']?></span>
		<span class="conta_leyenda">Pedidos sin Quedan</span>
		<span class="conta_valor">$<?=number_format($Sin_Quedan['mensual']['vendido'], 0)?></span>
		<a href="/conta/sin_quedan/index/<?=$Anho?>/<?=('anual'==$Mes)?date('m'):$Mes?>" class="conta_enlace">Ver Detalle</a>
	</div>
	
	<div class="sin_pago">
		<span class="conta_total"><?=$Sin_Pago['mensual']['total']?></span>
		<span class="conta_leyenda">Pedidos sin Cobrar</span>
		<span class="conta_valor">$<?=number_format($Sin_Pago['mensual']['vendido'], 0)?></span>
		<a href="/conta/sin_cobro/index/<?=$Anho?>/<?=('anual'==$Mes)?date('m'):$Mes?>" class="conta_enlace">Ver Detalle</a>
	</div>
	
	<div class="pagado">
		<span class="conta_total"><?=$Pagado['mensual']['total']?></span>
		<span class="conta_leyenda">Pedidos Cobrados</span>
		<span class="conta_valor">$<?=number_format($Pagado['mensual']['vendido'], 0)?></span>
		<a href="/conta/cobrado/index/<?=$Anho?>/<?=('anual'==$Mes)?date('m'):$Mes?>" class="conta_enlace">Ver Detalle</a>
	</div>
	
	
	
	
	<br /><br />
	
	
	
	<strong class="conta_subtitulo">Anual</strong>
	
	<div class="sin_facturar">
		<span class="conta_total"><?=$Sin_Factura['anual']['total']?></span>
		<span class="conta_leyenda">Pedidos sin Facturar</span>
		<span class="conta_valor">$<?=number_format($Sin_Factura['anual']['vendido'], 0)?></span>
		<a href="/conta/sin_factura/index/<?=$Anho?>/anual" class="conta_enlace">Ver Detalle</a>
	</div>
	
	<div class="sin_quedan">
		<span class="conta_total"><?=$Sin_Quedan['anual']['total']?></span>
		<span class="conta_leyenda">Pedidos sin Quedan</span>
		<span class="conta_valor">$<?=number_format($Sin_Quedan['anual']['vendido'], 0)?></span>
		<a href="/conta/sin_quedan/index/<?=$Anho?>/anual" class="conta_enlace">Ver Detalle</a>
	</div>
	
	<div class="sin_pago">
		<span class="conta_total"><?=$Sin_Pago['anual']['total']?></span>
		<span class="conta_leyenda">Pedidos sin Cobrar</span>
		<span class="conta_valor">$<?=number_format($Sin_Pago['anual']['vendido'], 0)?></span>
		<a href="/conta/sin_cobro/index/<?=$Anho?>/anual" class="conta_enlace">Ver Detalle</a>
	</div>
	
	<div class="pagado">
		<span class="conta_total"><?=$Pagado['anual']['total']?></span>
		<span class="conta_leyenda">Pedidos Cobrados</span>
		<span class="conta_valor">$<?=number_format($Pagado['anual']['vendido'], 0)?></span>
		<a href="/conta/cobrado/index/<?=$Anho?>/anual" class="conta_enlace">Ver Detalle</a>
	</div>
	
	
	
	
	<br /><br />
	
	
	
	<strong class="conta_subtitulo">Cumplimiento</strong>
	<div class="cont_grafico">
		<div class="conta_grafico" id="graf_mensual"></div>
		<span class="cont_graf_leyenda">MENSUAL</span>
	</div>
	<div class="cont_grafico">
		<div class="conta_grafico" id="graf_anual"></div>
		<span class="cont_graf_leyenda">ANUAL</span>
	</div>
	
</div>



<?
$Total_Mensual = $Sin_Factura['mensual']['vendido'] + 0;
$Total_Mensual += $Sin_Quedan['mensual']['vendido'];
$Total_Mensual += $Sin_Pago['mensual']['vendido'];
$Total_Mensual += $Pagado['mensual']['vendido'];


$Total_Anual = $Sin_Factura['anual']['vendido'] + 0;
$Total_Anual += $Sin_Quedan['anual']['vendido'];
$Total_Anual += $Pagado['anual']['vendido'];
?>

<script>
	
	var graf_mensual = new JustGage({
		id: "graf_mensual",
		value : <?=($Pagado['mensual']['vendido']+0)?>,
		min: 0,
		max: <?=floor($Total_Mensual)?>,
		decimals: 0,
		gaugeWidthScale: 1,
		customSectors: [{
			color : "#ff0000",
			lo : 0,
			hi : <?=floor($Total_Mensual / 2)?>
		},{
			color : "#00ff00",
			lo : <?=ceil($Total_Mensual / 2)?>,
			hi : <?=floor($Total_Mensual)?>
		}],
		counter: true
	});
	
	var graf_anual = new JustGage({
		id: "graf_anual",
		value : <?=($Pagado['anual']['vendido']+0)?>,
		min: 0,
		max: <?=floor($Total_Anual)?>,
		decimals: 0,
		gaugeWidthScale: 1,
		customSectors: [{
			color : "#ff0000",
			lo : 0,
			hi : <?=floor($Total_Anual / 2)?>
		},{
			color : "#00ff00",
			lo : <?=ceil($Total_Anual / 2)?>,
			hi : <?=floor($Total_Anual)?>
		}],
		counter: true
	});
	
</script>


