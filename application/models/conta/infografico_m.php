<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infografico_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function sin_factura($Anho, $Mes)
	{
		
		$Total = array('mensual' => array(), 'anual' => array());
		
		if('anual' == $Mes)
		{
			$Mes = date('m');
		}
		
		//Mensual
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap = ""
			and fecha_reale >= "'.$Anho.'-'.$Mes.'-01" and fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['mensual'] = $Resultado->row_array();
		
		
		//Anual
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap = ""
			and fecha_reale >= "'.$Anho.'-01-01" and fecha_reale <= "'.$Anho.'-12-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['anual'] = $Resultado->row_array();
		
		return $Total;
		
	}
	
	function sin_quedan($Anho, $Mes)
	{
		
		$Total = array('mensual' => array(), 'anual' => array());
		
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap != "" and factura = ""
			and fecha_reale >= "'.$Anho.'-'.$Mes.'-01" and fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['mensual'] = $Resultado->row_array();
		
		
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap != "" and factura = ""
			and fecha_reale >= "'.$Anho.'-01-01" and fecha_reale <= "'.$Anho.'-12-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['anual'] = $Resultado->row_array();
		
		return $Total;
		
	}
	
	function sin_pago($Anho, $Mes)
	{
		
		$Total = array('mensual' => array(), 'anual' => array());
		
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap != "" and factura != "" and orden = ""
			and factura >= "'.$Anho.'-'.$Mes.'-01" and factura <= "'.$Anho.'-'.$Mes.'-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['mensual'] = $Resultado->row_array();
		
		
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap != "" and factura != "" and orden = ""
			and factura >= "'.$Anho.'-01-01" and factura <= "'.$Anho.'-12-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['anual'] = $Resultado->row_array();
		
		return $Total;
		
	}
	
	function pagado($Anho, $Mes)
	{
		
		$Total = array('mensual' => array(), 'anual' => array());
		
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap != "" and factura != "" and orden != ""
			and factura >= "'.$Anho.'-'.$Mes.'-01" and factura <= "'.$Anho.'-'.$Mes.'-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['mensual'] = $Resultado->row_array();
		
		
		$Consulta = '
			select count(id_pedido_sap) as total, sum(venta) as vendido
			from cliente clie, pedido_sap sapo, pedido ped
			where clie.id_cliente = sapo.id_cliente and sapo.id_pedido = ped.id_pedido
			and venta > 0 and sap != "" and factura != "" and orden != ""
			and factura >= "'.$Anho.'-01-01" and factura <= "'.$Anho.'-12-31"
			and id_grupo = '.$this->session->userdata('id_grupo').'
		';
		$Resultado = $this->db->query($Consulta);
		$Total['anual'] = $Resultado->row_array();
		
		return $Total;
		
	}
	
}
/* Fin del archivo */