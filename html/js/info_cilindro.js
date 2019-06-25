function validar()
{
	if($('#pulgas').val() != '')
	{
		if($('#polimero').val() != '')
		{
			if($('#stickyback').val() == 0)
			{
				alert('Seleccione un Stickyback!');
				$('#stickyback').focus();
				return false;
			}
			else
			{
				$('#cilindro').submit();
			}
		}
		else
		{
			alert('Seleccione un Fotopolimero!');
			$('#polimero').focus();
			return false;
		}
	}
	else
	{
		alert('Debe ingresar el Desarrollo en mm.')
		$('#pulgas').focus();
		return false;
	}
}

function ver_tolerancia()
{
	if($('#mostrar_div').attr('checked'))
	{
		$('#mostrar').show();
	}
	else
	{
		$('#mostrar').hide();
	}
}

function ver_tolerancia2()
{
	if($('#mostrar_div2').attr('checked'))
	{
		$('#mostrar2').show();
	}
	else
	{
		$('#mostrar2').hide();
	}
}

function ver_sticky()
{
	if($('#ver_stickyback').attr('checked'))
	{
		$('#stickyback').removeAttr('disabled');
	}
	else
	{
		$('#stickyback').attr('disabled','disabled');
	}
}

function ver_tabla(numero)
{
	if($('#mostrar_informacion'+numero).attr('checked'))
	{
		$('#mostrar_tabla_conversion'+numero).css({'display':'block'});
	}
	else
	{
		$('#mostrar_tabla_conversion'+numero).css({'display':'none'});
	}
}

function ocultar_tabla()
{
	$('#mostrar_tabla_conversion1').css({'display':'none'});
	$('#mostrar_tabla_conversion2').css({'display':'none'});
	$('#mostrar_tabla_conversion3').css({'display':'none'});
	$('#mostrar_informacion1').removeAttr("checked");
	$('#mostrar_informacion2').removeAttr("checked");
	$('#mostrar_informacion3').removeAttr("checked");
}

function validar_info()
{
	var caja = $('#pulgas_desa').val();
	if($('#pulgas_desa').val() == 0)
	{
		alert('Debe ingresar el Desarrollo en mm.')
		$('#pulgas_desa').focus();
		return false;
	}
}