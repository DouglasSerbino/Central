<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class proceso_cli extends CI_Controller {
	
	/**
	 *Aca mostraremos el nombre del cliente.
	 *@return nombre del cliente.
	*/
	public function cliente($Codigo_Cliente)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$mostrar_clientes = '';
		//Modelo que realiza la busqueda de los clientes.
		$this->load->model('procesos/proceso_cli_m', 'proce_cliente');
		//proce_cliente == Proceso_cli_m
		if($Codigo_Cliente != '')
		{
			//Obtencion del nombre del cliente.
			$mostrar_clientes = $this->proce_cliente->buscar_cliente($Codigo_Cliente);
		}
		
		echo $mostrar_clientes;
	}
	
	/**
	 *Aca mostraremos el correlativo para un nuevo proceso.
	 *@return Numero de correlativo.
	*/
	public function generar_correlativo($Codigo_Cliente)
	{
		
		
		
		$mostrar_correlativo = '';
		//Modelo que realiza la busqueda de los clientes.
		$this->load->model('procesos/proceso_cli_m', 'proce_cliente');
		//proce_cliente == Proceso_cli_m
		
		if($Codigo_Cliente != '')
		{
			//Obtencion del nombre del cliente.
			$mostrar_correlativo = $this->proce_cliente->genera_correlativo($Codigo_Cliente);
		}
		
		echo $mostrar_correlativo;
	}
}
?>