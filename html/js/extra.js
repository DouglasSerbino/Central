function porcentaje_ocu()
{
	$("#porcentaje_extra").hide();
}

function ir_mes(){
	mes = $("#mes").val();
	anho = $("#anho").val();
	window.location = "/extras/extras/index/"+anho+"/"+mes;
}

function abrir_ventana(dia,mes,anho)
{
	id_usuario = $("#id_usuario").val();
	direccion = "/extras/extra_agr_ped/index/"+id_usuario+"/"+dia+"/"+mes+"/"+anho+"/-/-";
	window.open(direccion, 'Agregar Extra', 'dependent=yes, directories=no, height=600, hotkeys=no, location=no, menubar=no, personalbar=no, resizable=no, scrollbars=yes, status=no, toolbar=no, width=750');
}

function abrir_ventana2(id_extra,id_usuario,dia, mes, anho){
	direccion = "/extras/extra_agr_ped/index/"+id_usuario+"/"+dia+"/"+mes+"/"+anho+"/"+id_extra+"/-";
	window.open(direccion, 'Agregar Extras', 'dependent=yes, directories=no, height=600, hotkeys=no, location=no, menubar=no, personalbar=no, resizable=no, scrollbars=yes, status=no, toolbar=no, width=750');
}

function abrir_ventana3(id_extra,id_usuario){
	direccion = "/extras/extra_mod_ped/index/"+id_usuario+"/"+id_extra;
	window.open(direccion, 'Agregar Extra', 'dependent=yes, directories=no, height=600, hotkeys=no, location=no, menubar=no, personalbar=no, resizable=no, scrollbars=yes, status=no, toolbar=no, width=750');
}

function cambia_color(fila,cheque){
	if(document.getElementById(cheque).checked == true)
		document.getElementById(fila).className="selec";
	else
		document.getElementById(fila).className="desel";
}

function ver_observa_extra(identificador)
{
	$("#observa").val($("#comenta_"+identificador).val());
	$("#observacion").css({'display':'', 'visibility':'visible', 'top':(window.pageYOffset + 10) + 'px'});
	$("#caja_observa").val(identificador);
	
}

function ocu_observa_extra()
{
	$("#observacion").css({'display':'none', 'visibility':'hidden'});
}

function gua_observa_extra()
{
	$("#observacion").css({'display':'none', 'visibility':'hidden'});	
	$("#comenta_"+$("#caja_observa").val()).val($("#observa").val());
}

function envia_form()
{
	if($("#inicio").val() == "")
	{
		alert("Debe Especificar una Hora de Inicio");
		$("#inicio").focus();
		return false;
	}
	if($("#fin").val() == "")
	{
		alert("Debe Especificar una Hora de Finalizacion");
		$("#fin").focus();
		return false;
	}
	var inicio = $("#inicio").val().length;
	var fin = $("#fin").val().length;
	
	if(inicio < 5)
	{
		alert('Ingrese un formato de hora correcto \n    Ejemplo: 08:30');
		$('#inicio').focus();
		return false;
	}
	
	if(fin < 5 & inicio == 5)
	{
		alert('Ingrese un formato de hora correcto \n      Ejemplo: 17:00');
		$('#fin').focus();
		return false;
	}
	
	if(inicio == 5 & fin == 5)
	{
		$('#miform').submit();
	}
}
function eliminar_e(id_extra, dia, mes, anho, nombre){
	if(confirm("Desea Eliminar el registro de "+nombre+"?")){
		window.location = "/extras/extra_agr/eliminar_extras/"+id_extra+"/"+dia+"/"+mes+"/"+anho;
	}
}

function agregar_flete_extra(id_extra,dia,mes,anho)
{
	location = "/extras/extra_agr/agregar_flete/"+id_extra+"/"+dia+"/"+mes+"/"+anho;
}


function habi_desh(que_hago){
	
	if(que_hago == "ha")
	{
		$('#pre_mes').removeAttr('disabled');
		$('#pre_anho').removeAttr('disabled');;
	}
	if(que_hago == "de")
	{
		$('#pre_mes').attr('disabled','disabled');
		$('#pre_anho').attr('disabled','disabled');
	}
	
}

function validar_extra()
{	
	var valor = '';//Valor que tomara por cada elemento
	var campo = '';//Si un campo no cumple su condicion, se guarda cual es
	var continuar = true;//Puedo continuar?
	
	//Reviso todos los campos que son requeridos
	$('input:text').each(function()
	{
		//Asigno el valor de este campo y le elimino los espacios al inicio y final
		valor = $.trim($(this).val());
		//Asigno el valor sin espacios al campo
		$(this).val(valor);
		
		//Si este campo requerido esta vacio
		if('' == valor)
		{
			//Guardo el nombre del campo
			campo = $(this).attr('name');
			//Cambio el valor de continuar para que se detenga la ejecucion del script
			continuar = false;
			//Detengo el bucle
			return false;
		}
	});
	
	//Si no se puede continuar
	if(!continuar)
	{
		//Hago saber al usuario
		alert('Favor completar todos los campos.');
		//Indico cual es el campo
		$('[name='+campo+']').focus();
		//Detengo la ejecucion del script
		return false;
	}
}

/*
function oculta_cargando(){
	$("#cargando").hide();
}
*/
function ver_calendario_e(id_user){
	$("#calendario_"+id_user).show();
}

function ocu_calendario_e(id_user){
	$("#calendario_"+id_user).hide();
}

function mostrar_trabajos(dia,mes,anho, id_usuario, id_extra)
{
	if($('#trabajos_todos').attr('checked'))
	{
		if(id_extra != '-')
		{
			location = "/extras/extra_agr_ped/index/"+id_usuario+"/"+dia+"/"+mes+"/"+anho+"/"+id_extra+"/ok";
		}
	}
	else
	{
		location = "/extras/extra_agr_ped/index/"+id_usuario+"/"+dia+"/"+mes+"/"+anho+"/"+id_extra+"/-";
	}
	return false;
}