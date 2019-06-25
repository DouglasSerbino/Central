<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V_seguimiento extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los pedidos.
	 *@param string $Puesto.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Puesto = '', $Mensaje = '')
	{
		/*
		 *Esta pagina es la que se muestra a los clientes, Ventas.
		 *Determinamos los departamentos con acceso a esta informacion.
		*/
		
		$Permitido = array('Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Trabajos',
			'Mensaje' => $Mensaje
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//**Variables que controlan el reporte**//
		//**Valores predeterminados**//
		//Opciones de Fechas
		$Variables['Fechas']['dia1'] = date('d');
		$Variables['Fechas']['mes1'] = date('m');
		$Variables['Fechas']['anho1'] = date('Y');
		$Variables['Fechas']['dia2'] = date('d');
		$Variables['Fechas']['mes2'] = date('m');
		$Variables['Fechas']['anho2'] = date('Y');
		
		//Opciones de visualizacion
		$Variables['Puesto'] = 'todos';
		$Variables['Id_Cliente'] = 'todos';
		$Variables['Trabajo'] = 'incompleto';
		
		$Variables['Mostar_Datos'] = false;
		
		/*
		 *Valores que se reciben por el metodo $_POST
		*/
		if('' != $this->input->post('dia1'))
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
			
			$Variables['Mostar_Datos'] = true;
		}
		
		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();
		
		//Trabajos en carga segun los datos del formulario
		$Variables['Carga'] = array();
		
		//Si se ha dado click en el boton de generar.
		if($Variables['Mostar_Datos'])
		{
			//Cargamos el modelo
			$this->load->model('carga/seguimiento_m', 'seguimiento');
			//Buscamos los trabajos.
			$Variables['Carga'] = $this->seguimiento->carga(
				$Variables['Fechas'],
				'todos',
				$this->session->userdata('id_cliente'),
				$Variables['Trabajo']
			);
			/*
			//Cargamos el modelo
			$this->load->model('usuarios/usuario_grupo_m', 'usu_grup');
			$Usu_Grup = $this->usu_grup->listado();
			
			//Que id_cliente poseo en los otros grupos?
			$this->load->model('clientes/cliente_grupo_m', 'clie_grup');
			$Clie_Grup = $this->clie_grup->listado($Usu_Grup);
			
			
			if(0 < count($Clie_Grup))
			{
				$Variables['Carga_Grupos'] = array(
					'trabajos' => array(),
					'ruta' => array(),
					'enlaces' => array()
				);
				
				//Busqueda de los pedidos ingresados para este grupo en otro
				foreach($Clie_Grup as $Id_Grupo => $Id_Cliente)
				{
					//print_r($Variables['Carga_Grupos']['enlaces']);
					$Soy_Temporal = $this->seguimiento->carga(
						$Variables['Fechas'],
						'todos',
						$Id_Cliente,
						$Variables['Trabajo'],
						true,
						true
					);
					//Asignamos la informacion al array.
					if(0 < count($Soy_Temporal))
					{
						$Variables['Carga_Grupos']['trabajos'] =
							$Variables['Carga_Grupos']['trabajos']
							+ $Soy_Temporal['trabajos'];
						
						$Variables['Carga_Grupos']['ruta'] =
							$Variables['Carga_Grupos']['ruta']
							+ $Soy_Temporal['ruta'];
						
						$Variables['Carga_Grupos']['enlaces'] =
							$Variables['Carga_Grupos']['enlaces']
							+ $Soy_Temporal['enlaces'];
					}	
				}	
			}
			*/
		}
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/v_seguimiento_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */