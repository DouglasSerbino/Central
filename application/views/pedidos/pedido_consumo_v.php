
<strong>CONSUMOS</strong>
<table class="tabular" style="width: 100%">
	<tr>
		<th style="width: 120px;">Material</th>
		<th>Descripci&oacute;n</th>
		<th style="width: 200px;">Cantidad</th>
		<th style="width: 150px; text-align: center;">Reproceso</th>
	</tr>
<?
foreach($Consumo as $Material)
{
?>
	<tr>
		<td><?=$Material['codigo_sap']?></td>
		<td><?=$Material['nombre_material']?></td>
<?php
		if($this->session->userdata('codigo') == 'Sistemas')
		{
		?>
		<td style="text-align: left;"><input type='text' style='width: 75px; border: none;' name='matye' value='<?=$Material['cantidad']?>' onkeypress="if (event.keyCode == 13) guardar($(this).val(), <?=$Material['id_inventario_material']?>, <?=$Id_Pedido?>)" >&nbsp;&nbsp;&nbsp;<?=$Material['tipo']?></td>
		<td style="text-align: center;"><label id='che<?=$Material['id_inventario_material']?>' onclick='modificar($(this).text(), <?=$Material['id_inventario_material']?>, <?=$Id_Pedido?>)' ><?=('on'==$Material['reproceso'] || 'true'==$Material['reproceso'])?' Si':'No'?></label></td>
		<?php
		}
		else
		{
?>
		<td style="text-align: left;"><?=number_format($Material['cantidad'], 2)?> &nbsp;&nbsp;&nbsp;<?=$Material['tipo']?></td>
		<td style="text-align: center;"><?=('on'==$Material['reproceso'] || 'true'==$Material['reproceso'])?' Si':'No'?></td>
<?php
		}
?>
	</tr>
<?
}
?>
</table>