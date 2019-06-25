<script type="text/javascript" src="/html/js/administracion.js?n=1"></script>
<style type="text/css" media="all">@import "/html/css/administracion.css";</style>
<form method='post' action='/pedidos/agregar_sap/index'>
	<table>
		<tr>
			<td>
				Proceso:
			</td>
			<td><input type="text" name="codigo_cliente" id="codigo_cliente" size="2" />-<input type="text" name="proceso" id="proceso" size="20" /></td>
			<td>
				&nbsp; <input type='submit' value='Buscar'>
			</td>
		</tr>
	</table>
<div>
<?php

if(count($procesos) != 0)
{
foreach($procesos as $Datos)
{
?>
	<a href="/pedidos/pedido_detalle/index/<?=$Datos['id_pedido']?>" target="_blank" title="Abrir detalle en nueva ventana">
	<img src="/html/img/nueva_v.png" alt="Aparte" /></a>
	<?=$this->fechas_m->fecha_ymd_dmy($Datos['fecha_entrada'])?>
	&nbsp;&nbsp;
	<a href="javascript:haps_agr_sap_prev('<?=$Datos['id_pedido']?>' , '<?=$Datos['fecha_entrada']?>' , '<?=$Datos['id_cliente']?>');">[+]</a><br />
<?php
}
}
?>
</div>
</form>
	<!-- Aqui van los divs ocultos que solicitan informacion adicional -->
	<div id="haps-informacion_sap" style="display:none;">
	<form method='post' action='/pedidos/agregar_sap/agregar_pedido_sap/<?=isset($_POST['codigo_cliente'])?>/<?=isset($_POST['proceso'])?>'>
		<strong>Informaci&oacute;n Sap &nbsp; &nbsp;
		<a href="javascript:ocultar_ventana('haps-informacion_sap')">[x]</a></strong><br />
		<input type="hidden" name="id_pedido" id="id_pedido" value="" />
		<input type="hidden" name="id_cliente" id="id_cliente" value="" />
		<table>
			<tr><td>Pedido Sap:</td><td><input type="text" name="pedido_sap" id="pedido_sap" /></td></tr>
			<tr><td>Fecha:</td><td><input type="text" name="fecha" id="fecha" /></td></tr>
			<tr><td>Venta:</td><td><input type="text" name="venta" id="venta" /></td></tr>
			<tr><td>Ordenes:</td><td><input type="text" name="ordenes" id="ordenes" /> <cite>Separadas por coma (,)</cite></td></tr>
			<tr><td colspan='2'><input type='submit' value='[Agregar]'></td></tr>
		</table>
	</form>
	</div>
