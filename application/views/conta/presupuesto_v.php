<style>
	td, th{
		padding: 0px;
	}
	[type="text"]{
		margin: 0px;
		width: 85%;
	}
</style>


<strong>A&ntilde;o</strong>
<select id="mc_anho_cambia">
<?php
$Fecha_Fin = date('Y') + 2;
for($i = 2015; $i <= $Fecha_Fin; $i++)
{
?>
	<option value="<?=$i?>"<?=($i==$Anho)?' selected="selected"':''?>><?=$i?></option>
<?php
}
?>
</select>
<br /><br />



<div id="mc_linea_presu" style="display:none;">
	<strong>Establecer Presupuesto</strong>
	
	<form action="/conta/presupuesto/modificar" method="post" onsubmit="return validar();">
		
		<input type="hidden" name="anho" value="<?=$Anho?>" />
		<input type="hidden" name="id_mc_linea" id="id_mc_linea" />
		
		<table style="width:100%;">
			<tr>
				<th>L&iacute;nea</th>
<?php
foreach($Meses_v as $iMes => $nMes)
{
?>
				<th style="width:6%;"><?=$nMes?></th>
<?php
}
?>
			</tr>
			<tr>
				<td id="mc_nomb_line"></td>
<?php
foreach($Meses_v as $iMes => $nMes)
{
?>
				<td><input type="text" name="mc_pres_<?=$iMes?>" id="mc_pres_<?=$iMes?>" /></td>
<?php
}
?>
			</tr>
		</table>
		
		<input type="submit" value="Guardar" />
		<input type="button" value="Cancelar" />
		
	</form>
	
	<br />
	
</div>



<table id="mc_linea_presupuesto" class="tabular" style="width: 100%;">
	<tr>
		<th>L&iacute;nea</th>
<?php
foreach($Meses_v as $iMes => $nMes)
{
?>
		<th style="width:6%;"><?=$nMes?></th>
<?php
}
?>
	</tr>
<?php
recorrer_lineas($Lineas[0], 0, $Lineas, $Meses_v, $Presupuesto);

function recorrer_lineas(
	$Recorrer,
	$Id_Padre,
	array & $Lineas,
	array & $Meses_v,
	array & $Presupuesto
)
{
	foreach($Recorrer as $Id_Mc_Linea => $Datos)
	{
		
		$Hijos = false;
		if(isset($Lineas[$Id_Mc_Linea]))
		{
			
			$Hijos = true;
			
			recorrer_lineas(
				$Lineas[$Id_Mc_Linea],
				$Id_Mc_Linea,
				$Lineas,
				$Meses_v,
				$Presupuesto
			);
			
		}
		
		if(!$Hijos)
		{
?>
	<tr>
		<td>
			<span info="<?=$Id_Mc_Linea?>" class="toolder">
				<i class="iconos ieditar"></i>
				<?=$Datos['codigo'].' - '.$Datos['linea']?>
				<span>Modificar L&iacute;nea</span>
			</span>
			
			<input type="hidden" id="lin_<?=$Id_Mc_Linea?>" value="<?=$Datos['codigo'].' - '.$Datos['linea']?>" />
		</td>
<?php
			foreach($Meses_v as $iMes => $nMes)
			{
				$Presu_Mes_Linea = 0;
				if(isset($Presupuesto[$Id_Mc_Linea][$iMes]))
				{
					$Presu_Mes_Linea = $Presupuesto[$Id_Mc_Linea][$iMes];
				}
?>
		<td class="derecha">$<?=number_format($Presu_Mes_Linea, 0)?><input type="hidden" id="pres_<?=$Id_Mc_Linea?>_<?=$iMes?>" value="<?=$Presu_Mes_Linea?>" /></td>
<?php
			}
?>
	</tr>
<?php
		}
		
	}
}



foreach($Lineas as $Id_Mc_Linea => $Principal)
{
	foreach($Principal as $Id_sMc_Linea => $SubMC)
	{
	}
}
?>
</table>


<script>
	$('#mc_anho_cambia').change(function()
	{
		window.location = '/conta/presupuesto/index/'+$(this).val();
	});
	
	
	$('#mc_linea_presupuesto .toolder').click(function()
	{
		
		var Meses_v = ['01','02','03','04','05','06','07','08','09','10','11','12'];
		var Id_Linea = $(this).attr('info');
		$('#id_mc_linea').val(Id_Linea);
		$('#mc_nomb_line').empty().append($('#lin_'+Id_Linea).val());
		
		for(i = 0; i < 12; i++)
		{
			$('#mc_pres_'+Meses_v[i]).val($('#pres_'+Id_Linea+'_'+Meses_v[i]).val());
		}
		
		
		$('#mc_linea_presu').show();
		$('#mc_pres_01').focus();
	});
	
	$('#mc_linea_presu [type="button"]').click(function()
	{
		$('#mc_linea_presu').hide();
	});
</script>
