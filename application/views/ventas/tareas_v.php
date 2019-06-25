



<div id="tabs">
	
	<ul>
		<li><a href="#pend">Tareas Pendientes</a></li>
		<li><a href="#fina">Tareas Finalizadas</a></li>
	</ul>
	
	<div id="pend">
		
		<table class="tabular" style="width: 100%;">
<?
foreach($Incompletas as $Id_Tarea => $Tarea)
{
	$f = $this->fechas_m->fecha_subdiv($Tarea['fecha_creada']);
	$Tarea['fecha_creada'] = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
?>
			<tr>
				<td>
					<strong><?=$Tarea['solicitado']?> [<?=$Tarea['fecha_creada']?>]</strong>
					<br />
<?
if('central-g' == $this->session->userdata('grupo'))
{
?>
					<span id="tarea-<?=$Id_Tarea?>" class="iconos iterminado toolizq tarea_fin"><span>Finalizar tarea</span></span>
<?
}
?>
					<span id="tarea-<?=$Id_Tarea?>-tarea"><?=nl2br($Tarea['tarea'])?></span>
					<br /><br />
					<strong>Asignado a:</strong> <?=$Tarea['asignado']?>
					<br />
					<strong>Fecha L&iacute;mite:</strong> <?=$Tarea['fecha_estimada']?>
				</td>
			</tr>
<?
}
?>
		</table>
		
	</div>
	
	
	<div id="fina">
		
		<?=$Paginacion?>
		
		<table class="tabular" style="width: 100%;">
<?
foreach($Finalizadas as $Tarea)
{
	$f = $this->fechas_m->fecha_subdiv($Tarea['fecha_creada']);
	$Tarea['fecha_creada'] = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
	$f = $this->fechas_m->fecha_subdiv($Tarea['fecha_realizada']);
	$Tarea['fecha_realizada'] = $f["dia"]."-".$f["mes"]."-".$f["anho"]." ".$f["hora"].":".$f["minuto"].":".$f["segundo"];
?>
			<tr>
				<td>
					<strong><?=$Tarea['solicitado']?> [<?=$Tarea['fecha_creada']?>]</strong>
					<br />
					<strong class="raquo">&raquo;</strong> <?=nl2br($Tarea['tarea'])?>
					<br /><br />
					<strong><?=$Tarea['realizado']?> [<?=$Tarea['fecha_realizada']?>]</strong>
					<br />
					<strong class="raquo">&raquo;</strong> <?=nl2br($Tarea['observaciones'])?>
				</td>
			</tr>
<?
}
?>
	</table>
	
	<?=$Paginacion?>
	
	</div>
</div>


<div id="fin_tarea" style="display: none;">
	
	<form name="fin_tarea" action="/ventas/tareas/finalizar" method="post">
		
		<input type="hidden" id="finta_id_tarea" name="finta_id_tarea" value="" />
		
		<strong>Finalizar Tarea:</strong>
		
		<br />
		<span id="finta_tarea"></span>
		
		<br /><br />
		Agregar Comentario:
		<br />
		<textarea name="finta_comentario" id="finta_comentario" cols="80" rows="4"></textarea>
		
		<br />
		<input type="button" value="Cancelar" onclick="$('#fin_tarea').hide();" />
		<input type="submit" value="Finalizar" />
		
	</form>
	
</div>



<script type="text/javascript">
	$(function()
	{
		//$('#tabs').tabs(<?=('fina'==$Ver)?'{"select": "1"}':''?>);
		$('#tabs').tabs();
		$('.tarea_fin').click(function()
		{
			var tarea = $(this).attr('id');
			$('#finta_tarea').empty();
			$('#finta_tarea').append('"'+$('#'+tarea+'-tarea').text()+'"');
			tarea = tarea.split('-');
			$('#finta_id_tarea').val(tarea[1]);
			$('#fin_tarea').show();
			$('textarea#finta_comentario').val('');
			$('textarea#finta_comentario').focus();
			
		});
	});
	$(document).ready(function()
	{
		$('#tabs').tabs(<?=('fina'==$Ver)?'"select", 1':''?>);
	});
</script>


<style>
	#fin_tarea{
		position: absolute;
		margin-left: 100px;
		background: #ffffff;
		border: 1px solid #F8C773;
		margin-bottom: 55px;
		padding: 15px 25px;
		width: 600px;
		top: 100px;
		line-height: 15px;
	}
	#finta_tarea{
		font-style: italic;
	}
	.tabular td, th{
		padding: 15px;
		border-bottom: 1px solid #c7c7c7;
	}
	.raquo{
		font-size: 20px;
	}
</style>


