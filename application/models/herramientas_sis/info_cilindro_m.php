<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_cilindro_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function buscar_info_cilindro($mas, $menos, $pulgas,$Mandar_polimero,$Mandar_sticky)
	{
		//echo ($pulgas + $mas) * 1 / 3.1416 - ($Mandar_polimero * 2) - ($Mandar_sticky * 2);
		$where = 'where';
		$or = 'or';
		$SQL = '(';
		for($a=1;$a<=15;$a++)
		{
			if($a == 15)
			{
				$or = '';
			}
			
			$diamas = (($pulgas + $mas) * $a / 3.1416) - ($Mandar_polimero * 2) - ($Mandar_sticky * 2);
			$diames = ((($pulgas - $menos) * $a / 3.1416) - ($Mandar_polimero * 2) - ($Mandar_sticky * 2));
			
			$SQL .= ' (mili >= '.$diames.'
							and mili <= '.$diamas.') '.$or.'';
		}
		
		$SQL .= ')';
		
		if($Mandar_polimero / 25.4 == 0.112)
		{
			$SQL .= ' and tipo = "sirio" ';
		}
		else
		{
			$SQL .= ' and tipo != "sirio" ';
		}
		
		$Consulta = "select * from info_cilindronew where $SQL order by mili";
		
		//echo $Consulta.'<br />';
		
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
	
	
	function info_cilindro_desnudo()
	{
		$Consulta = 'select mili, num_cilindros from info_cilindronew
		where tipo = "mg_magma" or tipo = "Sierra" order by tipo, mili asc';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
		
		
		/*
		 *
		 *
		 $Consulta = 'select * from info_cilindronew order by tipo, mili asc';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$info = array();
			foreach($Resultado->result_array() as $Datos)
			{
				$info[$Datos['tipo']][$Datos['id_info_cil']] = $Datos['mili'];
			}
			return $info;
		}
		else
		{
			return array();
		}
		 *
		 **/
		
		
	}
	
	
}
/* Fin del archivo */