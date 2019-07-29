<style>
	#info_anilox div{
		float: left;
		margin-right: 20px;
	}
	.txt_encabezado, .txt_encabezado:focus, .txt_encabezado:disabled{
		border: none;
		color: #333333;
		background: #ffffff;
	}
	.resaltado{
		font-weight: bold;
		background: #eeeeee;
	}
	.clie_titutlo{
		font-size: 15px;
		display: block;
		margin-bottom: 5px;
	}
</style>


<form id="agregar_clientes" method="post" action="/clientes/modificar_clientes/modificar_datos" onsubmit="return validar('agregar_clientes')">
	
	<input type="hidden" name="icliente" value="<?=$general['id_cliente']?>" />

	<strong class="clie_titutlo">Informaci&oacute;n General</strong>
	<table>
		<tr>
			<td>Oficina</td>
			<td colspan="3">
				<select name="cpais">
<?php
foreach($Paises_C as $pCod => $pNomb)
{
?>
					<option value="<?=$pCod?>"<?=($general['pais']==$pCod)?' selected="selected"':''?>><?=$pNomb?></option>
<?php
}
?>
				</select>
				<span class="pais_<?=$general['pais']?>" id="span_pais"></span>
			</td>
		</tr>
		<tr>
			<td>C&oacute;digo:</td>
			<td><strong><?=$general['codigo_cliente']?></strong></td>
			<td>Empresa:*</td>
			<td>
				<input type="text" name="nombre" size="35" class="requ" value="<?=$general['nombre']?>" />
			</td>
		</tr>
		<tr>
			<td>NIT:*</td>
			<td>
				<input type="text" name="nit" size="20" class="requ" value="<?=$general['nit']?>" />
			</td>
			<td>D&iacute;as Cr&eacute;dito:*</td>
			<td>
				<input type="text" name="credito" size="5" class="requ" value="<?=$general['credito']?>" />
			</td>
		</tr>
		<tr>
			<td>Direcci&oacute;n:*</td>
			<td><input type="text" name="direccion" size="35" class="requ" value="<?=$general['direccion']?>" /></td>
			<td>Web</td>
			<td><input type="text" name="web" size="35" value="<?=$general['web']?>" /></td>
		</tr>
	</table>
	
	
	
	<br />
	<strong class="clie_titutlo">Informaci&oacute;n de Contacto</strong>
	<span class="manita" info="contacto">[+] Agregar Contacto</span>

	<table class="nopadding">
		<thead>
			<tr>
				<th>Contacto</th>
				<th>Cargo</th>
				<th>E-mail</th>
				<th>T. Oficina</th>
				<th>T. Directo</th>
				<th>T. Celular</th>
			</tr>
		</thead>
		<tbody id="info_contacto" class="info_filas">
<?php
foreach($contacto as $value){
?>
			<tr>
				<td><strong>[-]</strong> <input type="text" name="clie_contacto[]" size="20" value="<?=$value['nombre']?>" /></td>
				<td><input type="text" name="clie_cargo[]" size="20" value="<?=$value['cargo']?>" /></td>
				<td><input type="text" name="clie_email[]" size="20" value="<?=$value['email']?>" /></td>
				<td><input type="text" name="clie_tofic[]" size="9" value="<?=$value['tel_oficina']?>" /></td>
				<td><input type="text" name="clie_tdire[]" size="9" value="<?=$value['tel_directo']?>" /></td>
				<td><input type="text" name="clie_tcelu[]" size="9" value="<?=$value['tel_celular']?>" /></td>
			</tr>
<?php
}
?>
		</tbody>
	</table>
	

	<br /><br />
	<strong class="clie_titutlo">M&aacute;quinas Impresoras</strong>
	<span class="manita" info="maquina">[+] Agregar M&aacute;quina</span>

	<table>
		<thead>
			<tr>
				<th>M&aacute;quina</th>
				<th>Colores</th>
			</tr>
		</thead>
		<tbody id="info_maquina" class="info_filas">
<?php
foreach($maquina as $value){
?>
			<tr>
				<td><strong>[-]</strong><input type="text" name="maquina[]" size="40" value="<?=$value['maquina']?>" /></td>
				<td><input type="text" name="colores[]" size="5" value="<?=$value['colores']?>" /></td>
			</tr>
<?php
}
?>
		</tbody>
	</table>
	

	<br /><br />
	<strong class="clie_titutlo">Configuraci&oacute;n de Planchas</strong>
	<span class="manita" info="plancha">[+] Agregar Plancha</span>

	<table>
		<thead>
			<tr>
				<th>Altura</th>
				<th>Lineaje</th>
				<th>Marca</th>
			</tr>
		</thead>
		<tbody id="info_plancha" class="info_filas">
<?php
foreach($plancha as $value)
{
?>
			<tr>
				<td><strong>[-]</strong><input type="text" name="altura[]" size="20" value="<?=$value['altura']?>" /></td>
				<td><input type="text" name="lineaje[]" size="5" value="<?=$value['lineaje']?>" /></td>
				<td><input type="text" name="marca[]" size="25" value="<?=$value['marca']?>" /></td>
			</tr>
<?php
}
?>
		</tbody>
	</table>

	
	<br /><br />
	<strong class="clie_titutlo">Listado de Anilox</strong>
	<span class="manita" info="anilox">[+] Agregar Anilox</span>

	<div id="info_anilox">
<?php
for($i = 1; $i <= 3; $i++)
{
?>
		<div>
			<input type="text" size="9" class="txt_encabezado" disabled="disabled" value="Lineaje" />
			<input type="text" size="7" class="txt_encabezado" disabled="disabled" value="BCM" />
			<input type="text" size="7" class="txt_encabezado" disabled="disabled" value="Cantidad" />
		</div>
<?php
}
foreach($anilox as $value)
{
?>
		<div>
			<input type="text" size="9" name="anilox[]" value="<?=$value['anilox']?>" />
			<input type="text" size="7" name="bcm[]" value="<?=$value['bcm']?>" />
			<input type="text" size="7" name="ani_cantidad[]" value="<?=$value['cantidad']?>" />
		</div>
<?php
}
?>
	</div>
	
	

	<br style="clear:both;" /><br />
	<strong class="clie_titutlo">Listado de Productos</strong>
<?php
if(isset($productos))
{
?>
	<table id="cliente_productos">
		<tr>
			<th>Descripci&oacute;n</th>
			<th>Precio</th>
			<th>Concepto</th>
			<th>Asignar</th>
		</tr>
<?php
	foreach($productos as $Datos){
?>
		<tr id="tr-<?=$Datos['id_producto']?>" <?=(isset($producto[$Datos['id_producto']]))?'class="resaltado"':''?>>
			<td><?=$Datos['producto']?></td>
			<td>
				<input type="text" name="clie_prod_precio[]" size="7" value="<?=(isset($producto[$Datos['id_producto']]))?$producto[$Datos['id_producto']]['precio']:''?>" />
				<input type="hidden" name="hid_clie_prod[]" value="<?=$Datos['id_producto']?>" />
			</td>
			<td>
				<input type="text" name="clie_concepto[]" size="7" value="<?=(isset($producto[$Datos['id_producto']]))?$producto[$Datos['id_producto']]['concepto']:''?>" />
			</td>
			<td>
				<input type="checkbox" name="chk_clie_prod_<?=$Datos['id_producto']?>" info="tr-<?=$Datos['id_producto']?>"<?=(isset($producto[$Datos['id_producto']]))?' checked="checked"':''?> />
			</td>
		</tr>
<?php
	}
?>
				</table>
<?php
}
?>
				<br />
				<input type="submit" value="Guardar Cliente" />

				<br /><br />

</form>





<script>
				
	$('[name="nombre"]').focus();


	$('[name="cpais"]').change(function()
	{
		$('#span_pais').removeClass();
		$('#span_pais').addClass('pais_'+$(this).val());
	});



	//Almacenar las filas
	var Filas = {};

	//Agregar contactos
	Filas['contacto'] = '<tr>';
	Filas['contacto'] = Filas['contacto'] + '<td><strong>[-]</strong> <input type="text" name="clie_contacto[]" size="20" /></td>';
	Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_cargo[]" size="20" /></td>';
	Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_email[]" size="20" /></td>';
	Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_tofic[]" size="9" /></td>';
	Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_tdire[]" size="9" /></td>';
	Filas['contacto'] = Filas['contacto'] + '<td><input type="text" name="clie_tcelu[]" size="9" /></td>';
	Filas['contacto'] = Filas['contacto'] + '</tr>';

	
	//Agregar maquina
	Filas['maquina'] = '<tr>';
	Filas['maquina'] = Filas['maquina'] + '<td><strong>[-]</strong><input type="text" name="maquina[]" size="40" /></td>';
	Filas['maquina'] = Filas['maquina'] + '<td><input type="text" name="colores[]" size="5"/></td>';
	Filas['maquina'] = Filas['maquina'] + '</tr>';

	
	//Agregar plancha
	Filas['plancha'] = '<tr>';
	Filas['plancha'] = Filas['plancha'] + '<td><strong>[-]</strong><input type="text" name="altura[]" size="20" /></td>';
	Filas['plancha'] = Filas['plancha'] + '<td><input type="text" name="lineaje[]" size="5" /></td>';
	Filas['plancha'] = Filas['plancha'] + '<td><input type="text" name="marca[]" size="25" value="Kodak Flexcel NX" /></td>';
	Filas['plancha'] = Filas['plancha'] + '</tr>';


	//Agregar anilox
	Filas['anilox'] = '';
	for(i = 1; i <= 3; i++)
	{
		Filas['anilox'] = Filas['anilox'] + '<div>';
		Filas['anilox'] = Filas['anilox'] + '<input type="text" size="9" name="anilox[]" />';
		Filas['anilox'] = Filas['anilox'] + '<input type="text" size="7" name="bcm[]" />';
		Filas['anilox'] = Filas['anilox'] + '<input type="text" size="7" name="ani_cantidad[]" />';
		Filas['anilox'] = Filas['anilox'] + '</div>';
	}


	$('span.manita').click(function()
	{
		$('#info_'+$(this).attr('info')).append(Filas[$(this).attr('info')]);
	});

	$('.info_filas').on('click', 'strong', function()
	{
		$(this).parent().parent().remove();
	});

	$('#cliente_productos [type="checkbox"]').click(function()
	{
		if(true == $(this).prop('checked'))
		{
			$('#'+$(this).attr('info')).addClass('resaltado');
		}
		else
		{
			$('#'+$(this).attr('info')).removeClass('resaltado');
		}
	});

</script>
