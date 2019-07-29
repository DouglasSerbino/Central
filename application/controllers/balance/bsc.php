<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bsc extends CI_Controller {
	
	/**
	 *Tabla de resultados del BSC.
	 *Representa los objetivos con su cumplimiento mensual, trimestral y anual.
	*/
	public function index($Anho = 0)
	{
		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza y validacion
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Balanced scorecard',
			'Mensaje' => '',
			'Ver_Anho' => $Anho,
			'Meses' => $this->Meses_m->MandarMesesAbre()
		);
		
		
		//Modelo que muestra informacion y datos acerca de los objetivos del bsc
		$this->load->model('balance/objetivos_m', 'objetivo');
		//Listado de objetivos
		$Variables['Def_Objetivos'] = $this->objetivo->objetivos();
		//Datos mensuales de cada objetivo real y proyectado
		$Variables['Datos'] = $this->objetivo->datos('todo', $Anho);
		
		
		
		//Comprobacion de que todos los objetivos tienen un dato almacenado.
		$Vacios = false;
		foreach($Variables['Def_Objetivos'] as $Id_Perspectiva => $Perspectiva)
		{
			foreach($Perspectiva['Objs'] as $Id_Bsc_Objetivo => $Objetivo)
			{
				if(!isset($Variables['Datos'][$Id_Bsc_Objetivo]))
				{
					//Si almenos uno de los objetivos no posee datos, se agregan datos
					//en la base de datos iguales a cero para evitar errores.
					$this->objetivo->inserta_datos($Id_Bsc_Objetivo, $Anho);
					$Vacios = true;
				}
			}
		}
		
		
		//Carga de las vistas
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('balance/bsc_v');
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */