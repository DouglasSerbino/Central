<script type="text/javascript" src="/html/js/hojas_revision.js?n=1"></script>
<?php
foreach($Cliente_Procesos as $Datos)
{
	$id_cliente = $Datos["codigo_cliente"];
	$proceso = $Datos["proceso"];
	$cliente = $Datos["cliente"];
	$nombre_p = $Datos["nombre"];
}
?>
<div class="informacion_cont">
	<div><strong><?=$id_cliente.'-'.$proceso?></strong></div>	
		Cliente: <strong><?=$cliente?></strong><br />
		Producto: <strong><?=$nombre_p?></strong><br /><br />
		
	<form name="miform" id='miform' action="/hojas_revision/hoja_revision/hojas_sql" method="post">
		<input type="hidden" value="" name="dimensiones" id="dimensiones" />
		<input type="hidden" value="" name="textos" id="textos" />
		<input type="hidden" value="" name="fotocelda" id="fotocelda" />
		<input type="hidden" value="" name="color" id="color" />
		<input type="hidden" value="" name="codigo_barra" id="codigo_barra" />
		<input type="hidden" value="arte" name="tipo" id="tipo" />
		<input type="hidden" value="<?=$Id_pedido?>" name="id_pedido" />
		
		<div id="revi_1" class="margen_revision">
			<strong>1. DIMENSIONES</strong><br />
			Ubicaci&oacute;n de TOP.<br />
			Largo de la Repetici&oacute;n A-B.<br />
			Ancho de la Repetici&oacute;n C-D.<br />
			Medidas parciales en base a Plano mec&aacute;nico o diagrama.<br />
			Gu&iacute;as Especiales (Logotipo del Cliente).<br />
			<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_1','revi_2','dimensiones','OK')" />&nbsp;&nbsp;&nbsp;
			<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_1','revi_2','dimensiones','N/A')" />
		</div>
		
		<div id="revi_2" class="margen_revision" style="visibility:hidden; display:none;">
			<strong>2. TEXTO Y DISE&Ntilde;O</strong><br />
			Textos.<br />
			Dise&ntilde;o.<br />
			Imagen o Ilustraci&oacute;n.<br />
			<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_2','revi_3','textos','OK')" />&nbsp;&nbsp;&nbsp;
			<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_2','revi_3','textos','N/A')" />
		</div>
		
		<div id="revi_3" class="margen_revision" style="visibility:hidden; display:none;">
			<strong>3. FOTOCELDA</strong><br />
			Tama&ntilde;o.<br />
			Color.<br />
			Posici&oacute;n.<br />
			<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_3','revi_4','fotocelda','OK')" />&nbsp;&nbsp;&nbsp;
			<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_3','revi_4','fotocelda','N/A')" />
		</div>
		
		<div id="revi_4" class="margen_revision" style="visibility:hidden; display:none;">
			<strong>4. GUIA DE COLOR</strong><br />
			Secuencia de Impresi&oacute;n.<br />
			Laca y/o Blanco Registrado.<br />
			<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_4','revi_5','color','OK')" />&nbsp;&nbsp;&nbsp;
			<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_4','revi_5','color','N/A')" />
		</div>
		
		<div id="revi_5" class="margen_revision" style="visibility:hidden; display:none;">
			<strong>5. C&Oacute;DIGO DE BARRAS</strong><br />
			Tipo.<br />
			N&uacute;mero.<br />
			Factor de Magnificaci&oacute;n.<br />
			Posici&oacute;n (MD o TD).<br />
			Color.<br />
			<input type="button" class="boton" value="&nbsp;&nbsp;OK&nbsp;&nbsp;" onclick="arte_mos('revi_5','fin','codigo_barra','OK')" />&nbsp;&nbsp;&nbsp;
			<input type="button" class="boton" value="&nbsp;&nbsp;N/A&nbsp;&nbsp;" onclick="arte_mos('revi_5','fin','codigo_barra','N/A')" />
		</div>
	</form>
</div>