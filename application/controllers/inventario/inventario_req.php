<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_req extends CI_Controller {
	
	/**
	 *Permite Agregar los materiales necesarios a la base de datos.
	 *@param string $Codigo; Codigo de material que se quiere modificar.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'El Proveedor fue agregado exitosamente';
		}
		elseif($Mensaje == 'error')
		{
			$Mensaje = 'Ha ocurrido un error al intentar almacenar la informacion.';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Requisar Material',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/inventario_req_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	//Funcion que servira para almacenar nuevos Proveedores.
	public function agregar_proveedor()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables		
		$Proveedor = $this->seguridad_m->mysql_seguro(
			$this->input->post('proveedor')
		);
		
		//Carga del modelo que nos permite agregar la informacion
		$this->load->model('inventario/inventario_proveedores_m', 'inven_prove');
			
		//Llamamos la funcion para poder almacenarla informacion.
		$agregar_proveedor = $this->inven_prove->agregar_proveedor($Proveedor);
		
		if('ok' == $agregar_proveedor)
		{//Si se agrego el proveedor con exito.
			header('location: /inventario/agregar_prov/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{
			//Ocurrio un error al intentar agregar los datos.
			header('location: /inventario/agregar_prov/index/err');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
	
	//Funcion para buscar los materiales
	public function buscar_mat($valor)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables		
		$Valor = $this->seguridad_m->mysql_seguro($valor);
		
		//Carga del modelo que nos mostrar la informacion.
		$this->load->model('inventario/inventario_req_m', 'inven_req');
			
		
		$Buscar_material = $this->inven_req->buscar_material($Valor);
	}
	
	//Funcion para verificar si los materiales tienen existencias.
	public function buscar_exist($valor)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables		
		$Valor = $this->seguridad_m->mysql_seguro($valor);
		
		//Carga del modelo que nos mostrar la informacion.
		$this->load->model('inventario/inventario_req_m', 'inven_req');
			
		
		$Buscar_material = $this->inven_req->buscar_existencia($Valor);
	}
	
	//Funcion para mostrar la informacion de los materiales.
	public function mostrar_material($valor)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables		
		$Valor = $this->seguridad_m->mysql_seguro($valor);
		
		//Carga del modelo que nos mostrar la informacion.
		$this->load->model('inventario/inventario_req_m', 'inven_req');
			
		
		$Buscar_material = $this->inven_req->mostrar_materiales($Valor);
	}
	
	//Funcion que servira para realizar la requisicion.
	public function requisar_material()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		for($i_m_t = 0; $i_m_t < 12; $i_m_t++)
		{
			
			//Limpieza de variables		
			$Codigo_material_[] = $this->seguridad_m->mysql_seguro(
				$this->input->post('codigo_material_'.$i_m_t)
			);
			
			$Nombre_material_[] = $this->seguridad_m->mysql_seguro(
				$this->input->post('nombre_material_'.$i_m_t)
			);
			
			$Id_material_[] = $this->seguridad_m->mysql_seguro(
				$this->input->post('id_material_'.$i_m_t)
			);
			
			$Cantidad_material_[] = $this->seguridad_m->mysql_seguro(
				$this->input->post('cantidad_material_'.$i_m_t)
			);
		}
		//Carga del modelo que nos permitira realizar la requisicion.
		$this->load->model('inventario/inventario_req_m', 'inven_req');
		
		$Buscar_material = $this->inven_req->requisar_materiales($Codigo_material_,
																														$Nombre_material_,
																														$Id_material_,
																														$Cantidad_material_);

			header('location: /inventario/inventario_req_imp/index/'.$Buscar_material);
			//Evitamos que se continue ejecutando el script
			exit();
	}
}
/* Fin del archivo */