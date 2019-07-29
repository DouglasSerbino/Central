<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acumulado_ventas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function ventas($Anho)
	{
		
		$Ventas = array();
		$Consulta = '
			select sum(venta) as venta, mes, anho
			from reporte_anual_veAAAAntas
			where anho = "'.$Anho.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by mes+0
		';
		
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Ventas[$Fila['mes']]['venta'] = number_format($Fila['venta'], 0, '.', '');
			}
		}
		else
		{
			for($a=1; $a<=12; $a++)
			{
				$Ventas[$a]['venta'] = 0;
			}
		}
		
		
		
		$Consulta = '
			select sum( proyeccion ) as suma, mes+0 as mes, anho
			from venta_proyeccion proy, cliente cli
			where anho = "'.$Anho.'"
			and cli.id_cliente = proy.id_cliente
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by mes+0
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Ventas[$Fila['mes']]['proyeccion'] = number_format($Fila['suma'], 0, '.', '');
			}
		}
		else
		{
			for($a=1; $a<=12; $a++)
			{
				$Ventas[$a]['proyeccion'] = 0;
			}
		}
		
		return $Ventas;
	}	
}

/* Fin del archivo */