<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Agregar departamentos al sistema.
	 *Agregaremos los departamentos que sean requeridos en el sistema.
	 *@param string $Codigo: Codigo que identificara a cada departamento.
	 *@param string $Nombre_departamento mostrara el nombre del departamento.
	 *@param string $Tipo_inv: Tipo de inventario que tiene el departamento.
	 *@param string $Cant_mensual: Cantidad mensual que se le asigna al departamento.
	 *@param string $Iniciales: Iniciales que representan al departamento..
	 *@return string: 'ok' si el departamento se ingresa exitosamente
	 *@return string: 'error' si no se puede guardar el departamento.
	*/
	function guardar_datos($Codigo,$Nombre_dpto,$Tipo_inv,$cant_mensual,$Iniciales)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = '
			INSERT INTO departamentos values(
				null,
				"'.$Codigo.'",
				"'.$Nombre_dpto.'",
				"'.$Tipo_inv.'",
				"'.$cant_mensual.'",
				"'.$Iniciales.'",
				"s",
				"n",
				"s"
			)
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			//Regresar mensaje de ok
			return 'ok';
		}
		else
		{//Si no se ingresa
			//Enviamos mensaje de error
			return 'error';
		}
	}
}

/* Fin del archivo */