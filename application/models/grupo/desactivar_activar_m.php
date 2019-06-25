<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desactivar_Activar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Desactivar los grupos del sistema.
	 *@param int Id del grupo que queremos desactivar.
	 *@return string: 'ok' si el grupo se desactiva exitosamente
	 *@return string: 'error' si no se puede desactivar el grupo.
	*/
	function desactivar_grupos($Id_grupo)
	{
		
		//Creamos la consulta para desactivarlos grupos.
		$Consulta = 'UPDATE grupos SET activo="n" where id_grupo = "'.$Id_grupo.'"';
		
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
	
	/**
	 *Activar los grupos del sistema.
	 *@param int Id del grupo que queremos activar.
	 *@return string: 'ok' si el grupo se activa exitosamente
	 *@return string: 'error' si no se puede activar el grupo.
	*/
	function activar_grupos($Id_grupo)
	{
		
		//Creamos la consulta para desactivarlos grupos.
		$Consulta = 'UPDATE grupos SET activo="s" where id_grupo = "'.$Id_grupo.'"';
		
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