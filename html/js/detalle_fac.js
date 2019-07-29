function permanente(){
	$("#div_ver_cliente").css({'top':(170 + window.pageYOffset) + 'px'});
}

function ver_facturas(){
	ver_cliente = $('#ver_cliente').val();
	window.location = '/facturacion/detalle_facturacion/index/'+ver_cliente;
}

function ver_hojas(){
	ver_cliente = $('#ver_cliente').val();
	window.location = '/pedidos/hoja_tiempo_consumo/index/'+ver_cliente;
}

function confirmar_fac(pedido, id_venta){	
	$('#codigo_sap_fac').val(pedido);
	$('#id_venta').val(id_venta);
	$('#factura').val('');
	$('#formfac_div').css({'visibility':'visible', 'display':''});
	$('#formfac_div').css({'top':(170 + window.pageYOffset) + 'px'});
}

function ocultar_fac()
{
	$('#formfac_div').css({'visibility':'hidden'});
	$('#formfac_div').css({'display':'none'});
}

function guardar_fac(id_cliente)
{
	factura = $('#factura').val();
	
	if(factura == '')
	{
		alert("Debe digitar un numero de Factura/IT");
	}
	else
	{
		$('#miformfac').submit();
	}
}