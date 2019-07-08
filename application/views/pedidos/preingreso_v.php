<table class="tabular" style="width: 100%">
	<tr>
		<th style="width: 95px;">Proceso</th>
		<th style="width: 80px;">&nbsp;</th>
		<th>Trabajo</th>
		<th style="width: 85px;">F. Ingreso</th>
		<th style="width: 120px;">F. Solicitado</th>
		<th style="width: 80px;">&nbsp;</th>
	</tr>
<?php
if(isset($Pedidos[0]))
{
	foreach($Pedidos[0] as $Index => $Datos)
	{
		$conta = count($Datos);
?>
	<tr>
		<td colspan='7' style='font-size: 13px;'><strong><?=$Index?>&nbsp;(<?=$conta?>)</strong></td>
	</tr>
<?php
	
		foreach($Datos as $Pedido)
		{
?>
	<tr>
		<td>
			<a href="/pedidos/pedido_detalle/index/<?=$Pedido['id_pedido']?>" class="toolizq">
				<?=$Pedido['codc']?>-<?=$Pedido['proceso']?>
				<span>Ver detalle del Trabajo</span>
			</a>
		</td>
		<td>
<?php
			if('Ventas' != $this->session->userdata('codigo'))
			{
?>
			<a href="/pedidos/modificar/index/<?=$Pedido['id_pedido']?>" class="iconos iruta toolizq">
				<span>Ingresar Pedido</span>
			</a>
<?php
			}
?>
		</td>
		<td><?=$Pedido['proceso_nombre']?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Pedido['fecha_entrada'])?></td>
		<td><?=$this->fechas_m->fecha_ymd_dmy($Pedido['fecha_entrega'])?></td>
		<td>
<?php
			if('Ventas' == $this->session->userdata('codigo'))
			{
?>
			<a href="/pedidos/especificacion/index/<?=$Pedido["id_pedido"]?>/m" class="iconos idocumento toolder"><span>Modificar Hoja de Planificaci&oacute;n</span></a>
			<a href="/pedidos/tiempo/accion/finalizar/<?=$Pedido['id_pedido']?>/<?=$Pedido['id_peus']?>" class="iconos iterminado toolder"><span>Dar por Terminado</span></a>
<?php
			}
			else
			{
				if('Ventas' == $Pedido['codigo'])
				{
?>
		<strong>&nbsp;&nbsp;*</strong>
<?php
				}
				else
				{
?>
		<a href="javascript:rechazar('<?=$Pedido['id_pedido']?>','<?=$Pedido['id_peus']?>');" class="iconos irechazar toolder"><span>Rechazar Trabajo</span></a>
<?php
				}
			}
?>
		</td>
	</tr>
<?php
		}
	}
}
?>
</table>

<br />

<?php

$this->load->view('pedidos/rechazo_v.php');


?>

