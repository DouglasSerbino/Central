function arte_mos(div1,div2,objeto,sino)
{
	
	$('#'+objeto).val(sino);
	
	if(div2 == "fin")
	{
		$('#miform').submit();
	}
	else
	{
		$('#'+div1).css({'visibility':'hidden', 'display':'none'});
		$('#'+div2).css({'visibility':'visible', 'display':''});
	}
}

function color_mos(div1,div2){
	if(div2 == "fin")
	{
		$('#miform').submit();
	}
	else
	{
		$('#'+div1).css({'visibility':'hidden', 'display':'none'});
		$('#'+div2).css({'visibility':'visible', 'display':''});
	}
}