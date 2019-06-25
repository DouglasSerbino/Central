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
	<div class="inf_titulo"><strong><?=$id_cliente.'-'.$proceso?></strong></div>
		Cliente: <strong><?=$cliente?></strong><br />
		Producto: <strong><?=$nombre_p?></strong><br /><br />
		
		<form name="miform" id="miform" action="/hojas_revision/hoja_revision/hojas_sql" method="post">
			<input type="hidden" value="" name="diseno" id="diseno" />
			<input type="hidden" value="" name="fotocelda" id="fotocelda" />
			<input type="hidden" value="" name="separado" id="separado" />
			<input type="hidden" value="" name="montaje" id="montaje" />
			<input type="hidden" value="" name="codigo_barra" id="codigo_barra" />
			<input type="hidden" value="" name="negativo" id="negativo" />
			<input type="hidden" value="preprensa" name="tipo" id="tipo" />
			<input type="hidden" value="<?=$Id_pedido?>" name="id_pedido" />
			
			<div id="revi_1" class="margen_revision">
				<strong>1. DISE&Ntilde;O CONTRA EL ARTE</strong><br />
				Textos.<br />
				Logotipo de Cliente.<br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_1','revi_2','diseno','OK')" />&nbsp;&nbsp;&nbsp;
				<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_1','revi_2','diseno','N/A')" />
			</div>
			
			<div id="revi_2" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>2. FOTOCELDA</strong><br />
				Tama&ntilde;o.<br />
				Color.<br />
				Posici&oacute;n.<br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_2','revi_3','fotocelda','OK')" />&nbsp;&nbsp;&nbsp;
				<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_2','revi_3','fotocelda','N/A')" />
			</div>
			
			<div id="revi_3" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>3. ARTE SEPARADO</strong><br />
				Dise&ntilde;o.<br />
				Traslapes.<br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_3','revi_4','separado','OK')" />&nbsp;&nbsp;&nbsp;
				<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_3','revi_4','separado','N/A')" />
			</div>
			
			<div id="revi_4" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>4. MONTAJE FINAL</strong><br />
				Texto contra Arte Aprobado.<br />
				# Repeticiones en Ancho.<br />
				# Repeticiones en Circunferencia.<br />
				Desface en Circunferencia.<br />
				Embobinado.<br />
				Secuencia de Colores.<br />
				Gu&iacute;as de Registro Manual.<br />
				Gu&iacute;as Micropuntos o Cruces.<br />
				Separaci&oacute;n entre repeticiones.<br />
				G&iacute;a de Corte.<br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_4','revi_5','montaje','OK')" />&nbsp;&nbsp;&nbsp;
				<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_4','revi_5','montaje','N/A')" />
			</div>
			
			<div id="revi_5" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>5. C&Oacute;DIGO DE BARRAS</strong><br />
				Tipo.<br />
				N&uacute;mero.<br />
				Factor de Magnificaci&oacute;n.<br />
				Posici&oacute;n (MD o TD).<br />
				Color.<br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_5','revi_6','codigo_barra','OK')" />&nbsp;&nbsp;&nbsp;
				<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_5','revi_6','codigo_barra','N/A')" />
			</div>
			
			<div id="revi_6" class="margen_revision" style="visibility:hidden; display:none;">
				<strong>6. NEGATIVOS</strong><br />
				Impresi&oacute;n (Cara Dorso).<br />
				Lineajes.<br />
				&Aacute;ngulos.<br />
				Densidad.<br />
				Trama.<br />
				Distorsi&oacute;n.<br />
				<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_6','fin','negativo','OK')" />&nbsp;&nbsp;&nbsp;
				<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_6','fin','negativo','N/A')" />
			</div>
		</form>
	</div>
</div>