<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consolidado_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function consolidado($Anho = '', $Mes_Inicio, $Mes_Fin)
	{
		
		$Inicio = '01';
		$Fin = '12';
		if('anual' != $Mes_Inicio)
		{
			$Inicio = $Mes_Inicio;
			$Fin = $Mes_Fin;
		}
		
		$Consulta = '
			select sum(monto) as tot_linea, id_mc_linea, date_format(fecha, "%m") as mes
			from mc_linea_movimiento
			where fecha >= "'.$Anho.'-'.$Inicio.'-01" and fecha <= "'.$Anho.'-'.$Fin.'-31"
			and activo = "s"
			group by id_mc_linea, date_format(fecha, "%m")
		';// and id_mc_linea not in(19, 25)
		$Resultado = $this->db->query($Consulta);
		
		$Consolidado = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Consolidado[$Fila['id_mc_linea']][$Fila['mes']] = $Fila['tot_linea'];
		}
		
		
		//Oh! Ironias de la vida.
		//Los valores para el 101 y el 109 deben tomarse a patita :'(
		//Aqui voy....
		
		
		//Venta por mes
		$Consulta = '
			select sum(venta) as tot_linea, date_format(fecha, "%m") as mes
			from pedido_sap
			where fecha >= "'.$Anho.'-'.$Inicio.'-01" and fecha <= "'.$Anho.'-'.$Fin.'-31"
			group by date_format(fecha, "%m")
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			if(!isset($Consolidado[19][$Fila['mes']]))
			{
				$Consolidado[19][$Fila['mes']] = 0;
			}
			$Consolidado[19][$Fila['mes']] += $Fila['tot_linea'];
		}
		
		
		//Materias primas por mes
		$Consulta = '
			select sum(cantidad * valor) as tot_linea, date_format(fecha_entrega, "%m") as mes
			,mate.id_inventario_material
			from pedido ped, pedido_material pema, inventario_material mate
			where ped.id_pedido = pema.id_pedido
			and pema.id_inventario_material = mate.id_inventario_material
			and fecha_entrega >= "'.$Anho.'-'.$Inicio.'-01" and fecha_entrega <= "'.$Anho.'-'.$Fin.'-31"
			group by date_format(fecha_entrega, "%m")
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			if(!isset($Consolidado[25][$Fila['mes']]))
			{
				$Consolidado[25][$Fila['mes']] = 0;
			}
			$Consolidado[25][$Fila['mes']] += $Fila['tot_linea'];
		}
		
		
		$Sumar = array(19, 20, 23);
		
		for($i = 1;$i <= 12; $i++)
		{
			$Mes = $i;
			if(10 > $Mes)
			{
				$Mes = '0'.$Mes;
			}
			$Consolidado[18][$Mes] = 0;
		}
		foreach($Sumar as $Codigo)
		{
			if(isset($Consolidado[$Codigo]))
			{
				foreach($Consolidado[$Codigo] as $Mes => $Total)
				{
					$Consolidado[18][$Mes] += $Total;
				}
			}
		}
		
		
		if(isset($Consolidado[21]))
		{
			foreach($Consolidado[21] as $Mes => $Total)
			{
				$Consolidado[18][$Mes] -= $Total;
			}
		}
		
		
		return $Consolidado;
		
	}
	
	
}

/* Fin del archivo */