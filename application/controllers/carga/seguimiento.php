<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguimiento extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los pedidos.
	 *@param string $Puesto.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Puesto = '', $Mensaje = '')
	{
		/*
		 *Determinamos que departamentos tendran acceso a esta pagina.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Puestos de Trabajo',
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
		$Variables['Id_material'] = '';
		$Variables['tipo_cliente'] = 'todos';
		
		//Opciones de visualizacion
		$Variables['Puesto'] = 'todos';
		$Variables['Id_Cliente'] = 'todos';
		$Variables['Trabajo'] = 'incompleto';
		$Variables['Bus_Proceso'] = '';
		$Variables['Mostar_Datos'] = false;
		$Variables['Pais_C'] = '';
		
		/*
		 * Valores que se reciben por el metodo $_POST
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
			$Variables['Bus_Proceso'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('bus_proceso')
			);
			$Variables['Id_material'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('bus_material')
			);
			$Variables['Pais_C'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('pais_c')
			);
			
			$Variables['Mostar_Datos'] = true;
		}
		
		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();
		
		
		//Listado de los clientes
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		
		//Llamamos a la funcion encagada de proporcionar la informacion del cliente
		$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
			'id_cliente, codigo_cliente, nombre',
			true
		);
		
		
		//Listado de los usuarios
		$this->load->model('usuarios/listado_usuario_m', 'lusus');
		$Variables['Usuarios'] = $this->lusus->departamento_usuario();
		
		
		//Trabajos en carga segun los datos del formulario
		$Variables['Carga'] = array();
		//Cargamos el modelo.
		$this->load->model('carga/seguimiento_m', 'seguimiento');
		//Si se ha dado click en el boton de Generar, buscamos la información.
		if($Variables['Mostar_Datos'])
		{
			//Carga laboral para el grupo
			$Variables['Carga'] = $this->seguimiento->carga(
				$Variables['Fechas'],
				$Variables['Puesto'],
				$Variables['Id_Cliente'],
				$Variables['Trabajo'],
				true,
				false,
				$Variables['Bus_Proceso'],
				$Variables['Id_material'],
				0,
				$Variables['Pais_C']
			);
			
		}
		
		$Variables['bus_materiales'] = $this->seguimiento->materiales();
		//Numero de cajas que se mostraran en el formulario de adjuntos.
		$Variables['num_cajas'] = 1;
		$Variables['Redir'] = '';




		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();

		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/seguimiento_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}

	public function obtenerDatos(){
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);

		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		
		
		//Obtenemos las variables enviadas por POST
		$departamento = $this->seguridad_m->mysql_seguro($this->input->post('departamento'));
		$mes = $this->seguridad_m->mysql_seguro($this->input->post('mes'));
		$ano = $this->seguridad_m->mysql_seguro($this->input->post('ano'));

		$this->load->model('carga/seguimiento_m', 'seguimiento');
		$operadores = $this->seguimiento->obtenerOperadores($departamento);
		$trabajos_realizados = $this->seguimiento->obtenerTrabajosRealizados($departamento,$mes,$ano);
		$rechazos = $this->seguimiento->obtenerRechazos($departamento,$mes,$ano);
		$horas_extras = $this->seguimiento->obtenerHorasExtras($departamento,$mes,$ano);

		$infoarray = array('operadores'=>$operadores,'trabajos'=>$trabajos_realizados,'rechazos'=>$rechazos,'extras'=>$horas_extras);

		//add the header here
	    header('Content-Type: application/json');
	    echo json_encode($infoarray);
	}

	public function obtenerDatosUsuario(){

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);

		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Obtenemos las variables enviadas por POST
		$usuario = $this->seguridad_m->mysql_seguro($this->input->post('empleado'));
		$mes = $this->seguridad_m->mysql_seguro($this->input->post('mes'));
		$ano = $this->seguridad_m->mysql_seguro($this->input->post('ano'));


		//formulario a enviar para generar la tabla
		$diaC = $this->seguridad_m->mysql_seguro($this->input->post('diaC'));
		$mesC = $this->seguridad_m->mysql_seguro($this->input->post('mesC'));
		$anoC = $this->seguridad_m->mysql_seguro($this->input->post('anoC'));

		$diaF = $this->seguridad_m->mysql_seguro($this->input->post('diaF'));
		$mesF = $this->seguridad_m->mysql_seguro($this->input->post('mesF'));
		$anoF = $this->seguridad_m->mysql_seguro($this->input->post('anoF'));

		$pais = $this->seguridad_m->mysql_seguro($this->input->post('pais'));
		$puesto = $this->seguridad_m->mysql_seguro($this->input->post('puesto'));
		$cliente = $this->seguridad_m->mysql_seguro($this->input->post('cliente'));

		$r_terminado = $this->seguridad_m->mysql_seguro($this->input->post('r_terminado'));
		$r_inconcluso = $this->seguridad_m->mysql_seguro($this->input->post('r_inconcluso'));
		$r_atrasados = $this->seguridad_m->mysql_seguro($this->input->post('r_atrasados'));
		$r_reproceso = $this->seguridad_m->mysql_seguro($this->input->post('r_reproceso'));

		$material = $this->seguridad_m->mysql_seguro($this->input->post('material'));


		$this->load->model('carga/seguimiento_m', 'seguimiento');
		$trabajos_realizados = $this->seguimiento->obtenerTrabajosRealizadosUsuario($usuario,$mes,$ano);
		$rechazos = $this->seguimiento->obtenerRechazosUsuario($usuario,$mes,$ano);
		$horas_extras = $this->seguimiento->obtenerHorasExtrasUsuario($usuario,$mes,$ano);
		$utilizadas = $this->seguimiento->obtenerTiempoUtilizadoUsuario($usuario,$mes,$ano);

		$infoarray = array('trabajos'=>$trabajos_realizados,'rechazos'=>$rechazos,'extras'=>$horas_extras,'utilizadas'=>$utilizadas);

		//add the header here
	    header('Content-Type: application/json');
	    echo json_encode($infoarray);

	}
}

/* Fin del archivo */