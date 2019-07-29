function guarda_imprime(){
	
	if(confirm("Los datos de estas notas de envio seran guardados en el sistema.\r\nDesea continuar \"OK\"\r\nDesea revisar la informacion \"CANCEL\""))
		document.envios_form.submit();
	
}


//=================================================
function agregar(ide_pedido, posicion){//Funcion que agrega elementos en una tabla especifica
	
	var fila = $('#agrego'+posicion+' .somos_extras').length;
	
	var nuevo_elemento = "<tr><td><span id='tr_ot_"+ide_pedido+"_"+fila+"' class='somos_extras'>";
	nuevo_elemento += "<span class='scantidad'><input type='text' size='5' class='boton_gris scant' name='ot_"+ide_pedido+"_"+fila+"'></span>";
	nuevo_elemento += "<span class='seliminar'><a href='javascript:borrar("+posicion+", "+ide_pedido+", \"tr_ot_"+ide_pedido+"_"+fila+"\");'> * </a></span>";
	nuevo_elemento += "<span class='sdescripcion'><input type='text' size='20' class='boton_gris sdesc' name='de_"+ide_pedido+"_"+fila+"'></span>";
	nuevo_elemento += "</span></td></tr>";
	
	$('#agrego'+posicion).append(nuevo_elemento);
	
}

function borrar(posicion, ide_pedido, fila_a_borrar){//Esta funcion borra una fila especifica
	
	$('#'+fila_a_borrar).remove();
	
	if(0 != posicion){
		
		var actual = 0;
		var consh = '';
		
		$('#agrego'+posicion+' .somos_extras').each(function(){
			
			$(this).attr('id', 'tr_ot_'+ide_pedido+'_'+actual);
			$(this).find('a').attr('href', "javascript:borrar("+posicion+", "+ide_pedido+", \"tr_ot_"+ide_pedido+"_"+actual+"\");");
			$(this).find(':text').each(function(){
				if($(this).attr('class') == 'boton_gris scant'){
					$(this).attr('name', 'ot_'+ide_pedido+'_'+actual);
				}
				if($(this).attr('class') == 'boton_gris sdesc'){
					$(this).attr('name', 'de_'+ide_pedido+'_'+actual);
				}
			});
			actual++;
			
		});
		
	}
	
}