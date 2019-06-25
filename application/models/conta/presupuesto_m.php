<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuesto_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function asignados($Anho = '0000')
	{
		
		$Meses = array(
			'01', '02', '03', '04', '05', '06',
			'07', '08', '09', '10', '11', '12'
		);
		
		$Consulta = '
			select id_mc_linea, mes_01, mes_02, mes_03, mes_04, mes_05, mes_06, mes_07,
			mes_08, mes_09, mes_10, mes_11, mes_12
			from mc_linea_presupuesto
			where Anho = "'.$Anho.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Presupuesto = array();
		foreach($Resultado->result_array() as $Fila)
		{
			
			foreach($Meses as $Mes)
			{
				$Presupuesto[$Fila['id_mc_linea']][$Mes] = $Fila['mes_'.$Mes];
			}
			
		}
		
		
		
		$Sumar = array(19, 20, 23);
		
		for($i = 1;$i <= 12; $i++)
		{
			$Mes = $i;
			if(10 > $Mes)
			{
				$Mes = '0'.$Mes;
			}
			$Presupuesto[18][$Mes] = 0;
		}
		foreach($Sumar as $Codigo)
		{
			if(isset($Presupuesto[$Codigo]))
			{
				foreach($Presupuesto[$Codigo] as $Mes => $Total)
				{
					$Presupuesto[18][$Mes] += $Total;
				}
			}
		}
		
		
		if(isset($Presupuesto[21]))
		{
			foreach($Presupuesto[21] as $Mes => $Total)
			{
				$Presupuesto[18][$Mes] -= $Total;
			}
		}
		//print_r($Presupuesto); exit();
		
		
		return $Presupuesto;
		
	}
	
	
	function modificar($Id_MC_Linea, $Anho, $Presupuesto)
	{
		$Consulta = '
			select id_linea_presupuesto
			from mc_linea_presupuesto
			where id_mc_linea = "'.$Id_MC_Linea.'" and anho = "'.$Anho.'"
		';
		$Resultado = $this->db->query($Consulta);
		
		
		if(0 == $Resultado->num_rows())
		{
			
			$Consulta = '
				insert into mc_linea_presupuesto value(
					NULL,
					"'.$Id_MC_Linea.'",
					"'.$Anho.'",
					"'.$Presupuesto['mes_01'].'",
					"'.$Presupuesto['mes_02'].'",
					"'.$Presupuesto['mes_03'].'",
					"'.$Presupuesto['mes_04'].'",
					"'.$Presupuesto['mes_05'].'",
					"'.$Presupuesto['mes_06'].'",
					"'.$Presupuesto['mes_07'].'",
					"'.$Presupuesto['mes_08'].'",
					"'.$Presupuesto['mes_09'].'",
					"'.$Presupuesto['mes_10'].'",
					"'.$Presupuesto['mes_11'].'",
					"'.$Presupuesto['mes_12'].'"
				)
			';
			
			//Ejecuto la consulta
			$this->db->query($Consulta);
			
		}
		else
		{
			
			$Consulta = '
				update mc_linea_presupuesto set
				mes_01 = "'.$Presupuesto['mes_01'].'",
				mes_02 = "'.$Presupuesto['mes_02'].'",
				mes_03 = "'.$Presupuesto['mes_03'].'",
				mes_04 = "'.$Presupuesto['mes_04'].'",
				mes_05 = "'.$Presupuesto['mes_05'].'",
				mes_06 = "'.$Presupuesto['mes_06'].'",
				mes_07 = "'.$Presupuesto['mes_07'].'",
				mes_08 = "'.$Presupuesto['mes_08'].'",
				mes_09 = "'.$Presupuesto['mes_09'].'",
				mes_10 = "'.$Presupuesto['mes_10'].'",
				mes_11 = "'.$Presupuesto['mes_11'].'",
				mes_12 = "'.$Presupuesto['mes_12'].'"
				where id_mc_linea = "'.$Id_MC_Linea.'" and anho = "'.$Anho.'"
			';
			
			//Ejecuto la consulta
			$this->db->query($Consulta);
			
		}
		
	}
	
}

/* Fin del archivo */