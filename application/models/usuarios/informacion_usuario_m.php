<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informacion_usuario_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca la informacion del usuario indicado.
	 *Valida que el usuario no pertenezca a otro grupo.
	 *@param string $Id_Usuario.
	 *@return array: Contiene la informacion del usuario.
	*/
	function datos($Id_Usuario, $Todo = false)
	{
		
		$Condicion = '';
		if(!$Todo)
		{
			$Condicion = '
				and codigo != "Ventas" and codigo != "Gerencia"
				and codigo != "Plani" and codigo != "Sistemas" 
			';
		}
		
		$Consulta = '
			select *
			from usuario
			where id_grupo = "'.$this->session->userdata('id_grupo').'"
			and id_usuario = "'.$Id_Usuario.'"
			order by usuario asc
		';
		
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los usuarios encontrados
		return $Resultado->result_array();
		
	}
}

/* Fin del archivo */