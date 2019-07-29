
//Funcion que sirve para realizar las busquedas
//de los materiales por medio de los diferentes filtros.
function inventario_filtro()
{
	codigo = $("#codigo").val();
	proveedor = $("#proveedor").val();
	ver_cantidad = $("#ver_cantidad").val();	
	equipo = $("#equipo").val();
	window.location = "/inventario/existencias/index/" + codigo + "/" + proveedor + "/" + ver_cantidad + "/" + equipo;
}

function inventario_filtro2()
{
	codigo = $("#codigo").val();
	proveedor = $("#proveedor").val();
	ver_cantidad = $("#ver_cantidad").val();	
	equipo = $("#equipo").val();
	window.location = "/reportes/reporte_consumo/index/" + codigo + "/" + proveedor + "/" + ver_cantidad + "/" + equipo;
}

//Funcion para mostrar la rotacion de los materiales.
//Cambiar de materiales.
function ir_pag()
{
	anho = $("#anho").val();
	id_material = $("#id_material").val();
	window.location="/inventario/inventario_lot/index/"+id_material+"/0/0/"+anho;
}


//Validamos que la informacion del nuevo lote este completa.

function validar_lot()
{
	if($('#pedido').val() == '')
	{
		alert('Debe digitar el Numero de Pedido para este Lotes');
		$('#pedido').focus();
		return false;
	}
	if($('#cantidad').val() == '')
	{
		alert('Debe digitar una Cantidad de Unidades para este Lote');
		$('#cantidad').focus();
		return false;
	}
	if($('#date1').val() == '')
	{
		alert('Debe seleccionar la fecha de Ingreso del Lote de Materiales');
		$('#date1').focus();
		return false;
	}
	if(isNaN($('#cantidad').val()))
	{
		alert('Debe digitar solo valores Numericos');
		$('#cantidad').focus();
		return false;
	}
	return true;
}

function colocar(id_material)
{
	if(confirm("Desea realizar una solicitud pedido."))
	{
		window.location = "/herramientas_sis/sol_pedido/index/"+id_material;
	}
}