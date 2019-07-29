<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cargar_scan extends CI_Controller {
	
	/**
	 *Cargar los archivos adjuntos a la carpeta del pedido.
	 *@return nada.
	*/
	public function index()
	{
		
		//Validaciones
		$Proceso_Pedido = $this->seguridad_m->mysql_seguro(
			$this->input->post('scan_pedido')
		);
		
		$Proceso_Pedido = explode('-', $Proceso_Pedido);
		
		if(2 != count($Proceso_Pedido))
		{
			show_404();
			exit();
		}
		
		if($Proceso_Pedido[1] == 'imagen_proceso')
		{
			$Proceso_Pedido[0] += 0;
			if(0 == $Proceso_Pedido[0])
			{
				show_404();
				exit();
			}
		}
		else
		{
			$Proceso_Pedido[0] += 0;
			$Proceso_Pedido[1] += 0;
			
			if(
			0 == $Proceso_Pedido[0]
			|| 0 == $Proceso_Pedido[1]
			)
			{
				show_404();
				exit();
			}
		}
		
		
		//Fin validaciones
		
		$this->load->model('scan/cargar_scan_m', 'cscan');
		$this->cscan->cargar($Proceso_Pedido[0], $Proceso_Pedido[1], 6);
		
		
		$Redireccion = $this->seguridad_m->mysql_seguro(
			$this->input->post('scan_redireccion')
		);
		
		if('' == $Redireccion)
		{
			
			$Variables['Fechas']['dia1'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('dia1')
			);
			$Variables['Fechas']['mes1'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes1')
			);
			$Variables['Fechas']['anho1'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho1')
			);
			$Variables['Fechas']['dia2'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('dia2')
			);
			$Variables['Fechas']['mes2'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes2')
			);
			$Variables['Fechas']['anho2'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho2')
			);
			
			$Variables['Puesto'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('puesto')
			);
			
			$Variables['Id_Cliente'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('cliente')
			);
			
			$Variables['Trabajo'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('trabajo')
			);
			
			$Variables['cliente_tipo'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('cliente_tipo')
			);
			
			$this->load->view('/procesos/redirigir_v', $Variables);
		}
		else
		{
			
			if(
				strpos($Redireccion, '/pedidos/agregar/') !== false
				|| strpos($Redireccion, '/pedidos/modificar/') !== false
			)
			{
				echo 'ok';
			}
			else{
				header('location: '.$Redireccion);
			}
		}
	}
	
}

/* Fin del archivo */