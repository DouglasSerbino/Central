<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ruta_grupo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Obtiene de forma ordenada la ruta de trabajo para el grupo del planificador
	 *@param string $Id_Grupo.
	 *@return array
	*/
	function generar_ruta($Id_Grupo)
	{
		
		//Solicito los id_dpto de la ruta en el orden creado
		$Consulta = '
			select id_ruta_dpto, rudp.id_dpto, automatico
			from ruta_grupo grup, ruta_dpto rudp, departamentos dpto
			where grup.id_ruta_grupo = rudp.id_ruta_grupo
			and rudp.id_dpto = dpto.id_dpto
			and id_grupo = "'.$Id_Grupo.'"
			order by id_ruta_dpto asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Departamentos = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Departamentos[$Fila['id_ruta_dpto']]['id_dpto'] = $Fila['id_dpto'];
			$Departamentos[$Fila['id_ruta_dpto']]['automatico'] = $Fila['automatico'];
		}
		
		//Regreso el array con los departamentos
		return $Departamentos;
		
	}
}

/* Fin del archivo */