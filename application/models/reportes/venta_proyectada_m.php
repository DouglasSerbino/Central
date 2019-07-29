<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venta_proyectada_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Proyecciones mensuales
	*/
	function proyecciones_mensuales($anho, $mes)
	{
		
		//Total de clientes
		$Consulta = '
				select sum( proyeccion ) as proyeccion
				from venta_proyeccion proy, cliente cli
				where anho = "'.$anho.'"
				and mes = "'.$mes.'"
				and cli.id_cliente = proy.id_cliente
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		
		$Resultado = $this->db->query($Consulta);
		
		$Proyeccion = 0;
		if(1 == $Resultado->num_rows())
		{
			$Proyeccion = $Resultado->row_array();
			$Proyeccion = number_format($Proyeccion['proyeccion'], 0, '', '');
		}
		
		return $Proyeccion;
	}
	
	/**
	 *Pendiente de Facturacion 
	*/
	function pendiente_facturar($anho, $mes)
	{
		
		//Total de clientes
		$Consulta = '
			select sum(sap.venta) as venta
			from pedido_sap sap, cliente cli
			where confirmada != "Si"
			and cli.id_cliente = sap.id_cliente
			and fecha >= "'.$anho.'-'.$mes.'-01" and fecha <= "'.$anho.'-'.$mes.'-31"
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Pendiente = 0;
		if(1 == $Resultado->num_rows())
		{
			$Pendiente = $Resultado->row_array();
			$Pendiente = ceil($Pendiente['venta']);
		}
		
		return $Pendiente;
	}
	
	/**
	 *Ventas Acumuladas.
	*/
	function ventas_acumuladas($anho, $mes)
	{
		$Ventas = 0;
		
		/*
		$Consulta = '
			select sum(venta) as venta, fecha
			from cliente clie, pedido_sap sapo
			where clie.id_cliente = sapo.id_cliente
			and fecha >= "'.$anho.'-'.$mes.'-01" and fecha <= "'.$anho.'-'.$mes.'-31"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by fecha
		';
		*/
		$Consulta = '
			select sum(venta) as venta
			from cliente clie, pedido_sap sapo
			where clie.id_cliente = sapo.id_cliente
			and fecha >= "'.$anho.'-'.$mes.'-01" and fecha <= "'.$anho.'-'.$mes.'-31"
			and confirmada = "Si"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		$Resultado = $Resultado->row_array();
		
		return ceil($Resultado['venta']);
	}
}

/* Fin del archivo */