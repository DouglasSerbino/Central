<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Realiza la busqueda de los procesos para realizar el reporte de cumplimiento.
	 *El reporte se hara en formato de texto
	 *@param string $fmanho1.
	 *@param string $fmmes1.
	 *@return array.
	*/
	function cumplimiento_clientes_texto($fmanho1, $fmmes1)
	{
		//Establecemos la consulta para extraer la informacion
		//relacionada al proceso.
		$fecha1 = $fmanho1.'-'.$fmmes1.'-01';
		$fecha2 = $fmanho1.'-'.$fmmes1.'-31';
		$tpuntual = 0;
		$Consulta = '
			select distinct proc.id_cliente as id_cliente, codigo_cliente
			from cliente clie, procesos proc, pedido ped
			where clie.id_cliente = proc.id_cliente
			and activo = "s"
			and proc.id_proceso = ped.id_proceso
			and ped.fecha_entrega >= "'.$fecha1.'"
			and ped.fecha_entrega <= "'.$fecha2.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by proc.id_cliente
		';
		
		//echo $Consulta.'<br><br>';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		$Informacion = $Resultado->result_array();
		$info = array();
		//print_r($Informacion);
		foreach($Informacion as $Datos)
		{
			$info[$Datos['id_cliente']]['codigo_cliente'] = $Datos['codigo_cliente'];	
			
			
			$Consulta_fechas = '
				select ped.fecha_entrega, ped.fecha_reale
				from procesos proc, pedido ped
				where proc.id_proceso = ped.id_proceso
				and ped.fecha_entrega >= "'.$fecha1.'"
				and ped.fecha_entrega <= "'.$fecha2.'"
				and proc.id_cliente = "'.$Datos['id_cliente'].'"
				and ped.fecha_reale != "0000-00-00"
			';
										
			$Resultado_fechas = $this->db->query($Consulta_fechas);
		
			$Informacion_fechas = $Resultado_fechas->result_array();
			
			$pedido = 0;
			$puntual = 0;
			$atrasado = 0;
			
			foreach($Informacion_fechas as $Datos_fechas)
			{
				$pedido++;
				
				$fe_real = $Datos_fechas["fecha_reale"];
				$fe_entre = $Datos_fechas["fecha_entrega"];
				if($fe_real > $fe_entre)
				{
					++$atrasado;
				}
				else
				{
					++$puntual;
				}
			}
			$info[$Datos['id_cliente']]['pedido'] = $pedido;
			$info[$Datos['id_cliente']]['atrasado'] = $atrasado;
			$info[$Datos['id_cliente']]['puntual'] = $puntual;
			
			$porcentaje = 0;
			if($pedido > 0)
			{
				$porcentaje = round(($puntual * 100) / $pedido);
			}
			$info[$Datos['id_cliente']]['porcentaje'] = $porcentaje;
		}
		
		ksort($info);
		//print_r($info);
		
		return $info;
	}
}

/* Fin del archivo */