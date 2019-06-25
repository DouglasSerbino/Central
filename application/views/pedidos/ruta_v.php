<?
$Icono_Ruta = array(
	2 => 'fdi_pdf',
	5 => 'fdi_liberacion',
	28 => 'fdi_aprobacion',
	9 => 'fpr_cilindro',
	10 => 'fpr_pt'
);
?>

<br />



<!--strong>RUTA DE TRABAJO</strong-->

Aplicar Ruta:
<select id="asigna_ruta" name="asigna_ruta">
	<option value="">Seleccione</option>
<?
foreach($Detalle_Rutas as $Id_Ruta => $Rutina)
{
?>
	<option value="<?=$Id_Ruta?>"><?=$Rutina['elemento']?></option>
<?
}
?>
</select>


<br />
<div id="ruta_trabajo">
<?
//Quien tiene asignado el trabajo actualmente?
$Puesto_Asignado = 0;
//Puestos originales, para comparar si se realizaron cambios
$Puestos_Originales = array();
//La fecha de entrega se calcula para los pedidos que se agregaran con ruta cero,
//si es modificar ruta, no se calcula entrega
$Agrega_Modifica = 'agrega';

if(0 == count($Ruta_Actual))
{
	//echo 'Es ingreso nuevo';
}



?>
</div>



<div id="ruta_trabajos"></div>


<script>
	
<?
$Usuarios_Array_v = array();
foreach($Dpto_Usuario as $Index => $Fila)
{
	$Usuarios_Array_v[$Index]['dpto'] = $Fila['dpto'];
	$Usuarios_Array_v[$Index]['tiempo'] = $Fila['tiempo'];
	$Usuarios_Array_v[$Index]['usuarios'] = array();
	foreach($Fila['usuarios'] as $Iusute => $Infosote)
	{
		unset($Infosote['activo'], $Infosote['programable'], $Infosote['usuario']);
		$Usuarios_Array_v[$Index]['usuarios'][$Iusute] = $Infosote;
	}
}
?>
	var Icono_Ruta = <?=json_encode($Icono_Ruta, true)?>;
	var Dpto_Usuario = <?=json_encode($Usuarios_Array_v, true)?>;
	var Departamentos = <?=json_encode($Departamentos, true)?>;

	var Puestos_actuales = <?=count($Ruta_Actual)?>;
	
<?
foreach($Puestos_Originales as $Id_Ruta_Dpto => $Id_Usu_Dpto)
{
?>
	puestos_originales[<?=$Id_Ruta_Dpto?>] = <?=$Id_Usu_Dpto?>;
<?
}
?>
	puesto_asignado = <?=$Puesto_Asignado?>;


	var Detalle_Rutas = {};
<?
foreach($Detalle_Rutas as $Index => $Fila)
{
?>
	Detalle_Rutas[<?=$Index?>] = <?=json_encode($Fila, true)?>;
<?
}


foreach($Ruta_Actual as $Index => $Fila)
{
	if(
		is_numeric($Ruta_Actual[$Index]['tiempo_asignado'])
	)
	{
		$Ruta_Actual[$Index]['tiempo_asignado'] = $this->fechas_m->minutos_a_hora(
			$Ruta_Actual[$Index]['tiempo_asignado']
		);
	}
}
?>
var Ruta_Actual = <?=json_encode($Ruta_Actual, true)?>;
var Nuevo_Viejo = "<?=(0==count($Ruta_Actual))?'nuevo':'viejo'?>";

	$('#asigna_ruta').change(function()
	{
		if(0 != $(this).val())
		{
			
			$('#ruta_trabajo').empty();
			for(irup in Detalle_Rutas[$(this).val()].ruta)
			{

				var Iddepito = Detalle_Rutas[$(this).val()].ruta[irup].id_dpto;
				var Span_Seleccionado = ' flujo_rt_seleccionado';
				var Chequesin = ' checked="checked"';
				var val_hora = '0:00';
				var Set_Usuario = '';
				var Asignadote = '';

				if('viejo' == Nuevo_Viejo && 0 < <?=isset($Ruta_Aplicada)?$Ruta_Aplicada:'0'?>)
				{
					if(undefined == Ruta_Actual[irup])
					{
						Span_Seleccionado = '';
						Chequesin = '';
					}
					else
					{
						Set_Usuario = Ruta_Actual[irup]['id_usuario'];
						val_hora = Ruta_Actual[irup]['tiempo_asignado'];
						if(
							'Agregado' != Ruta_Actual[irup]['estado']
							&& 'Terminado' != Ruta_Actual[irup]['estado']
						)
						{
							Asignadote = ' checked="checked"';
						}
					}
				}
				

				var Puesto = '<span class="flujo_ruta_dpto fruta_'+irup+Span_Seleccionado+'">';
				Puesto = Puesto + '<div class="flujo_div1 icono_ruta_puesto">';
				Puesto = Puesto + '<span class="flicon '+Icono_Ruta[Iddepito]+'" ruta="'+irup+'"></span>';
				Puesto = Puesto + '</div>';
				Puesto = Puesto + '<div class="flujo_div2">';
				Puesto = Puesto + '<span class="toolizq">';
				Puesto = Puesto + '<input type="radio" name="puesto_asignado" id="puas_'+irup+'" value="'+irup+'"'+Asignadote+' />';
				Puesto = Puesto + '<span>Asignar Pedido a <strong>'+Departamentos[Iddepito].departamento+'</strong></span>';
				Puesto = Puesto + '</span>';
				Puesto = Puesto + Departamentos[Iddepito].departamento;
				Puesto = Puesto + '<br />';
				Puesto = Puesto + '<input type="checkbox" name="chk_'+irup+'" id="chk_'+irup+'" info="'+irup+'"'+Chequesin+' />';
				Puesto = Puesto + '<select name="slc_'+irup+'" id="slc_'+irup+'">';

				//Usuario/a que pertenecen al departamento en turno si es que hay usuarios
				if(undefined != Dpto_Usuario[Iddepito].usuarios)
				{
					for(iusu in Dpto_Usuario[Iddepito].usuarios)
					{
						Puesto = Puesto + '<option value="'+iusu+'">'+Dpto_Usuario[Iddepito].usuarios[iusu].nombre+'</option>';
					}
				}

				Puesto = Puesto + '</select>';

				if('s' == Departamentos[Iddepito].tiempo)
				{
					Puesto = Puesto + '<input type="text" size="5" value="'+val_hora+'" name="tie_'+irup+'" id="tie_'+irup+'" onblur="validar_hora(\'tie_'+irup+'\')" />';
				}
				else
				{
					Puesto = Puesto + '<input type="hidden" value="N/A" name="tie_'+irup+'" id="tie_'+irup+'" />';
				}

	
				Puesto = Puesto + '</div>';
				Puesto = Puesto + '</span>';


				$('#ruta_trabajo').append(Puesto);

				if('' != Set_Usuario)
				{
					$('#slc_'+irup).val(Set_Usuario);
				}

			}
			
		}
	});
	
	
	$('.sele_puestos').click(function()
	{
		var chequear = false;
		if('Todo' == $(this).text())
		{
			chequear = true;
		}
		
		$('#ruta_trabajo input[type="checkbox"]').attr('checked', chequear).each(function()
		{
			var id_chk = $(this).attr('id');
			id_chk = id_chk.split('_');
			poner_color_fila('chk_'+id_chk[1],'fila_'+id_chk[1]);
		});
	});
	
	$('#ruta_trabajo').on('click', '.flicon', function()
	{
		if($('#chk_'+$(this).attr('ruta')).prop('checked'))
		{
			$(this).parent().parent().removeClass('flujo_rt_seleccionado');
		}
		else
		{
			$(this).parent().parent().addClass('flujo_rt_seleccionado');
		}
		
		$('#chk_'+$(this).attr('ruta')).click();
	});


<?
if(isset($Ruta_Aplicada))
{
?>
	$('#asigna_ruta').val(<?=$Ruta_Aplicada?>).change();
<?
}
?>

</script>
