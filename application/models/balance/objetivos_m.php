<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Objetivos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function objetivos()
	{
		
		$Consulta = '
			select *
			from bsc_objetivo
			where activo = "s" and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by id_padre asc, id_bsc_objetivo asc
		';
		$Resultado = $this->db->query($Consulta);
		
		
		$Objetivos = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			if(0 == $Fila['id_padre'])
			{
				$Objetivos[$Fila['id_bsc_objetivo']]['Nom'] = $Fila['objetivo'];
				$Objetivos[$Fila['id_bsc_objetivo']]['Objs'] = array();
			}
			else
			{
				if(!isset($Objetivos[$Fila['id_padre']]))
				{
					if(!isset($Objetivos[0]))
					{
						$Objetivos[0] = array('Nom' => 'Sin Categor&iacute;a');
					}
					$Fila['id_padre'] = 0;
				}
				$Objetivos[$Fila['id_padre']]['Objs'][$Fila['id_bsc_objetivo']]['Nom'] = $Fila['objetivo'];
				$Objetivos[$Fila['id_padre']]['Objs'][$Fila['id_bsc_objetivo']]['Ind'] = $Fila['indicador'];
				$Objetivos[$Fila['id_padre']]['Objs'][$Fila['id_bsc_objetivo']]['Con'] = $Fila['condicion'];
			}
			
		}
		
		return $Objetivos;
		
	}
	
	
	function datos($Tipo_Objetivo, $Anho, $Id_Bsc_Objetivo = 0, $Mes = 0)
	{
		
		$Condicion = '';
		if(0 < $Id_Bsc_Objetivo)
		{
			$Condicion = ' and id_bsc_objetivo = "'.$Id_Bsc_Objetivo.'"';
		}
		
		if(0 != $Mes)
		{
			$Condicion .= ' and mes = "'.$Mes.'"';
		}
		
		$Consulta = '
			select *
			from bsc_datos
			where anho = "'.$Anho.'"'.$Condicion.'
			order by mes asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		$Datos = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			if('todo' == $Tipo_Objetivo)
			{
				$Datos[$Fila['id_bsc_objetivo']][$Fila['mes']]['real'] = $Fila['bsc_real'];
				$Datos[$Fila['id_bsc_objetivo']][$Fila['mes']]['proy'] = $Fila['bsc_proyeccion'];
			}
			else
			{
				$Datos[$Fila['id_bsc_objetivo']][$Fila['mes']] = $Fila['bsc_'.$Tipo_Objetivo];
			}
		}
		
		
		return $Datos;
	}
	
	
		
	function agregar()
	{
		
		$Objetivo = $this->seguridad_m->mysql_seguro($this->input->post('objetivo'));
		$Indicador = $this->seguridad_m->mysql_seguro($this->input->post('indicador'));
		$Pertenece = $this->input->post('pertenece') + 0;
		
		$Condicion = $this->input->post('condicion');
		if('-' != $Condicion)
		{
			$Condicion = '+';
		}
		
		
		if('' == $Objetivo)
		{
			echo 'no';
		}
		else
		{
			
			$Consulta = '
				insert into bsc_objetivo value(
					NULL,
					"'.$Pertenece.'",
					"'.$this->session->userdata('id_grupo').'",
					"'.$Objetivo.'",
					"'.$Condicion.'",
					"'.$Indicador.'",
					"s"
				)
			';
			
			$this->db->query($Consulta);
			
			$Id_Bsc_Objetivo = $this->db->insert_id();
			
			
			if(0 < $Pertenece)
			{
				
				$Consulta = '
					select anho
					from bsc_datos
					order by anho desc
					limit 0, 1
				';
				
				$Resultado = $this->db->query($Consulta);
				
				$Inicio = date('Y');
				if(0 < $Resultado->num_rows())
				{
					$Inicio = $Resultado->row_array();
					$Inicio = $Inicio['anho'];
				}
				
				$Consulta = '
					select anho
					from bsc_datos
					order by anho asc
					limit 0, 1
				';
				
				$Resultado = $this->db->query($Consulta);
				
				$Inicio = date('Y');
				if(0 < $Resultado->num_rows())
				{
					$Inicio = $Resultado->row_array();
					$Inicio = $Inicio['anho'];
				}
				
				for($Anho = 2012; $Anho <= $Inicio; $Anho++)
				{
					$this->inserta_datos($Id_Bsc_Objetivo, $Anho);
				}
				
			}
			
			
			echo $Id_Bsc_Objetivo;
			
		}
		
	}
	
	
	
	function modifica_objetivo($Id_Bsc_Objetivo)
	{
		
		$Objetivo = $this->seguridad_m->mysql_seguro($this->input->post('objetivo'));
		$Indicador = $this->seguridad_m->mysql_seguro($this->input->post('indicador'));
		$Pertenece = $this->input->post('pertenece') + 0;
		
		$Condicion = $this->input->post('condicion');
		if('-' != $Condicion)
		{
			$Condicion = '+';
		}
		
		
		if('' == $Objetivo)
		{
			echo 'no';
		}
		else
		{
			
			$Consulta = '
				update bsc_objetivo set
				id_padre = "'.$Pertenece.'",
				objetivo = "'.$Objetivo.'",
				condicion = "'.$Condicion.'",
				indicador = "'.$Indicador.'"
				where id_bsc_objetivo = "'.$Id_Bsc_Objetivo.'" and id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			$this->db->query($Consulta);
			
			echo 'ok';
			
		}
		
		
	}
	
	
	
	function eliminar($Id_Bsc_Objetivo)
	{
		
		$Consulta = '
			update bsc_objetivo set activo = "n" where id_bsc_objetivo = "'.$Id_Bsc_Objetivo.'" and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$this->db->query($Consulta);
		
		echo 'ok';
		
	}
	
	
	
	function inserta_datos($Id_Bsc_Objetivo, $Anho)
	{
		
		$Consulta = '';
		
		for($Mes = 1; $Mes <= 12; $Mes++)
		{
			
			if('' != $Consulta)
			{
				$Consulta .= ',';
			}
			
			$nMes = $Mes;
			if(10 > $nMes)
			{
				$nMes = '0'.$nMes;
			}
			
			$Consulta .= '
				(
					NULL,
					"'.$Id_Bsc_Objetivo.'",
					"'.$Anho.'",
					"'.$nMes.'",
					"0",
					"0"
				)
			';
			
		}
		
		$Consulta = '
			insert into bsc_datos values
			'.$Consulta.'
		';
		
		$this->db->query($Consulta);
		
	}
	
	
	function actualiza_datos($Tipo_Objetivo, $Anho)
	{
		
		$Datos = json_decode($this->input->post('valores'));
		
		foreach($Datos as $Id_Bsc_Objetivo => $Rango)
		{
			
			foreach($Rango as $Index => $Valor)
			{
				
				$Mes = $Index + 1;
				if(10 > $Mes)
				{
					$Mes = '0'.$Mes;
				}
				
				$Consulta = '
					update bsc_datos
					set bsc_'.$Tipo_Objetivo.' = "'.$Valor.'"
					where id_bsc_objetivo = "'.$Id_Bsc_Objetivo.'" and anho = "'.$Anho.'"
					and mes = "'.$Mes.'"
				';
				
				$this->db->query($Consulta);
				
			}
			
		}
		
		echo 'ok';
		
	}
	
	
}

/* Fin del archivo */