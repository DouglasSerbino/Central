<script type="text/javascript" src="/html/js/info_cilindro.js?n=1"></script>
<style>
	.prueba
	{
		float: left;
		margin-left: 10px;
	}
	
	td, th{
		border-right: solid 1px;
		border-left: solid 1px;
		border-color: #aaaabb;
	}
	th{
		font-size: 9px;
	}
	
	fieldset {
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
	}
	
	.header-fixed {
		top:0px;
		position:fixed;
		padding-right:20px;
		width:910px;
		z-index:4	;
		height: 40px;
		-webkit-box-shadow:rgb(245,244,240) 0 5px 10px;
	}
	
	.header{
		position: fixed;
		bottom: 250px;
	}

	.encabezados th, .encabezados td{
		padding: 1px 2px;
	}
	
	.encabezados th, .tbordes th{
		background: #fdb930;
	}
</style>
<script type="text/javascript">
  $(document).ready(function(){
		for(a = 0; a < 13; a++)
		{
			var celda = $('#td'+a).width();
			$('#th'+a).attr('width', (celda+1)+'px');
		}

		$(window).scroll(function()
		{
      var scrolltop = $(window).scrollTop();
      if(scrolltop >= 310)
			{
        $("#header").addClass("header-fixed");
				$("#idca").removeClass("tabular");
				$('#idca').addClass('encabezados');
				$("#header").addClass("header");
      }
			else
			{
       $(".header-fixed").removeClass("header-fixed");
			 $("#idca").addClass("tabular");
			 $("#header").removeClass("header");
      }
    });
  })
</script>
<?php
$polimero = array('1' => '0.045', '2' => '0.067', '3' => '0.107','4' => '0.112');
$stickyback = array('1' => '0.015', '2' => '0.020', '3' => '0.060');

?>

<div class='contenido'>
	<fieldset style='border: solid 1px #aaaabb; width: 72%;'>
		<legend>&nbsp;&nbsp;Buscar Desarrollos&nbsp;&nbsp;</legend>
		<form method='post' id='cilindro' action='/herramientas_sis/info_cilindro_keep/index'>
			<table>
				<tr>
					<td style='border: none;'>
						<strong>Desarrollo en mm.</strong>
						<input type='text' name='pulgas_desa' id='pulgas_desa' value="<?=$pulgas_desa?>" size='5px'>
					</td>
					<td style='border: none;'>
						<label for='mostrar_div'>Tolerancias</label>
						<input type='checkbox' id='mostrar_div' name='mostrar_div' onclick="ver_tolerancia()" <?=($mostrar_div != '')?' checked="checked"':''?>>
					</td>
					<td style='border: none;'>
						<div  id='mostrar' <?=($mostrar_div != '')?' style="display:block;"':'style="display:none;"'?>>
							<strong>-</strong> <input type='text' name='menos' id='menos' size='2px' value="<?=$menos?>">
							<strong>+</strong> <input type='text' name='mas' id='mas' size='2px' value="<?=$mas?>">
						</div>
					</td>
					<td style='border: none;'>
						<input type='submit' value='Buscar' onclick='return validar_info();'>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>
	<br />
	
	<label for='mostrar_informacion1'>Tabla de Conversi&oacute;n de Cilindros</label>
	<input type='checkbox' id='mostrar_informacion1' onclick="ver_tabla(1)">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<br /><br />
	
	<!--position: fixed; margin-top: auto; bottom: 170px; -->
<table id='idca' class='tabular' style='width: 910px;'>
	<tr id='header' style='text-align: center; font-size: 10px;'>
		<th id='th0'>Articulo</th>
		<th id='th1'>Detalle</th>
		<th id='th2'>Cod</th>
		<th id='th3'>Cliente</th>
		<th id='th4'>Colores</th>
		<th id='th5'>Impr.</th>
		<th id='th6'>Ancho Cliente</th>
		<th id='th7'>Repe. <br /> ancho</th>
		<th id='th8'>Ancho Impre.</th>
		<th id='th9'>Largo del Cliente</th>
		<th id='th10'>#Repet.<br />al des.</th>
		<th id='th11'>Largo de Impr.</th>
		<th id='th12'>Desarrollo de Manga</th>
	</tr>
</table>

<table class='tabular' style='width: 910px; padding: 0px; padding-left: 0px;'>
<?php
$estilo = 'text-align: center;';
$a = 0;
if(isset($Informacion_cilindro))
{
	foreach($Informacion_cilindro as $tipo => $informacion)
	{
		//echo count($informacion).'<br>';
		if(count($informacion) != 0)
		{
?>	
	<tr>
		<td colspan='13'>	
		<strong>DESARROLLOS <?=strtoupper($tipo)?> A <?=$pulgas_desa?> mm</strong>
		</td>
	</tr>
<?php
		//Variable que almacenara el total de trabajos clasificados por impresora.
	$trab_impresora = 0;
		foreach($informacion['impres'] as $impresora)
		{
			$trab_impresora_ind = 0;
			foreach($impresora['info_maquinas'] as $Datos)
			{
				$trab_impresora_ind++;
			}
?>
	<tr>
		<td colspan='13'>
			<center><strong>IMPRESORA <?=strtoupper($impresora['maquina'])?> ( <?=$trab_impresora_ind?>  ) </strong></center>
		</td>
	</tr>
<?php
			foreach($impresora['info_maquinas'] as $Datos)
			{
?>
	<tr style='font-size: 11px;'>
		<td <?=($a==0)?"id='td0' ":''?>style="width: 60px;"> <?=$Datos['articulo']?></td>
		<td <?=($a==0)?"id='td1' ":''?>style="width: 230px;"> <?=$Datos['detalle']?></td>
		<td <?=($a==0)?"id='td2' ":''?>style="width: 50px;"><?=$Datos['cod']?></td>
		<td <?=($a==0)?"id='td3' ":''?>style="width: 100px;"><?=$Datos['cliente']?></td>
		<td <?=($a==0)?"id='td4' ":''?>style="width: 42px;"><?=$Datos['colores']?></td>
		<td <?=($a==0)?"id='td5' ":''?>style="width: 60px;"><?=$Datos['impresora']?></td>
		<td <?=($a==0)?"id='td6' ":''?>style="width: 50px;"><?=$Datos['ancho_cliente']?></td>
		<td <?=($a==0)?"id='td7' ":''?>style="width: 50px;"><?=$Datos['repe_alto']?></td>
		<td <?=($a==0)?"id='td8' ":''?>style="width: 50px;"><?=$Datos['ancho_impresion']?></td>
		<td <?=($a==0)?"id='td9' ":''?>style="width: 50px;"><?=$Datos['largo_cliente']?></td>
		<td <?=($a==0)?"id='td10' ":''?>style="width: 50px;"><?=$Datos['repe_desarrollo']?></td>
		<td <?=($a==0)?"id='td11' ":''?>style="width: 50px;"><?=$Datos['largo_impresion']?></td>
<?php
	$estado = $Datos['largo_impresion'] / 25.4;
?>
		<td <?=($a==0)?"id='td12' ":''?>style="width: 50px; <?=$estilo?>"><?=number_format($estado, 2)?> (<?=$Datos['largo_impresion']?>mm)</td>
	</tr>
<?php
		$trab_impresora++;
		$a++;
			}
		}
?>
	<tr>
		<td colspan='13' style='text-align: right;'><strong>TRABAJOS  <?=strtoupper($tipo)?> A <?=$mandar_pulgas?><label style='font-size: 15px;'></label>:&nbsp;&nbsp;&nbsp;&nbsp;<label style='font-size: 15px;'><?=$trab_impresora?></label></strong></td>
	</tr>
<?php
		}
		else
		{
?>
	<tr>
		<td colspan='13'><strong>DESARROLLOS <?=strtoupper($tipo)?> A <?=$mandar_pulgas?> mm</strong></td>
	</tr>
	<tr>
		<td colspan='13' style='text-align: right;'><strong>TRABAJOS <?=strtoupper($tipo)?> A <?=$mandar_pulgas?>:&nbsp;&nbsp;&nbsp;&nbsp;<label style='font-size: 15px;'>0</label></strong></td>
	</tr>
<?php
		}
	}
}
?>
	<tr>
		<td colspan='13' style='text-align: right;'><br />TOTAL DE TRABAJOS&nbsp;&nbsp;&nbsp;&nbsp;<strong><?=$a?></strong>&nbsp;&nbsp;<br />&nbsp;</td>
	</tr>
</table>
</div>

<!--para mostrar las tablas convertidas en milimetros-->

<div id='mostrar_tabla_conversion1' style='display: none; position: absolute; top: 120px; left: 10px; background-color: #ffffff; width: 100%;'>
	<br />
	<input type='submit' value='Ocultar Tabla' id='ocultar_tabla1' onclick='ocultar_tabla();'>
	<br />
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Desnudo</th>
			<th>0.067</th>
			<th>0.045</th>
			<th>Mangas</th>
		</tr>
<?php
$e= 1;
$fin = count($Cilindro_Desnudo);
$a = 1;
foreach($Cilindro_Desnudo as  $Datos)
{
	if($a == 26 or $a == 51 or $a == 76)
	{
?>
	</table>
	<table class="tabular prueba">
		<tr>
			<th>#</th>
			<th>Desnudo</th>
			<th>0.067</th>
			<th>0.045</th>
			<th>Mangas</th>
		</tr>
<?php
	}
?>
		<tr style='text-align: center;'>
			<td style='background-color: #feebc5;'><?=$e?></td>
			<td><?=number_format(($Datos['mili'] * 3.1416) , 3)?></td>
			<td style='background-color: #feebc5;'><?=number_format((($Datos['mili'] + (((0.067 * 2) + (0.020 * 2)) * 25.4)) * 3.1416), 3)?></td>
			<td><?=number_format((($Datos['mili'] + (((0.045 * 2) + (0.020 * 2)) * 25.4)) * 3.1416), 3)?></td>
			<td><?=$Datos['num_cilindros']?></td>
		</tr>
<?php
	
	if($a == $fin)
	{
?>
	</table>
	
<?php
	}
	$e++;
	$a++;
}
?>
<br style="clear:both;" /><br /><br />&nbsp;
</div>