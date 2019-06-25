<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempo_consumo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Buscar la informacion relacionada al proceso proceso
	 *@param string $id_cliente.
	 *@return array.
	*/
	function informacion_proceso($id_pedido)
	{
		//Establecemos la consulta para extraer la informacion
		//relacionada al proceso.
		$Consulta = '
							select proc.id_proceso, cli.nombre as cliente, proc.proceso,
								cli.codigo_cliente, proc.nombre as producto_n
							from cliente cli, procesos proc, pedido ped
							where cli.id_cliente = proc.id_cliente
								and proc.id_proceso = ped.id_proceso
								and id_pedido = "'.$id_pedido.'"
								and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
						';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			//Si la hay regresamos el array.
			return $Resultado->result_array();
		}
		else
		{
			//Si no hay informacion regresamos un array.
			return array();
		}
	}
	
	/**
	 *Busca la informacion general de todos los usuarios que realizaron el trabajo.
	 *@param string $id_pedido.
	 *@return array.
	*/
	function info_general($id_pedido, $Id_Proceso = 0)
	{
		
		$Orden = '';
		
		//Debido a que se va a facturar este pedido debo saber los pedidos que puedan
		//existir entre este y el ultimo pedido cotizado para sumar los tiempos
		$Consulta = '
			select ped.id_pedido, orden
			from pedido ped, pedido_sap sap
			where ped.id_pedido = sap.id_pedido and id_proceso = "'.$Id_Proceso.'"
			and sap != ""
			order by ped.id_pedido desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);
		if(1 == $Resultado->num_rows())
		{
			$Orden = $Resultado->row_array();
		}
		
		
		
		
		//Establecemos la consulta para extraer la informacion
		//relacionada al proceso.
		
		
		//exit();
		
		$Consulta = '
			select sum(ped_tie.tiempo) as tiempo_usuario, usu.puesto, usu.usuario,
			inicio, fin
			from pedido_tiempos ped_tie, usuario usu, departamentos dpto
			where usu.id_usuario = ped_tie.id_usuario and usu.id_dpto = dpto.id_dpto
			and ped_tie.id_pedido = "'.$id_pedido.'"
			and usu.id_grupo = "'.$this->session->userdata('id_grupo').'"
			and dpto.codigo != "Gerencia" and dpto.codigo != "Plani"
			and dpto.codigo != "Sistemas" and dpto.codigo != "Ventas"
			and dpto.codigo != "SAP" and dpto.codigo != "DESPACHO"
			group by usu.id_usuario
			order by ped_tie.id_tiempo
		';
		
		
		
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			//Si la hay regresamos el array.
			
			if('' != $Orden && $id_pedido != $Orden['id_pedido'])
			{
				return array('tiem' => $Resultado->result_array(), 'orden' => $Orden['orden']);
			}
			else
			{
				return $Resultado->result_array();
			}
		}
		else
		{
			//Si no hay informacion regresamos un array.
			return array();
		}
	}
	
	
	/**
	 *Busca la informacion del pedido sap
	 *@param string $id_pedido.
	 *@return array.
	*/
	function info_sap($id_pedido)
	{
		//Establecemos la consulta para extraer la informacion
		//relacionada al proceso.
		$Consulta = '
							select sap, id_pedido_sap, orden
							from pedido_sap
							where id_pedido = "'.$id_pedido.'"
						';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			//Si la hay regresamos el array.
			return $Resultado->result_array();
		}
		else
		{
			//Si no hay informacion regresamos un array.
			return array();
		}
	}
	
	
	/**
	 *Busca la informacion general de todos los materiales que se utilizaron
	 *en la realizacion del proceso o pedido.
	 *@param string $id_pedido.
	 *@return array.
	*/
	function info_materiales($id_pedido)
	{
		//Establecemos la consulta para extraer la informacion
		//relacionada al proceso.
		$Consulta = '
							select invent_mat.codigo_sap, invent_mat.nombre_material,
								ped_mat.cantidad, invent_mat.tipo, ped_mat.reproceso
							from pedido ped, pedido_material ped_mat, inventario_material invent_mat
							where ped_mat.id_pedido = "'.$id_pedido.'"
								and ped_mat.id_pedido = ped.id_pedido
								and ped_mat.id_inventario_material = invent_mat.id_inventario_material
								and invent_mat.id_grupo = "'.$this->session->userdata('id_grupo').'"
						';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			//Si la hay regresamos el array.
			return $Resultado->result_array();
		}
		else
		{
			//Si no hay informacion regresamos un array.
			return array();
		}
	}
}
/* Fin del archivo */