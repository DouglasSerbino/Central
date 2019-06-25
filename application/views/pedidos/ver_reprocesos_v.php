<script type="text/javascript" src="/html/js/thickbox.js"></script>
<link rel="stylesheet" href="/html/css/thickbox.css" type="text/css" media="screen" />
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
		</tr>
	</table>
	
	<div id='corte_pagina'></div>

	<table style='width: 90%'>
		<tr>
			<th>CARGAR IMAGEN DE MUESTRA</th>
		</tr>
		<tr>
			<td>
				IMAGEN RESULTADO <br />
				<a href="<?=$Reproceso['img1']?>" class="thickbox" title="">
					<img width="200px" height="225px" src="<?=$Reproceso['img1']?>" title="IMAGEN RESULTADO">
				</a>
			</td>
			<td>
				IMAGEN REFERENCIA <br />
				<a href="<?=$Reproceso['img2']?>" class="thickbox" title="">
					<img width="200px" height="225px" src="<?=$Reproceso['img2']?>" title="IMAGEN REFERENCIA">
				</a>
			</td>
		</tr>
		<tr style='background: #fdb930'>
			<th colspan='2'>ANTECEDENTES</th>
		</tr>
		<tr>
			<td>
				<label><?=$Reproceso['antecedentes']?></label>
			</td>
		</tr>
		<tr style='background: #fdb930'>
			<th colspan='2'>OBSERVACIONES</th>
		</tr>
		<tr>
			<td>
				<label><?=$Reproceso['observaciones']?></label>
			</td>
		</tr>
		<tr style='background: #fdb930'>
			<th colspan='2'>PROCEDIMIENTOS DE MEJORA</th>
		</tr>
		<tr>
			<td>
				<label><?=$Reproceso['mejora']?></label>
				<br />&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				IMAGEN RESULTADO <br />
				<a href="<?=$Reproceso['img3']?>" class="thickbox" title="">
					<img width="200px" height="225px" src="<?=$Reproceso['img3']?>" title="IMAGEN RESULTADO">
				</a>
			</td>
			<td>
				IMAGEN REFERENCIA <br />
				<a href="<?=$Reproceso['img4']?>" class="thickbox" title="">
					<img width="200px" height="225px" src="<?=$Reproceso['img4']?>" title="IMAGEN REFERENCIA">
				</a>
			</td>
		</tr>
		<tr style='background: #fdb930'>
			<th colspan='2'>COMENTARIOS ADICIONALES</th>
		</tr>
		<tr>
			<td>
				<label><?=$Reproceso['adicionales']?></label>
				<br />&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				IMAGEN RESULTADO <br />
				<a href="<?=$Reproceso['img5']?>" class="thickbox" title="">
					<img width="200px" height="225px" src="<?=$Reproceso['img5']?>" title="IMAGEN RESULTADO">
				</a>
			</td>
			<td>
				IMAGEN REFERENCIA <br />
				<a href="<?=$Reproceso['img6']?>" class="thickbox" title="">
					<img width="200px" height="225px" src="<?=$Reproceso['img6']?>" title="IMAGEN REFERENCIA">
				</a>
			</td>
		</tr>
	</table>
</form>