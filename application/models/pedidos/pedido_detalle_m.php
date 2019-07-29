<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pedido_detalle_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Busca la informacion del pedido.
	 *@param string $Id_pedido.
	 *@return array.
	*/
	function pedido($Id_Pedido)
	{
		
		$Consulta = '
			select id_tipo_trabajo, fecha_entrada, fecha_entrega,
			prioridad, id_responsable, id_repro_deta
			from pedido
			where id_pedido = "'.$Id_Pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->row_array();
		
	}
	
	/**
	 *Cotizacion del pedido.
	 *@param string $Id_pedido.
	 *@return string array con el id_prod_clie como indice.
	*/
	function buscar_cotizacion($Id_pedido)
	{
		
		$Grupo = $this->session->userdata('id_grupo');
		
		
		$Consulta = '
			select prod.id_producto as iprod, proped.id_prod_ped as iprodped, producto, proped.precio,
			proped.cantidad, proped.pulgadas, proped.cantidad, proped.alto, proped.ancho
			from producto prod, producto_pedido proped
			where prod.id_producto = proped.id_producto and id_pedido = "'.$Id_pedido.'"
			order by proped.id_producto asc, proped.id_prod_ped asc
		';
		//echo $Consulta;
		
		$Resultado = $this->db->query($Consulta);
		
		$Cotizacion = array();
		
		if($Resultado->num_rows() >= 1)
		{
			
			foreach($Resultado->result_array() as $Fila)
			{

				if(!isset($Cotizacion[$Fila['iprod']]['total']))
				{
					$Cotizacion[$Fila['iprod']]['total'] = 0;
				}
				$Cotizacion[$Fila['iprod']]['total'] += $Fila['precio'] * $Fila['pulgadas'];

				$Cotizacion[$Fila['iprod']][] = array(
					'alto' => $Fila['alto'],
					'ancho' => $Fila['ancho'],
					'precio' => $Fila['precio'],
					'cantidad' => $Fila['cantidad'],
					'pulgadas' => $Fila['pulgadas'],
					'producto' => $Fila['producto'],
					'iprodped' => $Fila['iprodped']
				);
			}

			
			
		}
		
		//print_r($Cotizacion);
		return $Cotizacion;
		
	}
	
	
	/**
	 *Buscar en la base de datos si existe el proceso indicado por el usuario.
	 *@param string $Id_Cliente.
	 *@param string $Proceso.
	 *@return string array con informacion del proceso, "" si no existe.
	*/
	function observaciones(
		$Id_Pedido
	)
	{
		/*
		if(false == $Id_Grupo)
		{
			$Id_Grupo = $this->session->userdata('id_grupo');
		}
		
		$Pedidos = 'id_pedido= "'.$Id_pedido.'"';
		
		//Hay pedidos que estan "enlazados" a otros y debo saber si este esta enlazado
		//para tomar todas las observaciones
		$Consulta = '
			select id_ped_primario, id_ped_secundario
			from pedido_pedido
			where id_ped_primario = "'.$Id_pedido.'"
			or id_ped_secundario = "'.$Id_pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Pedidos = '(';
			foreach($Resultado->result_array() as $Fila)
			{
				if('(' != $Pedidos)
				{
					$Pedidos .= ' or ';
				}
				$Pedidos .= 'id_pedido = "'.$Fila['id_ped_primario'].'"';
				$Pedidos .= ' or id_pedido = "'.$Fila['id_ped_secundario'].'"';
			}
			$Pedidos .= ')';
		}
		*/
		$Consulta = '
			select obs.fecha_hora, obs.observacion, obs.id_observacion, usu.id_usuario, usu.usuario, req_aprobacion
			from observacion obs, usuario usu
			where obs.id_usuario=usu.id_usuario
			and id_pedido = "'.$Id_Pedido.'"
			order by obs.fecha_hora desc
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			
			return $Resultado->result_array();
			
		}
		else
		{
				return '';	
		}	
	}
	
	
}

/* Fin del archivo */