<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cambio_fecha extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus de usuario existentes.
	 *Tiene opciones para modificar y desactivar.
	 *@param string $Pagina.
	 *@param string $Inicio.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{		
		$Permitido = array('Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		if($Mensaje == 'ok')
		{
			$Mensaje = 'Solicitud de Cambio de Fechas Ingresado Correctamente';
		}
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' =>'SOLICITUD CAMBIO DE FECHA',
			'Mensaje' => $Mensaje
		);
		$this->load->view('encabezado_v', $Variables);
		
			//Listado de los meses
		$Variables['Meses'] = array(
			'01' => 'Enero',
			'02' => 'Febrero',
			'03' => 'Marzo',
			'04' => 'Abril',
			'05' => 'Mayo',
			'06' => 'Junio',
			'07' => 'Julio',
			'08' => 'Agosto',
			'09' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre'
		);
		$Variables['mes'] = date('m');
		$Variables['anho'] = date('Y');
		if($_POST)
		{
			$Variables['mes'] = $this->seguridad_m->mysql_seguro($this->input->post('mes'));
			$Variables['anho'] = $this->seguridad_m->mysql_seguro($this->input->post('anho'));
		}
		
		if('Sistemas' == $this->session->userdata('codigo'))
		{
			$this->load->model('herramientas_sis/cambiar_fecha_m', 'fecha');
			$Variables['Cambios'] = $this->fecha->info_cambios($Variables['anho'], $Variables['mes']);
			$Variables['Grafica'] = $this->fecha->grafica($Variables['anho']);
			$this->load->view('herramientas_sis/cambio_fecha_v', $Variables);
		}
		elseif('Plani' == $this->session->userdata('codigo'))
		{
			$this->load->view('herramientas_sis/sol_cambio_fecha_v', $Variables);
		}
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
		
	/**
		Funcion para modificar la informacion de un pedido en transito.
	**/
	public function sol_cambio()
	{
		$this->ver_sesion_m->no_clientes();
		
		$Proc1 = $this->seguridad_m->mysql_seguro(
			$this->input->post('proc1')
		);
		
		$Proc2 = $this->seguridad_m->mysql_seguro(
			$this->input->post('proc2')
		);
		
		$Proc3 = $this->seguridad_m->mysql_seguro(
			$this->input->post('proc3')
		);
		
		if($Proc1 != '')
		{
			$Opcion['uno']['proc'] = $Proc1;
			$Opcion['uno']['fecha'] = $this->seguridad_m->mysql_seguro($this->input->post('fecha1'));
			$Opcion['uno']['opcion'] = $this->seguridad_m->mysql_seguro($this->input->post('opcion1'));
		}
		
		if($Proc2 != '')
		{
			$Opcion['dos']['proc'] = $Proc2;
			$Opcion['dos']['fecha'] = $this->seguridad_m->mysql_seguro($this->input->post('fecha2'));
			$Opcion['dos']['opcion'] = $this->seguridad_m->mysql_seguro($this->input->post('opcion2'));
		}
		
		if($Proc3 != '')
		{
			$Opcion['tres']['proc'] = $Proc3;
			$Opcion['tres']['fecha'] = $this->seguridad_m->mysql_seguro($this->input->post('fecha3'));
			$Opcion['tres']['opcion'] = $this->seguridad_m->mysql_seguro($this->input->post('opcion3'));
		}
		
		$Opcion['solicitado'] = $this->seguridad_m->mysql_seguro($this->input->post('solicitado'));
		
		
		$this->load->model('herramientas_sis/cambiar_fecha_m', 'fecha');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		$Solicitud = $this->fecha->guardar_solicitud($Opcion);
		
		header('location: /herramientas_sis/cambio_fecha/index/ok');
	}
	
	public function eliminar_cambio()
	{
		$this->ver_sesion_m->no_clientes();
		
		$Proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		$Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		
		$Tiempo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tiempo')
		);
		
		$this->load->model('herramientas_sis/cambiar_fecha_m', 'fecha');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		echo $this->fecha->eliminar_cambio($Proceso, $Cliente, $Tiempo);
	}
}

/* Fin del archivo */