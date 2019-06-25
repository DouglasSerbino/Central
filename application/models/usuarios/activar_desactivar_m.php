<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activar_desactivar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Activar o Desactivar el usuario.
	 *@param int $Id_Usuario.
	 *@param String $Tipo accion que permitira activar o desactivar un usuario.
	 *@return "ok", "error".
	*/
	function accion($Tipo, $Id_Usuario)
	{
		if('s' == $Tipo)
		{
			$Consulta = '
				update usuario set
				activo = "s"
				where id_usuario = "'.$Id_Usuario.'"
			';
		}
		else
		{
			$Consulta = '
				update usuario set
				activo = "n"
				where id_usuario = "'.$Id_Usuario.'"
			';
		}
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Notificacion que todo salio bien
		return 'ok';
	}	
}

/* Fin del archivo */