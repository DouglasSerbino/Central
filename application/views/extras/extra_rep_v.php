<script type="text/javascript" src="/html/js/extra.js?n=1"></script>
<div class="informacion">
	<a href="/extras/extras/index/<?=$anho.'/'.$mes?>" title="Regresar al calendario de Horas Extras"><strong>&laquo;&laquo; Regresar &laquo;&laquo;</strong></a>
	
	<form name="miform" id='miform' action="/extras/extra_rep/index/" method="post" onsubmit="return validar_extra();">
		
		<strong>Rango de Fechas</strong><br />
		
		Inicio: &nbsp; 
		<input type="text" size="3" value="<?=$dia1?>" name="dia1" id="dia1" />
		<input type="text" size="3" value="<?=$mes1?>" name="mes1" id="mes1" />
		<input type="text" size="6" value="<?=$anho1?>" name="anho1" id="anho1" /> &nbsp; &nbsp; 
		
		Finalizaci&oacute;n &nbsp; 
		<input type="text" size="3" value="<?=$dia2?>" name="dia2" id="dia2" />
		<input type="text" size="3" value="<?=$mes2?>" name="mes2" id="mes2" />
		<input type="text" size="6" value="<?=$anho2?>" name="anho2" id="anho2" /><br />
		
		Tipo de Reporte: &nbsp; 
		<input type="radio" name="tipo" id="detalle" value="detalle" <?=($tipo == "detalle")?' checked="checked"':''?> onclick="habi_desh('de')" /><label for="detalle"><strong>Detalle de Horas</strong></label>

<?php
if($this->session->userdata('codigo') != 'SAP')
{
?>
	<!-- input type="radio" name="tipo" id="presupuesto" value="presupuesto" <?php if($tipo == "presupuesto") echo "checked=\"checked\""; ?> onclick="habi_desh('ha')" /><label for="presupuesto"><strong>Presupuesto</strong></label> &nbsp; 
		<select id='pre_mes' name="pre_mes" <?php if($tipo == "detalle") echo "disabled=\"disabled\""; ?>>
<?php
$meses_v = array("", "Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
for($i = 1; $i <= 12; $i++)
{
	if($i < 10)
	{
		$iii = "0$i";
	}
	else
	{
		$iii = $i;
	}
?>
			<option value="<?=$iii?>"<?=($pre_mes == $i)?' selected="selected"':''?>><?=$meses_v[$i]?></option>
<?php
}

$inicio_a = 2008;
$fin_a = date("Y");
$anho_select = "";
?>
		</select>
		<select id='pre_anho' name="pre_anho"<?=($tipo == "detalle")?' disabled="disabled"':''?>>
<?php
for($i = $inicio_a; $i <= $fin_a; $i++)
{
?>
			<option value="<?=$i?>"<?=($i == $pre_anho)?' selected="selected"':''?>><?=$i?></option>
<?php
}
?>
		</select--> &nbsp;
<?php
}
?>
		<input type="submit" class="boton" value="Generar Reporte" />
		
	</form>
	
<?php
//Indica que debo mostrar los datos
if($mostrar == 'ok')
{
	if($tipo == "detalle")
	{//Si hay que mostrar detalle de horas extras hago esto...
?>
	
	<hr width="80%" />
	
	<strong>Horas Extras Ingresadas</strong><br />
	
<?php
		$nombre_ant = "";
		$horas_t1 = 0;
		$horas_t2 = 0;
		$money_t1 = 0;
		$money_t2 = 0;
		$muestra = explode('-', $Administrador);
		$a = 0;
		foreach($HExtras as $Datos_extras)
		{

			$fecha = $Datos_extras["fecha"];
			$nombre = $Datos_extras["nombre"];
			$hora = $Datos_extras["hora"];
			$inicio = $Datos_extras["inicio"];
			$fin_real = $Datos_extras["fin_real"];
			$fin = $Datos_extras["fin"];
			$total_h = $Datos_extras["total_h"];
			$total_m = $Datos_extras["total_m"];
				
			$usu_adm = $muestra[$a];
			
			if($nombre != $nombre_ant)
			{
				if($nombre_ant != "")
				{
?>
		</table>
		<table class='tabla_i' style='margin-left: 24em;'>
			<tr>
				<th>&nbsp;</th>
				<th></th>
				<th>Total:</th>
				<th><?=number_format($horas_t1, 2)?> horas<br>&nbsp;</th>
<?php
	if($this->session->userdata('codigo') != 'SAP')
	{
?>
				<th>$ <?=number_format($money_t1, 3)?></th>
			</tr>
<?php
	}
?>
		</table>
<?php
				}
?>
		<strong><?=strtoupper($nombre)?></strong>
		<table class="tabla_i" style='width: 60%;'>
<?php				
				$nombre_ant = $nombre;
				$horas_t1 = 0;
				$money_t1 = 0;
			}
			
			$horas_t1 += $total_h;
			$horas_t2 += $total_h;
			$money_t1 += $total_m;
			$money_t2 += $total_m;
?>
			<tr>
<?php
	$fecha_mostrar = explode('-',$fecha);
?>
				<td style='width: 30%;'><?=$fecha_mostrar[2].'-'.$fecha_mostrar[1].'-'.$fecha_mostrar[0]?>&nbsp; <?=$usu_adm?></td>
				<td style='width: 35%;'> &nbsp; <?=$inicio?> a <?=$fin?> ( <?=$fin_real?> )</td>
				<td><strong><?=number_format($total_h, 2)?> horas</strong></td>
<?php
	if($this->session->userdata('codigo') != 'SAP')
	{
?>
				<td><strong>$<?=number_format($total_m, 3)?></strong></td>
<?php
	}
?>
			</tr>
<?php
			$a++;
		}
	if($this->session->userdata('codigo') != 'SAP')
	{
?>
		<tr>
			<th>&nbsp;</th><th>Total:</th>
			<th><?php echo number_format($horas_t1, 2); ?> horas</th>
			<th>$<?php echo number_format($money_t1, 3); ?></th>
		</tr>
		<tr>
			<th colspan="4">&nbsp;</th>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>Total:</th>
			<th><?php echo number_format($horas_t2, 2); ?> horas</th>
			<th>$<?php echo number_format($money_t2, 3); ?></th></tr>
<?php
	}
?>
			</table>
	<br />
	<a href="/extras/extra_rep_imp/index/<?=$dia1.'/'.$mes1.'/'.$anho1.'/'.$dia2.'/'.$mes2.'/'.$anho2?>" target="_blank"><strong>Imprimir Reporte</strong></a>
	
<?php
	}
	elseif($tipo == 'presupuesto')
	{//Debo mostrar el presupuesto de las horas....
?>
	<hr width="80%" />
	
	<strong>Reporte Presupuesto Horas Extras</strong>
	
	<table class="tabla_100">
		<tr>
			<td><strong>Departamento</strong></td>
			<td><strong>Proyectado</strong></td>
			<td><strong>Utilizado</strong></td>
			<td><strong>Porcentaje</strong></td>
			<td><strong>Total Horas</strong></td>
		</tr>
<?php
		$tth = 0;
		$ttm = 0;
		$tp = 0;
		//Exploramos el array para obtener la informacion.
		foreach($proyecciones as $Informacion)
		{
			$id_dpto = $Informacion["id_dpto"];
			$codigo = $Informacion["codigo"];
			$departamento = $Informacion["departamento"];
			$proyeccion = $Informacion["proyeccion"];
		
			$total_h = $Informacion["horas"];
			$total_m = $Informacion["t_d"];
			
			$tth += $total_h;
			$ttm += $total_m;
?>
			<tr>
				<td>&raquo; <a href="/extras/extra_rep_dep/index/<?=$id_dpto.'/'.$pre_mes.'/'.$pre_anho?>/presupuesto"><?=$codigo.' - '.$departamento?></a></td>
				<td>$ <?=number_format($proyeccion, 2)?></td>
				<td>$ <?=number_format($total_m, 3)?></td>
<?php
	$porcentaje_m = 0;
	if($proyeccion > 0)
	{
		$porcentaje_m = ($total_m * 100) / $proyeccion;
	}
?>
				<td><?=number_format($porcentaje_m, 2)?> %</td>
				<td><?=number_format($total_h, 2)?></td>
			</tr>
<?php			
				$tp += $proyeccion;
				$total_h = 0;
				$total_m = 0;
			
		}
?>
		<tr>
			<th>Totales</th>
			<th><?="$ ".number_format($tp, 2)?></th>
			<th><?="$ ".number_format($ttm, 3)?></th>
			<th><?php if($tp > 0) echo number_format(($ttm * 100) / $tp, 2)." %"; else echo "0 %"; ?></th>
			<th><?=number_format($tth, 2)?></th>
		</tr>
	</table>
<?php
	}
}
?>
</div>