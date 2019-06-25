//===================================
function handleHttpResponse2()
{
	if (http.readyState == 4)
	{
		if (http.status == 200)
		{
			if(http.responseText.indexOf('invalid') == -1)
			{
				//====================
				if(requisar != "--")
				{
					var ind=0;
					var concep= new Array();
					var concep2= new Array();
					if(null != http.responseXML)
					{
						if(http.responseXML.getElementsByTagName('id').length > 0)
						{
							var total = http.responseXML.getElementsByTagName('id').length; //total de productos
						}
						else
						{
							var total = 0;
						}
						var i;
						for(i=0;i<total;i++)
						{
							concep[ind]= "" + http.responseXML.getElementsByTagName('id')[i].firstChild.data;
							concep2[ind]= "" + http.responseXML.getElementsByTagName('codigo')[i].firstChild.data;
							ind=ind+1;
						}
						
						if(requisar == "si")
						{
							document.miform.id_material.length= ind+1;
							document.miform.id_material.options[0] = new Option("--");
							document.miform.id_material.options[0].value = "--";
							o=1;
							for(i=0;i<ind;i++)
							{
								document.miform.id_material.options[o] = new Option(concep2[i]);
								document.miform.id_material.options[o].value = concep[i];
								document.miform.id_material.selectedIndex=1;
								o++;
							}
						}
						if(requisar == "no")
						{
							document.existe.id_material2.length= ind+1;
							document.existe.id_material2.options[0] = new Option("--");
							document.existe.id_material2.options[0].value = "--";
							o=1;
							for(i=0;i<ind;i++)
							{
								document.existe.id_material2.options[o] = new Option(concep2[i]);
								document.existe.id_material2.options[o].value = concep[i];
								document.existe.id_material2.selectedIndex=1;
								o++;
							}
						}
					}
				}
				else{
					document.existe.cantidad2.value = http.responseText;
				}
				//=================
				enProceso = false;
			}
    }
  }
}

function cambia_sel() {
	requisar = "si";
	if (!enProceso && http) {
		valor = document.miform.busque.value;
		if(valor.length>1){
			var url = "/inventario/inventario_req/buscar_mat/"+ valor;
			http.open("GET", url, true);
			http.onreadystatechange = handleHttpResponse2;
			enProceso = true;
			http.send(null);
		}
		else
		{
			document.miform.id_material.length=1;
			document.miform.id_material.options[0] = new Option("--");
			document.miform.id_material.options[0].value = "--";
		}
	}

}

function cambia_sel2()
{
	requisar = "no";
	if (!enProceso && http)
	{
		valor = document.existe.nbus.value;
		if(valor.length>1){
			var url = "/inventario/inventario_req/buscar_mat/"+valor;
			http.open("GET", url, true);
			http.onreadystatechange = handleHttpResponse2;
			enProceso = true;
			http.send(null);
		}
		else
		{
			document.existe.id_material2.length=1;
			document.existe.id_material2.options[0] = new Option("--");
			document.existe.id_material2.options[0].value = "--";
		}
	}

}

function existencia()
{
	requisar = "--";
	if (!enProceso && http)
	{
		codigo_mat = document.existe.id_material2.value;
		var url = "/inventario/inventario_req/buscar_exist/"+ codigo_mat;
		http.open("GET", url, true);
		http.onreadystatechange = handleHttpResponse2;
		enProceso = true;
		http.send(null);
	}
}

function getHTTPObject2()
{
    var xmlhttp;
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
		{
       try
			 {
          xmlhttp = new XMLHttpRequest();
       }
			 catch (e)
			 {
					xmlhttp = false;
			 }
    }
    return xmlhttp;
}

var requisar = "si";

var enProceso = false; // lo usamos para ver si hay un proceso activo
var http = getHTTPObject2(); // Creamos el objeto XMLHttpRequest