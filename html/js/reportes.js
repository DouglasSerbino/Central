function mostrar(tipo)
{
		/*
		$(":text").each(function(){	
			$($(this)).val('');
		});
		$('#menu_principal').css('selected', '');
		*/
	if(tipo=='mensual')
	{
		$("#mensual").css('display','block');
		$("#anual").css('display','none');
	}
	else
	{
		$("#mensual").css('display','none');
		$("#anual").css('display','block');
	}
	
}