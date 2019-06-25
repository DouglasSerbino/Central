<script type="text/javascript" src="/html/js/hojas_revision.js?n=1"></script>
<?
foreach($Cliente_Procesos as $Datos)
{
	$id_cliente = $Datos["codigo_cliente"];
	$proceso = $Datos["proceso"];
	$cliente = $Datos["cliente"];
	$nombre_p = $Datos["nombre"];
}
?>
<div class="informacion">
	<div><strong><?=$id_cliente.'-'.$proceso?></strong></div>
		Cliente: <strong><?=$cliente?></strong><br />
		Producto: <strong><?=$nombre_p?></strong><br /><br />
		
		<form name="miform" id="miform" action="/hojas_revision/hoja_revision/hojas_sql" method="post">
			<input type="hidden" value="offset" name="tipo" id="tipo" />
			<input type="hidden" value="" name="neg_arte" id="neg_arte" />
			<input type="hidden" value="<?=$Id_pedido?>" name="id_pedido" />
			
			<div id="revi_1" class="margen_revision">
				<strong>1. NEGATIVO CONTRA EL ARTE</strong><br />
				<input type="checkbox" name="medida" id='medida' />
				<label for='medida'>Medidas A-B y C-D</label><br />
				<input type="checkbox" name="diseno" id='diseno' />
				<label for='diseno'>Dise&ntilde;o</label><br />
				<input type="checkbox" name="cod_barra" id='cod_barra' /> 
				<label for='cod_barra' >C&oacute;digo de Barras</label><br />
				<input type="checkbox" name="color" id='color' />
				<label for='color'>Secuencia de Color</label><br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_1','revi_2','neg_arte', 'OK')" />&nbsp;&nbsp;&nbsp;<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_1','revi_2','neg_arte', 'N/A')" />
			</div>
			
			<div id="revi_2" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>2. PLANCHA POR COLOR</strong><br />
<?
$i = 1;
foreach($Buscar_color as $Datos)
{
?>
			<input type="checkbox" name="color_<?=$i?>" id='color_<?=$i?>' />
			<input type="hidden" name="color_n" value="<?=$Datos["color"]?>" /> &nbsp;<label for='color_<?=$i?>'><?=$Datos["color"]?></label><br />
<?php	
	$i++;
}
?>
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="color_mos('revi_2','revi_3')" />&nbsp;&nbsp;&nbsp;<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="color_mos('revi_2','revi_3')" />
			</div>
			
			<div id="revi_3" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>3. M&Aacute;QUINA IMPRESORA</strong><br />
				<input type="checkbox" name="gto" value='1' id='gto' />
				<label for='gto'>GTOV</label><br />
				<input type="checkbox" name="m74" value='2' id='m74' />
				<label for='m74'>SPEEDMASTER 74</label><br />
				<input type="checkbox" name="sor_z" value='3' id='sor_z' />
				<label for='sor_z'>ZORS Z</label><br />
				<input type="checkbox" name="speed_master" value='4' id='speed_master' />
				<label for='speed_master'>SPEEDMASTER 72</label><br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="color_mos('revi_3','revi_4')" />&nbsp;&nbsp;&nbsp;<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="color_mos('revi_3','revi_4')" />
			</div>
			
			<div id="revi_4" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>4. OBSERVACIONES</strong><br />
				<textarea name="observaciones" cols="60" rows="10"></textarea><br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="color_mos('revi_4','fin')" />&nbsp;&nbsp;&nbsp;<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="color_mos('revi_4','fin')" />
			</div>
		</form>
</div>