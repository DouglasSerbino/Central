<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activar_desactivar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Activa el menu indicado por el usuario.
	 *@param int $Id_Menu.
	 *@return "ok", "error".
	*/
	function activar($Id_Menu)
	{
		
		//Consulta para desactivar
		$Consulta = '
			update menu set
			activo = "s"
			where id_menu = "'.$Id_Menu.'"
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Notificacion que todo salio bien
		return 'ok';
		
		
	}
	
	/**
	 *Desactiva el menu indicado por el usuario.
	 *@param int $Id_Menu.
	 *@return "ok", "error".
	*/
	function desactivar($Id_Menu)
	{
		
		//Consulta para desactivar
		$Consulta = '
			update menu set
			activo = "n"
			where id_menu = "'.$Id_Menu.'"
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Notificacion que todo salio bien
		return 'ok';
		
		
	}
	
}

/* Fin del archivo */