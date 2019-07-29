<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grafica extends CI_Controller {
	
	/**
	 *Informacion en formato grafico de el objetivo del BSC seleccionado.
	*/
	public function index($Id_Bsc_Objetivo = 0, $Anho = 0)
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
		
		
		//Limpieza y validaciones de las variables principales
		$Id_Bsc_Objetivo += 0;
		$Anho += 0;
		
		if(0 == $Id_Bsc_Objetivo)
		{
			show_404();
			exit();
		}
		
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Balanced Scorecard',
			'Mensaje' => '',
			'Meses' => $this->Meses_m->MandarMesesAbre(),
			'Id_Bsc_Objetivo' => $Id_Bsc_Objetivo,
			'Anho' => $Anho
		);
		
		
		//Modelo que muestra informacion y datos acerca de los objetivos del bsc
		$this->load->model('balance/objetivos_m', 'objetivo');
		
		//Listado de los objetivos pertenecientes al BSC
		$Variables['Def_Objetivos'] = $this->objetivo->objetivos();
		//Se busca si existe el objetivo solicitado via url y si existe se almacena
		//la condicion [+|-] para preparar el grafico correcto
		$Variables['Condicion'] = '';
		foreach($Variables['Def_Objetivos'] as $Id_Perspectiva => $Perspectiva)
		{
			if(isset($Perspectiva['Objs'][$Id_Bsc_Objetivo]))
			{
				$Variables['Condicion'] = $Perspectiva['Objs'][$Id_Bsc_Objetivo]['Con'];
				break;
			}
		}
		
		//Si no se encontro el objetivo solicitado via url se detiene la ejecucion
		if('' == $Variables['Condicion'])
		{
			show_404();
			exit();
		}
		
		//Datos del objetivo solicitado para el anho solicitado
		$Variables['Datos'] = $this->objetivo->datos('todo', $Anho, $Id_Bsc_Objetivo);
		
		
		//Informacion para la generacion del grafico
		//Valores para cada par de informacion ([proy, real], [1,2],[3,4],[5,6])
		$Variables['Cantidad_Barras'] = array();
		//El grafico tomara el valor maximo para calcular los altos de cada barra
		$Variables['Maximo_Valor'] = 0;
		
		//Recorrido por cada mes para tomar los valores reales y proyectados
		foreach($Variables['Datos'][$Id_Bsc_Objetivo] as $Mes => $Valores)
		{
			//Datos por cada mes
			$Variables['Cantidad_Barras'][] = '['.$Valores['proy'].','.$Valores['real'].']';
			
			//Obtencion del valor maximo
			if($Variables['Maximo_Valor'] < $Valores['real'])
			{
				$Variables['Maximo_Valor'] = $Valores['real'];
			}
			//Obtener el valor maximo de las proyecciones.
			if($Variables['Maximo_Valor'] < $Valores['proy'])
			{
				$Variables['Maximo_Valor'] = $Valores['proy'];
			}
		}
		
		
		//Carga de las vistas
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('balance/grafica_v');
		$this->load->view('pie_v');
		
	}
	
	
}

/* Fin del archivo */