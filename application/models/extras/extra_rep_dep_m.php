<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_rep_dep_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos el codigo y departamento seleccionado.
	 *@return array.
	*/
	function mostrar_dpto($id_dpto)
	{
			
			$Consulta = '
							select codigo, departamento
							from departamentos where id_dpto = "'.$id_dpto.'"
			';
		
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
	
	/**
	 *Busca en la base de datos las proyecciones para el dpto seleccionado
	 *En su respectivo mes y anho.
	 *@return array.
	*/
	function mostrar_proyeccion($id_dpto, $mes, $anho)
	{
			
			$Consulta = '
							select proyeccion
							from extra_proy
							where id_dpto = "'.$id_dpto.'"
							and mes = "'.$mes.'" and anho = "'.$anho.'"
			';
		
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
	
	
	/**
	 *Busca en la base de datos los usuarios correspondientes
	 *al departamento seleccionado.
	 *@return array.
	*/
	function mostrar_usuarios($id_dpto, $mes, $anho)
	{
			$Informacion = array();
			$Consulta = '
								select id_usuario, usuario
								from usuario
								where id_dpto = "'.$id_dpto.'"
								and id_grupo = "'.$this->session->userdata('id_grupo').'"
								and activo = "s" order by usuario asc
			';
		
		$Resultado = $this->db->query($Consulta);
				
		
		if(0 < $Resultado->num_rows())
		{
			$Resul = $Resultado->result_array();
			foreach($Resul as $Datos)
			{
				$Informacion[$Datos['id_usuario']]['id_usuario'] = $Datos['id_usuario'];
				$Informacion[$Datos['id_usuario']]['usuario'] = $Datos['usuario'];
				
				$Consulta = 'select sum(total_h) as total_h, sum(total_m) as total_m
										from extra, usuario usu
										where usu.id_usuario = "'.$Datos["id_usuario"].'"
										and extra.id_usuario = "'.$Datos["id_usuario"].'"
										and fecha >= "'.$anho.'-'.$mes.'-01"
										and fecha <= "'.$anho.'-'.$mes.'-31"
										and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
										';
				//echo $Consulta.'<br><br>';
				$Resultado = $this->db->query($Consulta);
				
				$Result_completo = $Resultado->result_array();
				foreach($Result_completo as $Datos_completos)
				{
					$Informacion[$Datos['id_usuario']]['total_h'] = $Datos_completos["total_h"];
					$Informacion[$Datos["id_usuario"]]["total_m"] = $Datos_completos["total_m"];
				}
			}
			//print_r($Informacion);
			return $Informacion;
		}
		else
		{
			return array();
		}
	}
}

/* Fin del archivo */