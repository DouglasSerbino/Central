<style>
	td, th
	{
		
	}
</style>
<!--Datos generales-->
	<table style="width: 90%">
		<tr>
			<td style="width: 65px;">Proceso:</td>
			<th><?=$Proceso['codigo_cliente']."-".$Proceso['proceso']?></th>
		</tr>
		<tr>
			<td>Cliente:</td><th colspan="3">
				<?=$Proceso['nombre']?>
				</th>
		</tr>
		<tr>
			<td>Producto:</td>
			<th colspan="3"><?=$Proceso['nombre_proceso']?></th>
			<td style='width: 50px;'><input type="button" onclick="cargar_scan(6)" value="Subir Archivo" class="boton" /></td>
		</tr>
	</table>

	<div id='corte_pagina'></div>
<form enctype="multipart/form-data" id="formscan" name="formscan" method="post" action="/scan/cargar_scan">
<input type="hidden" value="<?=$Proceso['id_proceso']?>-<?=$Id_Pedido?>" id="scan_pedido" name="scan_pedido" />
<input type="hidden" value="/pedidos/detalle_activo/index/<?=$Id_Pedido?>" name="scan_redireccion" />
	<table style='width: 90%'>
		<tr>
			<th>CARGAR IMAGEN DE MUESTRA</th>
		</tr>
		<tr>
			<td colspan='2'>IMAGEN RESULTADO   &nbsp;&nbsp;<input type='file' value='' title='Examinar' class="span_archivo" id='archivo_0' name='archivo_0' /></td>
		</tr>
		<tr>
			<td colspan='2'>IMAGEN REFERENCIA &nbsp;<input type='file' value='' title='Examinar' class="span_archivo" id='archivo_1' name='archivo_1' /></td>
		</tr>
		<tr style='background: #fdb930'>
			<th>ANTECEDENTES</th>
		</tr>
		<tr>
			<td colspan='2'>
				<textarea name='antecedentes' style='width: 600px; height: 80px;'></textarea>
			</td>
		</tr>
		<tr style='background: #fdb930'>
			<th>OBSERVACIONES</th>
		</tr>
		<tr>
			<td colspan='2'>
				<textarea name='observaciones' style='width: 600px; height: 80px;'></textarea>
			</td>
		</tr>
		<tr style='background: #fdb930'>
			<th colspan='2' style='width: 600px;'>PROCEDIMIENTOS DE MEJORA</th>
		</tr>
		<tr>
			<td colspan='2'>
				<textarea name='mejora' style='width: 600px; height: 80px;'></textarea>
			</td>
		</tr>
		<tr>
			<td colspan='2'>IMAGEN RESULTADO   &nbsp;&nbsp;<input type='file' value='' title='Examinar' class="span_archivo" id='archivo_2' name='archivo_2' /></td>
		</tr>
		<tr>
			<td colspan='2'>IMAGEN REFERENCIA &nbsp;<input type='file' value='' title='Examinar' class="span_archivo" id='archivo_3' name='archivo_3' /></td>
		</tr>
		<tr style='background: #fdb930'>
			<th>COMENTARIOS ADICIONALES</th>
		</tr>
		<tr>
			<td colspan='2'>
				<textarea name='adicional' style='width: 600px; height: 80px;'></textarea>
			</td>
		</tr>
		<tr>
			<td colspan='2'>IMAGEN RESULTADO   &nbsp;&nbsp;<input type='file' value='' title='Examinar' class="span_archivo" id='archivo_4' name='archivo_4' /></td>
		</tr>
		<tr>
			<td colspan='2'>IMAGEN REFERENCIA &nbsp;<input type='file' value='' title='Examinar' class="span_archivo" id='archivo_5' name='archivo_5' /></td>
		</tr>
	</table>
</form>