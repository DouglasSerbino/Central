<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Ingresa el item de menu que el usuario ingresa.
	 *@param string $Etiqueta.
	 *@param string $Enlace.
	 *@param string $Id_Menu_Padre.
	 *@return string 'ok'.
	*/
	function menu($Etiqueta, $Enlace, $Id_Menu_Padre)
	{
		
		//Consultamos la base de datos para que nos ofresca los grupos.
		$Consulta = '
			insert into menu values(
				NULL,
				"'.$Etiqueta.'",
				"'.$Enlace.'",
				"'.$Id_Menu_Padre.'",
				"s"
			)
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Informacion que todo fue bien realizado
		return 'ok';
		
	}
	
}

/* Fin del archivo */