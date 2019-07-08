<div class="informacion">
	<form name="miform" action="/pedidos/hoja_produccion/index" method="POST">
		<strong>Ingrese el N&uacute;mero de Proceso</strong><br />
		<input type="text" name="cod_cliente" id="cliente_hoja" size="8" />&nbsp;<strong>-</strong>&nbsp;
		<input type="text" name="proceso" id="proceso_hoja" size="15" />&nbsp;
		<input type="submit" class="boton" value="Buscar" />
	</form>
</div>

<?php
if('' != $cod_cliente)
{
	?>
<div class="informacion">
	
	<div class="informacion_top"><div></div></div>
	
	<div class="informacion_cont">
		Hojas coincidentes:<br />
<?php
foreach($informacion_procesos as $Datos)
{
?>
	&raquo;<a href="/pedidos/tiempo_consumo/index/<?=$Datos["id_pedido"]?>" target="_blank"><?=$Datos["sap"]?></a>
<?php
	if($Datos["fecha"] == "0000-00-00")
	{
		echo " En proceso";
	}
	else
	{
		echo " ".$Datos["fecha"];
	}
	echo "<br />\n";
}
?>
	</div>
</div>
<?php
}
?>