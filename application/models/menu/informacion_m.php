<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informacion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos todos el menu de usuario solicitado y regresar
	 *un array con la informacion.
	 *@param int $Id_Menu: Identificador del menu a buscar.
	 *@return array: Contiene la informacion del menu.
	*/
	function menu($Id_Menu)
	{
		
		//Consultamos la base de datos para que nos ofresca la informacion.
		$Consulta = '
			select *
			from menu
			where id_menu = "'.$Id_Menu.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
	}
	
	
	function Extraer_accesos($Id_Menu)
	{
		$Consulta = 'select menu.id_dpto, dpto.departamento from menu_acceso menu, departamentos dpto
								where menu.id_menu = "'.$Id_Menu.'"
								and menu.id_dpto = dpto.id_dpto';
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
}

/* Fin del archivo */