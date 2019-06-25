<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_procesos extends CI_Controller {
	
	/**
	 *Pagina que permite ingresar los procesos
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Codigo_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo_cliente')
		);
		
		$Procesos = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Proceso',
			'Mensaje' => ''
		);
		
		
		
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		
		//Extraemos la informacion del producto
		$Variables['Info_producto'] = $this->buscar_proc->cliente_proceso($Codigo_cliente, $Procesos);
		
		if('' == count($Variables['Info_producto']))
		{
			$Variables['Mensaje'] = 'El Proceso ingresado no existe. <br /> Verifique la informaci&oacute;n proprocionada.';
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
		}
		else
		{
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			
			//Pagina que muestra el listado de las unidades existentes y su enlace de
			//ingreso, se adjunta las variables que necesita
			$this->load->view('procesos/modificar_procesos_v', $Variables);
			
			
			//Se carga el pie de pagina
			$this->load->view('pie_v');
		}
	}
	
	public function modificar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Cod_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo_cliente')
		);
		
		
		$Cod_clienteAnt = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo_clienteant')
		);
		
		$Id_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		
		
		$Procesos = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		$Proceso_ant = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso_ant')
		);
		
		$Id_proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_proceso')
		);
		
		$Producto= $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		$this->load->model('procesos/modificar_proceso_m', 'mod_proc');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		$Modificar = $this->mod_proc->modificar_sql(
			$Cod_cliente,
			$Id_cliente,
			$Procesos,
			$Proceso_ant,
			$Id_proceso,
			$Producto,
			$Cod_clienteAnt
		);
		
		//echo $Modificar;
		
		if('ok' == $Modificar)
		{
			//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			header('location: /procesos/buscar_procesos/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('existe' == $Modificar)
		{
			//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			header('location: /procesos/buscar_procesos/index/existe');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{
			//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /procesos/buscar_procesos');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
	
}

/* Fin del archivo */