function handleHttpResponse()
{
	if(http.readyState == 4)
	{
		if(http.status == 200)
		{
			if(http.responseText.indexOf('invalid') == -1){
				
				if(http.responseXML.getElementsByTagName('id_material').length > 0)
				{
					if(ver_rep == "ver")
					{
						document.getElementById("nombre_material"+caja).value = "" + http.responseXML.getElementsByTagName('nombre_material')[0].firstChild.data;
						document.getElementById("id_material"+caja).value = "" + http.responseXML.getElementsByTagName('id_material')[0].firstChild.data;
					}
				}
			}
		}
	}
	
	enProceso = false;
	
}

var caja = "";
function ver_material(cajaver)
{
	caja = cajaver;
	ver_rep = "ver";
	if(!enProceso && http)
	{
		document.getElementById("id_material"+caja).value = "";
		document.getElementById("nombre_material"+caja).value = "";
		valor = document.getElementById("codigo_material"+caja).value;
		if(valor.length > 0)
		{
			document.getElementById("id_material"+caja).value = "--";
			var url = "/inventario/inventario_req/mostrar_material/"+ valor;
			http.open("GET", url, true);
			http.onreadystatechange = handleHttpResponse;
			enProceso = true;
			http.send(null);
		}
	}
	//document.getElementById("cantidad_material"+caja).focus();
}