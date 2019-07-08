<script type="text/javascript" src="/html/js/req_sel_add.js?n=1"></script>
<script type="text/javascript" src="/html/js/detalle.js?n=1"></script>
<style>
	.nombre_material{
		border: none;
		border-bottom: 1px solid #555555;
	}
	.nombre_material:focus{
		border: none;
		background-color: #fff;
		border-bottom: 1px solid #555555;
	}
	.nombre_material:disabled{
		background: none;
	}
	table{
		line-height: 10px;
	}
</style>

<div class="informacion">
	
	<div class="informacion_top"><div></div></div>
	
	<div class="informacion_cont">
		
		<form name="miform" action="/inventario/inventario_req/requisar_material/" method="post">
			<table style="float: left;">
				<tr><th>Material</th><th>Descripci&oacute;n</th><th>Cantidad</th></tr>
<?php for($i_m_t = 0; $i_m_t < 12; $i_m_t++){ ?>
				<tr>
					<td><input class="nombre_material" type="text" size="12" name="codigo_material_<?php echo $i_m_t; ?>" id="codigo_material_<?php echo $i_m_t; ?>" onblur="ver_material('_<?php=$i_m_t?>')" /></td>
					<td>
						<input class="nombre_material" type="text" size="50" name="nombre_material_<?php echo $i_m_t; ?>" id="nombre_material_<?php echo $i_m_t; ?>" value="" readonly="readonly" />
						<input type="hidden" id="id_material_<?php echo $i_m_t; ?>" name="id_material_<?php echo $i_m_t; ?>" value="" />
					</td>
					<td>
						<input class="nombre_material" type="text" size="12" name="cantidad_material_<?php echo $i_m_t; ?>" id="cantidad_material_<?php echo $i_m_t; ?>" />
					</td>
				</tr>
<?php } ?>
			</table>
			
			<div style="float: left; margin-left: 25px; width: 300px;">
				<br />
				<strong>Importante:</strong>
				
				<br />
				Si la cantidad a requisar de un material no est&aacute; disponible en bodega no se realizar&aacute; la requisici&oacute;n para &eacute;l.
				
				<br /><br />
				Favor confirmar la existencia de materiales primero.
			</div>
			
			
			<br style="clear: both;" /><br />
			<input type="submit" class="boton" value="Requisar Material" />
			
		</form>
		
		<br /><br />
		
		<strong>Revisar Existencias</strong><br />
		
		<form name="existe" action="requisar.php" method="get">
			
			<table>
				<tr>
					<td>Nombre:</td>
					<td><input type="text" name="nbus" id="nbus" size="12" onkeyup="cambia_sel2()" /></td>
					<td>Material:</td>
					<td>
						<select name="id_material2" id="id_material2">
							<option value="--">--</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Existencias:</td>
					<td colspan="3"><input type="text" name="cantidad2" id="cantidad2" size="25" value="0" readonly="readonly" /></td>
				</tr>
			</table>
			<input type="button" class="boton" value="Mostrar Existencias" onclick="existencia()" />
		</form>
	</div>
	<div class="informacion_bot"><div></div></div>
</div>