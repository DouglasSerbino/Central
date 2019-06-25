<script type="text/javascript" src="/html/js/carga.js?n=1"></script>


<div id="tabs">
	<ul>
<?php
foreach($Dpto_Usu as $Id_Dpto => $Dpto)
{
	if('n' == $Dpto['tiempo'])
	{
		continue;
	}
?>
		<li><a href="#tabs-<?=$Id_Dpto?>"><?=$Dpto['dpto']?></a></li>
<?php
}
?>
	</ul>
<?php
$Conectar_Listas = array();

foreach($Dpto_Usu as $Id_Dpto => $Dpto)
{
	if('n' == $Dpto['tiempo'])
	{
		continue;
	}
?>
	<div id="tabs-<?=$Id_Dpto?>">
		<input type="button" value="Guardar distribuci&oacute;n" onclick="guarda_distribucion('<?=$Id_Dpto?>')" />
		(Aplica para los trabajos de este departamento)
		<br />
<?php
	$TUsuarios = 0;
	//Listado de los trabajos
	foreach($Dpto['usuarios'] as $Id_Usuario => $Usuario)
	{
		$TUsuarios++;
		if(8 == $TUsuarios)
		{
			$TUsuarios = 1;
?>
<br style="clear: both;" />
<?php
		}
		$Conectar_Listas[] = '#sortable-'.$Id_Usuario;
?>
		<ul id="sortable-<?=$Id_Usuario?>" class="sortable connectedSortable">
			<li class="no_sortable"><?=$Usuario['usuario']?></li>
<?php
		
		if(isset($Trabajos[$Id_Usuario]))
		{
			foreach($Trabajos[$Id_Usuario]['trabajos'] as $Id_Pedido => $Trabajo)
			{
?>
			<li id="pedido-<?=$Trabajo['peu']?>" style="height: <?=(10<$Trabajo['tie'])?$Trabajo['tie']:10?>px;"><strong><?=$Trabajo['pro']?></strong>[<?=$this->fechas_m->minutos_a_hora($Trabajo['tie'])?> h]<br /><?=$Trabajo['nom']?></li>
<?php
			}
		}
?>
		</ul>
<?php
	}
?>
		<br style="clear: both;" />
	</div>
<?php
}
?>
	
	
	
</div>


<script>
	$(function()
	{
		$('#tabs').tabs();
	});
<?php
/*
if(0 < count($Conectar_Listas))
{
?>
	$("<?=implode(', ', $Conectar_Listas)?>" ).sortable(
	{
		items: 'li:not(.no_sortable)',
		connectWith: ".connectedSortable"
	}).disableSelection();
<?php
}*/
?>
	$(".sortable" ).sortable(
	{
		items: 'li:not(.no_sortable)',
		connectWith: ".sortable"
	}).disableSelection();





</script>

<style type="text/css">
	#tabs{
		font-size: 11px;
		line-height: 13px;
	}
	.ui-tabs .ui-tabs-panel{
		padding: 2px 1px;
	}
	.sortable
	{
		font-size: 10px;
		padding: 0px;
		padding-bottom: 20px;
		width: 132px;
		margin: 0px;
		float: left;
		margin-left: 2px;
		background: #eeeeee;
	}
	.sortable li:not(.no_sortable){
		border: 1px solid #000;
		margin: 0;
		padding: 2px;
		background: #fff;
		margin-bottom: 3px;
		overflow: hidden;
	}
	.no_sortable{
		border: 1px solid #eeeeee;
		background: #eeeeee;
		text-align: center;
		font-weight: bold;
	}
</style>

