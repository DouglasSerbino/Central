function ir_pagven5(){
	mes = $("#mes").val();
	anho = $("#anho").val();
	window.location = "/reportes/venta_proyectada/index/"+mes+"/"+anho;
}

function ir_pagven_acumulado(){
	mes = $("#mes").val();
	anho = $("#anho").val();
	window.location = "/reportes/acumulado/index/"+mes+"/"+anho;
}

function permanente(){
	document.getElementById("movible").style.marginTop = window.pageYOffset + "px";
}

function ir_pagven1(pagina){
	mes = $("#mes").val();
	anho = $("#anho").val();
	mes2 = $("#mes2").val();
	anho2 = $("#anho2").val();
	multiple = $('#rango_fecha').attr('checked');
	window.location = pagina+".php?anho="+anho+"&mes="+mes+"&anho2="+anho2+"&mes2="+mes2+'&multiple='+multiple;
}