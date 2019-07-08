<script>
	$(function()
	{
		$("[name=fecha_despacho]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
	});
</script>
<div class="informacion">
	<div class="informacion_top"><div></div></div>
	<div class="informacion_cont">
	<strong>Busqueda por fecha</strong>
	<form action="/notas/despacho/index" method="post" name="miform">
		
		Cliente: <input type="text" name="id_cliente" size="8" /> &nbsp; 
		Fecha Despacho: <input type="text" readonly="readonly" name="fecha_despacho" size="12" value="" id='fecha_despacho' />
		<input type="submit" class="boton" value="Buscar" />
		
	</form>
	

<?php
//Verificamos si hay informacion del cliente ingresado.
if($id_cliente != "")
{
	//Verificamos si hay pedidos correspondientes al cliente ingresado.
	//Contamos si hay pedidos.
	//Si los hay procedemos.
	$num = count($Notas);
	//Si las no
	if($num > 0)
	{
?>
<br  />
	
	<strong>
<?php
//Exploramos el array para mostrar la informacion.
foreach($clientes as $Datos_cliente)
{
	echo $Datos_cliente["nombre"];
}
?>
			</strong>
	<form name="miform" action="/notas/nota_envio" method="post">
		<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>" />
		
		<table class="tabular">
			<tr>
				<th>Proceso</th>
				<th>&nbsp;&nbsp;&nbsp;Producto</th>
			</tr>
<?php
		//Definimos las variables.
		$contador = 0;
		$i = 0;
		//Exploramos el array.
		foreach($Notas as $Datos_notas)
		{
			//Asignamos los valores a las variables.
			$id_proceso = $Datos_notas["proceso"];
			$producto = $Datos_notas["producto"];
			$id_nota_env = $Datos_notas['id_nota_env'];
			$id_cliente = strtoupper($id_cliente);
			$contador++;
?>
				<tr>
					<td>
						<input type="checkbox" name="nota_<?=$i?>" id="nota_<?=$i?>" value="<?=$Datos_notas["id_pedido"]?>" />
						<label for="nota_<?=$i?>"><?=$id_cliente.'-'.$id_proceso?></label>
<?php
	//Verificamos si ya se creo la nota de envio para este pedido.
		if('' != $id_nota_env)
		{
			//Si ya se creo le damos la opcion para poder verla.
			echo " <a href=\"#\" onclick=\"javascript:window.open('/notas/nota_ver/index/".$id_nota_env."');\">(*)</a>";
		}
?>
				</td>
				<td>&nbsp;&nbsp;&nbsp;<?=$producto?></td>
			</tr>
<?php	
			$i++;
		}
?>
		</table>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="boton" value="Generar Nota" />
		<input type="hidden" name="cajas" value="<?php echo $i; ?>" />
	</form>
	(*) Ya fue creada una nota de envio para este pedido. Click para ver hoja completa.
<?php
	}
}
?>
	</div>
	<div class="informacion_bot"><div></div></div>
</div>