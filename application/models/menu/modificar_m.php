<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Modifica el menu seleccionado por el cliente.
	 *@param int $Id_Menu.
	 *@param string $Etiqueta.
	 *@param string $Enlace.
	 *@param string $Id_Menu_Padre.
	 *@return string 'ok'.
	*/
	function menu($Id_Menu, $Etiqueta, $Enlace, $Id_Menu_Padre)
	{
		
		//Consulta para realizar la modificacion
		$Consulta = '
			update menu set
			etiqueta = "'.$Etiqueta.'",
			enlace = "'.$Enlace.'",
			id_menu_padre = "'.$Id_Menu_Padre.'"
			where id_menu = "'.$Id_Menu.'"
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Informacion que todo fue bien realizado
		return 'ok';
		
	}
	
	function Crear_acceso($Id_menu, $Ruta)
	{
		$Ruta = explode(',', $Ruta);
		if(0 < count($Ruta))
		{
			$Consulta = 'delete from menu_acceso where id_menu = "'.$Id_menu.'"';
			$this->db->query($Consulta);
			foreach($Ruta as $Dpto)
			{
				$Id_Dpto = explode('_', $Dpto);	
				$Consulta = 'insert into menu_acceso values(null, "'.$Id_Dpto[1].'", "'.$Id_menu.'")';
				$this->db->query($Consulta);
			}
			return 'ok';
		}
		else
		{
			return 'error';
		}
		
	}
	
}

/* Fin del archivo */