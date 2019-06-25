<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_info_material extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function index($Id_inventario_material = '')
	{
		
		$this->ver_sesion_m->no_clientes();

		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Agregar informaci&oacute;n del Material',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['Id_inventario_material'] = $Id_inventario_material;
		
		//Informacion del material.
		$this->load->model('inventario/informacion_material_m', 'mos_material');
		$Variables['Mostrar_materiales'] = $this->mos_material->mostrar_materiales($Id_inventario_material);	
		
		//Listado de Proveedores
		$this->load->model('inventario/inventario_proveedores_m', 'proveedor');
		$Variables['Mostrar_Proveedor'] = $this->proveedor->mostrar_proveedor();
		
		//Modelo que realiza la busqueda de los tipos de planchas..
		$this->load->model('planchas/planchas_m', 'planchas');
		$Variables['plancha_tipo'] = $this->planchas->plancha_tipo();
		
		//Modelo que permite mostrar los espesores de planchas.
		$codigo = '';
		$this->load->model('planchas/planchas_m', 'planchas');
		$Variables['tipo_planchas'] = $this->planchas->buscar_planchas($codigo);
		
		$this->load->view('herramientas_sis/agregar_info_material_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	public function agregar_info()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_inventario_material = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_inventario_material')
		);
		
		$proveedor = $this->seguridad_m->mysql_seguro(
			$this->input->post('proveedor')
		);
		
		$cod_plancha = $this->seguridad_m->mysql_seguro(
			$this->input->post('cod_plancha')
		);
		
		$numero_individual = $this->seguridad_m->mysql_seguro(
			$this->input->post('numero_individual')
		);
		
		$tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		
		$numero_cajas = $this->seguridad_m->mysql_seguro(
			$this->input->post('numero_cajas')
		);
		
		$plancha_tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('plancha_tipo')
		);
		
		$tamanho = $this->seguridad_m->mysql_seguro(
			$this->input->post('tamanho')
		);
		
		//Carga del modelo que nos permite modificar la informacion
		$this->load->model('herramientas_sis/agregar_info_material_m', 'agregar_info');
		
		
		//Llamamos el modelo para poder modificar los datos.
		$Agregar_info = $this->agregar_info->agregar_informacion($Id_inventario_material, $proveedor, $cod_plancha,
																														$numero_individual, $numero_cajas, $plancha_tipo, $tamanho, $tipo);
		
		if('ok' == $Agregar_info)
		{//Si se modifico la informacion con exito.
			//lo redirigimos al listado de materiales por si se quiere modificar otro.
			header('location: /inventario/existencias/');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar modificar los datos.
			//Regresamos a la pagina para mostrar el listado
			header('location: /inventario/existencias');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
	
}

/* Fin del archivo */