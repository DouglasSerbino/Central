<style>
	#vclie_contenedor{
		display: none;
	}
	#info_anilox div{
		float: left;
		margin-right: 20px;
	}
	.txt_encabezado, .txt_encabezado:focus, .txt_encabezado:disabled{
		border: none;
		color: #333333;
		background: #ffffff;
	}
	.txt_encabezado.normal{
		font-weight: normal;
	}
</style>

Mostrar: 
<select id="clie_acti_inac">
		<option value="s">Activos</option>
		<option value="n"<?=('n'==$Activo)?' selected="selected"':''?>>Inactivos</option>
</select>

<br />


<div id="vclie_contenedor">

	<strong>Informaci&oacute;n del Cliente</strong>

	<br /><br />
	<strong>Empresa: <span id="vclie_empresa"></span></strong>
	
	<br />
	C&oacute;digo:
	<span id="vclie_codig"></span>

	&nbsp; &nbsp;
	NIT:
	<span id="vclie_nit"></span>
	
	&nbsp; &nbsp;
	D&iacute;as Cr&eacute;dito:
	<span id="vclie_credito"></span>
	
	<br />
	Direcci&oacute;n:
	<span id="vclie_direccion"></span>
	
	&nbsp; &nbsp;
	Web:
	<span id="vclie_web"></span>


	<br /><br />
	<strong>Contactos</strong>
	<table>
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
		<tbody id="info_contacto"></tbody>
	</table>
	

	<br />
	<strong>M&aacute;quinas Impresoras</strong>
	<table>
		<thead>
			<tr>
				<th>M&aacute;quina</th>
				<th>Colores</th>
			</tr>
		</thead>
		<tbody id="info_maquina"></tbody>
	</table>
		

	<br />
	<strong>Configuraci&oacute;n de Planchas</strong>

	<table>
		<thead>
			<tr>
				<th>Altura</th>
				<th>Lineaje</th>
				<th>Marca</th>
			</tr>
		</thead>
		<tbody id="info_plancha"></tbody>
	</table>

	
	<br />
	<strong>Listado de Anilox</strong>
	<div id="info_anilox">
<?
for($i = 1; $i <= 3; $i++)
{
?>
		<div>
			<input type="text" size="9" class="txt_encabezado" disabled="disabled" value="Lineaje" />
			<input type="text" size="7" class="txt_encabezado" disabled="disabled" value="BCM" />
			<input type="text" size="7" class="txt_encabezado" disabled="disabled" value="Cantidad" />
		</div>
<?
}
?>
	</div>
		
		

		<br style="clear:both;" /><br />
		<strong>Listado de Productos</strong>
		<table>
				<tr>
						<th>Descripci&oacute;n</th>
						<th>Precio</th>
						<th>Concepto</th>
				</tr>
				<tbody id="cliente_productos"></tbody>
			</table>

	<br style="clear:both;" />


	<input type="button" value="Cerrar" onclick="$('#vclie_contenedor').hide();" />
	<br /><br />

</div>



<?=$Paginacion?>

<table class="tabular" id='tabla' style="width:100%">
		<tr>
				<th>C&oacute;digo</th>
				<th>Nombre</th>
				<th>Usuario</th>
				<th>Contrase&ntilde;a</th>
				<th>Pa&iacute;s</th>
				<th>Opciones</th>
		</tr>
<?php
foreach($Clientes as $Cliente)
{
?>
		<tr>
				<td><strong><?=$Cliente['codigo_cliente']?></strong></td>
				<td><?=$Cliente['nombre']?></td>
				<td><?=$Cliente['usuario']?></td>
				<td><?=$Cliente['contrasena']?></td>
				<td><?=$Paises_C[$Cliente['pais']]?></td>
				<td>
						<span info="<?=$Cliente['id_cliente']?>" class="iconos idocumento toolder vis_cliente"><span>Visualizar Cliente</span></span>
						<a href="/clientes/modificar_clientes/mostrar_datos/<?=$Cliente['id_cliente']?>" class="iconos ieditar toolder"><span>Modificar Cliente</span></a>
<?php
if($Cliente['activo'] == 's')
{
?>
						<a href="/clientes/desactivar_activar/accion/d/<?=$Cliente['id_cliente']?>" class="iconos ieliminar toolder"><span>Desctivar Cliente</span></a>
<?php
}
else
{
?>
						<a href="/clientes/desactivar_activar/accion/a/<?=$Cliente['id_cliente']?>" class="iconos ireactivar toolder"><span>Reactivar Cliente</span></a>
<?php
}
?>
				</td>
		</tr>    
		<?php
}
?>
</table>

<?=$Paginacion?>




<script>
	$('#clie_acti_inac').change(function()
	{
		window.location = '/clientes/administrar_clientes/index/'+$(this).val();
	});



	$('.vis_cliente').click(function()
	{

		var Info_Cliente = $(this).attr('info');

		$.ajax({
			type: "POST",
			url: "/clientes/administrar_clientes/ver_info_cliente/"+Info_Cliente,
			success: function(msg)
			{

				msg = JSON.parse(msg);

				$('#vclie_empresa').empty().append(msg.general.nombre);
				$('#vclie_codig').empty().append(msg.general.codigo_cliente);
				$('#vclie_direccion').empty().append(msg.general.direccion);
				$('#vclie_nit').empty().append(msg.general.nit);
				$('#vclie_web').empty().append(msg.general.web);
				$('#vclie_credito').empty().append(msg.general.credito);


				$('#info_contacto').empty();
				for(i in msg.contacto)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>'+msg.contacto[i].nombre+'</td>';
					Fila = Fila + '<td>'+msg.contacto[i].cargo+'</td>';
					Fila = Fila + '<td>'+msg.contacto[i].email+'</td>';
					Fila = Fila + '<td>'+msg.contacto[i].tel_oficina+'</td>';
					Fila = Fila + '<td>'+msg.contacto[i].tel_directo+'</td>';
					Fila = Fila + '<td>'+msg.contacto[i].tel_celular+'</td>';
					Fila = Fila + '</tr>';
					$('#info_contacto').append(Fila);
				}

				
				$('#info_maquina').empty();
				for(i in msg.maquina)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>'+msg.maquina[i].maquina+'</td>';
					Fila = Fila + '<td>'+msg.maquina[i].colores+'</td>';
					Fila = Fila + '</tr>';
					$('#info_maquina').append(Fila);
				}

				
				$('#info_plancha').empty();
				for(i in msg.plancha)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>'+msg.plancha[i].altura+'</td>';
					Fila = Fila + '<td>'+msg.plancha[i].lineaje+'</td>';
					Fila = Fila + '<td>'+msg.plancha[i].marca+'</td>';
					Fila = Fila + '</tr>';
					$('#info_plancha').append(Fila);
				}

				
				$('#info_anilox').empty();
				for(i in msg.anilox)
				{
					Valores_Comunes = 'type="text" class="txt_encabezado normal" disabled="disabled"';
					Fila = '<div>';
					Fila = Fila + '<input '+Valores_Comunes+' size="9" value="'+msg.anilox[i].anilox+'" />';
					Fila = Fila + '<input '+Valores_Comunes+' size="7" value="'+msg.anilox[i].bcm+'" />';
					Fila = Fila + '<input '+Valores_Comunes+' size="7" value="'+msg.anilox[i].cantidad+'" />';
					Fila = Fila + '</div>';
					$('#info_anilox').append(Fila);
				}

				
				$('#cliente_productos').empty();
				for(i in msg.producto)
				{
					Fila = '<tr>';
					Fila = Fila + '<td>'+msg.producto[i].producto+'</td>';
					Fila = Fila + '<td>'+msg.producto[i].precio+'</td>';
					Fila = Fila + '<td>'+msg.producto[i].concepto+'</td>';
					Fila = Fila + '</tr>';
					$('#cliente_productos').append(Fila);
				}


				$('#vclie_contenedor').show();
				window.scrollTo(0, 0);

			},
			error: function(msg){ alert("Ocurrio un Error " + msg + ".<br />Haga una captura de pantalla para realizar una verificacion del problema."); }
		});
	});


</script>

