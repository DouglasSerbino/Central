<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listar_scan extends CI_Controller {
	
	/**
	 *Busca los archivos adjuntos del pedido.
	 *@return nada.
	*/
	public function archivos($Proceso_Pedido = 0)
	{
		
		//Validaciones
		$Proceso_Pedido = explode('-', $Proceso_Pedido);
		
		if(2 != count($Proceso_Pedido))
		{
			show_404();
			exit();
		}
		
		
		$Proceso_Pedido[0] += 0;
		$Proceso_Pedido[1] += 0;
		
		if(0 == $Proceso_Pedido[0] || 0 == $Proceso_Pedido[1])
		{
			show_404();
			exit();
		}
		
		
		$Variables = array();
		
		$this->load->model('scan/listar_scan_m', 'liscan');
		//Listado de los archivos adjuntos pertenecientes a este pedido
		$Scans = $this->liscan->listar(
			$Proceso_Pedido[1]
		);
		
		if('' != $Scans)
		{
			$Variables['Ajax'][] = $Scans;
		}
		
		
		//Necesitamos ver los scaneos para los pedidos enlazados
		$this->load->model('pedidos/enlaces_m', 'enlace');
		$Padre = $this->enlace->es_padre($Proceso_Pedido[1]);
		
		if(0 != $Padre)
		{
			$Scans = $this->liscan->listar(
				$Padre['id_ped_secundario']
			);
			
			if('' != $Scans)
			{
				$Variables['Ajax'][] = $Scans;
			}
		}
		
		
		$Hijo = $this->enlace->es_hijo($Proceso_Pedido[1]);
		
		if(0 != $Hijo)
		{
			$Scans = $this->liscan->listar(
				$Hijo['id_ped_primario']
			);
			
			if('' != $Scans)
			{
				$Variables['Ajax'][] = $Scans;
			}
		}
		
		if(isset($Variables['Ajax']))
		{
			$Variables['Ajax'] = '['.implode(', ', $Variables['Ajax']).']';
		}
		else
		{
			$Variables['Ajax'] = '';
		}
		
		
		
		$Variables['Ajax'] = '{"a":'.$Variables['Ajax'].'}';
		
		$this->load->view('ajax_v', $Variables);
		
	}
	
}

/* Fin del archivo */