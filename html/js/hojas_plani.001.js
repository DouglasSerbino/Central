

	$('#list_colores [type="checkbox"]').click(function()
	{
		pintar_caja($(this).attr('info'));
		calcular_pulgadas();
	});
	
	//Pintamiento de cajas
	for(i = 1; i <= 10; i++)
	{
		pintar_caja(i);
	}
	
	function enviar_espec()
	{
		if(confirm('La informaci\xf3n ser\xe1 modificada.\r\nDesea continuar?'))
		{
			$('#form_espec_repro').submit();
		}
	}

	//Calcular ancho total
	$('#repet_ancho, #ancho_arte').blur(function()
	{
		ancho = $('#ancho_arte').val();
		ancho_rep = $('#repet_ancho').val();
		if(!isNaN(ancho) && !isNaN(ancho_rep))
		{
			$('#ancho_total').val(ancho * ancho_rep);
		}
		else
		{
			$('#ancho_total').val(0);
		}

		calcular_pulgadas();
	});

	//Calcular alto total
	$('#repet_alto, #alto_arte').blur(function()
	{
		alto = $('#alto_arte').val();
		alto_rep = $('#repet_alto').val();
		if(!isNaN(alto) && !isNaN(alto_rep))
		{
			$('#alto_total').val(alto * alto_rep);
		}
		else
		{
			$('#alto_total').val(0);
		}
		$('#pa').val($('#alto_total').val());

		calcular_distorsion();
		calcular_pulgadas();
	});

	//Esta casilla recibe el exceso por lado
	$('#codb_bwr').blur(function()
	{
		calcular_pulgadas();
	});

	function calcular_pulgadas()
	{
		var Alto_Total = parseInt($('#alto_total').val());
		var Ancho_Total = parseInt($('#ancho_total').val());
		var Exceso = parseInt($('#codb_bwr').val());

		//Si no hay medidas se deja a 0
		if(isNaN(Ancho_Total) || isNaN(Alto_Total))
		{
			$('#codb_magni').val(0);
			return false;
		}

		//Convertimos a pulgadas
		Alto_Total = (Alto_Total / 25.4)
		Ancho_Total = (Ancho_Total / 25.4)


		//Agregamos el exceso si aplica
		if(!isNaN(Exceso))
		{
			Alto_Total = Alto_Total + Exceso;
			Ancho_Total = Ancho_Total + Exceso;
		}

		Total_Pulgadas = Math.ceil(Alto_Total * Ancho_Total);

		//El total de pulgadas se multiplica por los colores seleccionados
		var Colores = 0;
		for(i = 1; i <= 10; i++)
		{
			if($('#solicitado_'+i).prop('checked'))
			{
				Colores++;
			}
		}

		Total_Pulgadas = Total_Pulgadas * Colores;


		$('#codb_magni').val(Total_Pulgadas);

	}
