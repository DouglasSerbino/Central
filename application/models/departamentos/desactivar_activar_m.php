<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desactivar_activar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Desactivar o Activar los departamentos del sistema.
	 *@param int Id del departamento que queremos desactivar.
	 *@param String Opcion permitira activar o desactivar un departamento.
	 *@return string: 'ok' si el departamento se desactiva exitosamente
	 *@return string: 'error' si no se puede desactivar el departamento.
	*/
	function Operacion($Id_dpto, $Opcion)
	{
		
		//Creamos la consulta para desactivarlos departamentos.
		$Consulta = 'UPDATE departamentos SET activo = "'.$Opcion.'" where id_dpto = "'.$Id_dpto.'"';
		
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