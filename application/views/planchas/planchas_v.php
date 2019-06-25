<script type="text/javascript" src="/html/js/plancha.js?n=1"></script>
<?

$Codigo_Plancha = '';
if($codigo_pla <> "0")
{
	foreach($plancha_especifica as $Datos)
	{
		$codigo_pla = $Datos["cod_plancha"];
		$altura = $Datos["grosor"];
		$ubicacion = $Datos["ubicacion"];
		$tipo = $Datos["tipo"];
		$id_plancha = $Datos['id_plancha'];
	}
	
	$hdn = "<input type=\"hidden\" name=\"cod_viejo\" value=\"$codigo_pla\" />\n";
	$btnmodificar = "Modificar";
	$Codigo_Plancha = ': '.$codigo_pla;
	if($tipo == "Digital")
	{
		$ana = "";
		$digi = "selected=\"selected\"";
	}
	else
	{
		$ana = "selected=\"selected\"";
		$digi = "";
	}
}
?>
	<strong><?=$btnmodificar?> Plancha<?=$Codigo_Plancha?></strong>
	
	<form name="miform" method="post" action="/planchas/planchas/planchas_modagr" onsubmit="return validar_m();">
		<table>
			<tr>
				<td>C&oacute;digo de plancha:</td>
				<td><input type="text" value="<?=$codigo_pla?>" name="codigo" /></td>
				<td>Altura:</td>
				<td><input type="text" value="<?=$altura?>" name="altura" /></td>
			</tr>
			<tr>
				<td>Ubicaci&oacute;n:</td>
				<td><input type="text" value="<?=$ubicacion?>" name="ubicacion" /></td>
				<td>Tipo:</td>
				<td>
					<select name="tipo">
						<option <?=$ana?> value="An&aacute;loga">An&aacute;loga</option>
						<option <?=$digi?> value="Digital">Digital</option>
					</select> &nbsp;
					<?=$hdn?>
					<input type="hidden" value="<?=$modifiqueme?>" name="modifiqueme" />
					<input type="hidden" value="<?=$codigo_pla?>" name="codigo_pla" />
					<input type="hidden" value="<?=$id_plancha?>" name="id_pla" />
					<input type="submit" class="boton" value="<?=$btnmodificar?>" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	
	<strong>Listado de Tipos de planchas</strong>
	
	<table class="tabular" style='width: 65%;'>
		<tr>
			<th>C&oacute;digo</th>
			<th>Altura</th>
			<th>Ubicaci&oacute;n</th>
			<th>Tipo</th>
			<th>&nbsp;</th>
		</tr>
<?
$i = 0;
foreach($tipo_planchas as $Datos)
{
?>
		<tr>
			<td><strong><?=$Datos["cod_plancha"]?></strong></td>
			<td><?=$Datos["grosor"]?></td>
			<td><?=$Datos["ubicacion"]?></td>
			<td><?=$Datos["tipo"]?></td>
			<td>
				<a href="/planchas/planchas/index/<?=$Datos["cod_plancha"]?>" class="iconos ieditar toolder"><span>Modificar Plancha</span></a>
				<span codp="<?=$Datos["cod_plancha"]?>" class="iconos ieliminar toolder"><span>Eliminar Plancha</span></span>
			</td>
		</tr>
<?php	
	$i++;
}
?>
	</table>
	
	</div>
</div>


<script>
	$('.ieliminar').click(function()
	{
		if(confirm('Esta Plancha sera eliminada y todo el historial relacionado a ella. Desea Continuar?'))
		{
			window.location = '/planchas/planchas/eliminar/'+$(this).attr('codp');
		}
	});
</script>