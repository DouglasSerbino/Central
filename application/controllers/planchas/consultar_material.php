<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consultar_material extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($codigo = '', $ordenar = 'nombre_tipo')
	{
		
		$this->ver_sesion_m->no_clientes();
		if($codigo == 'ok' and $ordenar == 'msj')
		{
			$Mensaje = 'Se ha actualizado en el sistema la cantidad de material con la que ha iniciado este periodo.<br />';
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'REPORTE DE MATERIA PRIMA',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);

		if($_POST)
		{
			$codigo = $this->seguridad_m->mysql_seguro($this->input->post('codigo'));			
		}
		
		if($ordenar == "fecha")
		{
			 $ordenar = 'year, mes, dia asc';
		}
		$Variables['codigo'] = $codigo;
		
		//Modelo que realiza la busqueda de los click_tabs.
		$this->load->model('planchas/planchas_m', 'planchas');
		
		$Variables['planchas_especifica'] = array();
		$Variables['retazos_planchas'] = array();
 		
		if($codigo != '' and $codigo != 'ok')
		{
			$Variables['planchas_especifica'] = $this->planchas->buscar_planchas($codigo);
			$Variables['retazos_planchas'] = $this->planchas->mostrar_cantidades($codigo, $ordenar);
		}
		
		if($codigo != '' or $codigo == '' or $codigo != 'ok')
		{
			$codigo = '';
			$Variables['tipo_planchas'] = $this->planchas->buscar_planchas($codigo);
		}
		//Cargamos la vista
		$this->load->view('planchas/consultar_material_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	/**
	 *Funcion para agregar la informacion a la tabla plancha_mensual.
	*/
	public function agregar_datos()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('planchas/planchas_m', 'planchas');
		
		//Llamamos el modelo para poder almacenar los datos.
		$agregar_planchas = $this->planchas->guardar_planchas();
		
		if('ok' == $agregar_planchas)
		{
			//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /planchas/consultar_material/index/ok/msj');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{
			//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /planchas/consultar_material/index');
			//Evitamos que se continue ejecutando el script
			exit();
		}	
	}
	
}

/* Fin del archivo */