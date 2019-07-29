<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_grupo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca los usuarios de este grupo que hacen referencia a un grupo externo.
	 *@return array.
	*/
	function listado()
	{
		$Consulta = '
			select grup.id_usuario, grup.id_grupo
			from usuario_grupo grup, usuario usu
			where grup.id_usuario = usu.id_usuario
			and usu.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		//echo $this->session->userdata('id_grupo');
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Listado_Usuario = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Listado_Usuario[$Fila['id_usuario']] = $Fila['id_grupo'];
		}
		
		return $Listado_Usuario;
		
	}
	
}

/* Fin del archivo */