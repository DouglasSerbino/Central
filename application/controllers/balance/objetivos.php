<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Objetivos extends CI_Controller {
	
	/**
	 *Tablero principal del BSC, muestra las perspectivas, objetivos, indicadores
	 *y valores mensuales.
	 *Tambien muestra los formularios para agregar y modificar los objetivos.
	 *Alterna la informacion presentada entre los datos proyectados y los reales;
	 *ademas de especificar el anho a visualizar.
	*/
	public function index($Tipo_Objetivo = '', $Anho = 0)
	{
		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza para determinar si hemos recibido un año
		//Si no hemos recibido nada asignamos el año actual para generar el reporte.
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Objetivos Reales',
			'Mensaje' => '',
			'Tipo_Objetivo' => 'real',
			'Ver_Anho' => $Anho,
			'Meses' => $this->Meses_m->MandarMesesAbre()
			
		);
		
		//Si la solicitud que se recibe no es para ver datos reales, debe ser entonces
		//para ver los datos proyectados y asi se modifican las variables del encabezado.
		if('real' != $Tipo_Objetivo)
		{
			$Tipo_Objetivo = 'proyeccion';
			$Variables['Titulo_Pagina'] = 'Objetivos Proyectados';
			$Variables['Tipo_Objetivo'] = 'proyeccion';
		}
		
		
		
		//Modelo que muestra informacion y datos acerca de los objetivos del bsc
		$this->load->model('balance/objetivos_m', 'objetivo');
		//Listado de objetivos
		$Variables['Def_Objetivos'] = $this->objetivo->objetivos();
		//Datos mensuales de cada objetivo segun el tipo solicitado [real|proy]
		$Variables['Datos'] = $this->objetivo->datos($Tipo_Objetivo, $Anho);
		
		
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
		
		//Si se encontro un objetivo vacio, vueleve a llamar los datos de todos
		if($Vacios)
		{
			$Variables['Datos'] = $this->objetivo->datos($Tipo_Objetivo, $Anho);
		}
		
		//Carga de las vistas
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('balance/objetivos_v');
		$this->load->view('pie_v');
		
	}
	
	
	/**
	 *Agregar nuevo objetivo
	*/
	function agregar()
	{
		//Cargamos el modelo
		$this->load->model('balance/objetivos_m', 'objetivo');
		//Creacion del objetivo
		$this->objetivo->agregar();
		
	}
	
	
	/**
	 *Modificar objetivo.
	*/
	function modifica_objetivo($Id_Bsc_Objetivo)
	{
		
		//Limpieza
		$Id_Bsc_Objetivo += 0;
		
		//Si no hay un id correcto se finaliza la ejecucion
		if(0 == $Id_Bsc_Objetivo)
		{
			echo 0;
			exit();
		}
		
		//Cargamos el modelo
		$this->load->model('balance/objetivos_m', 'objetivo');
		//Modificar objetivo
		$this->objetivo->modifica_objetivo($Id_Bsc_Objetivo);
		
	}
	
	
	/**
	 *Eliminar un objetivo.
	*/
	function eliminar($Id_Bsc_Objetivo)
	{
		
		//Limpieza
		$Id_Bsc_Objetivo += 0;
		
		//Si no hay un id correcto se finaliza la ejecucion
		if(0 == $Id_Bsc_Objetivo)
		{
			echo 0;
			exit();
		}
		
		//Cargamos el modelo
		$this->load->model('balance/objetivos_m', 'objetivo');
		//Eliminar objetivo
		$this->objetivo->eliminar($Id_Bsc_Objetivo);
		
	}
	
	
	/**
	 *Actualizar objetivo
	*/
	function actualiza_datos($Tipo_Objetivo, $Anho)
	{
		
		//Limpieza y validaciones
		$Tipo_Objetivo = $this->seguridad_m->mysql_seguro($Tipo_Objetivo);
		$Anho += 0;
		
		//El tipo de objetivo es obligatorio
		if('real' != $Tipo_Objetivo && 'proyeccion' != $Tipo_Objetivo)
		{
			echo 'no_obje';
			exit();
		}
		
		//Se debe recibir un anho valido
		if(0 == $Anho)
		{
			echo 'no_date';
			exit();
		}
		
		//Cargamos el modelo
		$this->load->model('balance/objetivos_m', 'objetivo');
		//Actualizar/Modificar objetivo
		$this->objetivo->actualiza_datos($Tipo_Objetivo, $Anho);
	}
	
	
}

/* Fin del archivo */