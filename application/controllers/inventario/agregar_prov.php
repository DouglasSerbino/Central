<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_prov extends CI_Controller {
	
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
		//Listado de Proveedores
		$this->load->model('inventario/inventario_proveedores_m', 'proveedor');
		$Mostrar_Prov = $this->proveedor->mostrar_proveedor();	
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Proveedor',
			'Mensaje' => $Mensaje,
			'Mostrar_proveedor' => $Mostrar_Prov
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/agregar_prov_v', $Variables);
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

	public function eliminar($Id_Proveedor = 0)
	{
		$Id_Proveedor += 0;

		if(0 < $Id_Proveedor)
		{
			$Consulta = '
				delete from inventario_proveedor where id_inventario_proveedor = "'.$Id_Proveedor.'"
			';
			$this->db->query($Consulta);
		}

		header('location: /inventario/agregar_prov');
	}

}

/* Fin del archivo */