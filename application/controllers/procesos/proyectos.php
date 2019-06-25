<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyectos extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function index($Tipo_Proy = 'pendiente', $Mes = 0, $Anho = 0)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Mes += 0;
		$Anho += 0;
		
		if(0 == $Mes)
		{
			$Mes = date('m');
		}
		elseif(10 > $Mes)
		{
			$Mes = '0'.$Mes;
		}
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Proyectos',
			'Mensaje' => '',
			'Tipo_Proy' => $Tipo_Proy,
			'Mes' => $Mes,
			'Anho' => $Anho
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		
		$this->load->model('procesos/proyectos_m', 'ciclo');
		$Variables['Informacion_pedidos'] = $this->ciclo->info_pedidos(
			$Tipo_Proy,
			'',
			$Mes,
			$Anho
		);
		$Variables['cliente_proyecto'] = $this->ciclo->cliente_proyecto();
		//Asignamos las variables para poder acceder desde la vista.
		
		$this->load->view('procesos/proyectos_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	public function finalizar($Id_Proyecto)
	{
		
		if('' == $this->input->post('razon'))
		{
			exit();
		}
		
		$Id_Proyecto += 0;
		
		$this->load->model('procesos/proyectos_m', 'proyecto');
		$this->proyecto->finalizar_proyecto(
			$Id_Proyecto,
			$Aprobacion = $this->seguridad_m->mysql_seguro(
				$this->input->post('razon')
			)
		);
		
	}
}
?>