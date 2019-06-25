<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bodega_uso extends CI_Controller {
	
	/**
	 *Muestra el inventario de materiales y sus existencias.
	 *@param string $Codigo;
	 *@param string $Proveedor;
	 *@param string $Cantidad;
	 *@param string $Equipo;
	 *@return nada.
	*/
	public function index(
		$Codigo = 'todos',
		$Proveedor = '--',
		$Cantidad = 'todos',
		$Equipo = '--'
	)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Listado de Proveedores
		$this->load->model('inventario/inventario_proveedores_m', 'proveedor');
		$Mostrar_Prov = $this->proveedor->mostrar_proveedor();	
		
		//Informacion de los equipos por areas
		$this->load->model('inventario/inventario_equipos_m', 'inv_equipos');
		$Mostrar_Equi = $this->inv_equipos->mostrar_equipos();
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Bodega de Uso',
			'Mensaje' => '',
			'Mostrar_proveedor' => $Mostrar_Prov,
			'Cod_Proveedor' => $Proveedor,
			'Mostrar_Equipo' => $Mostrar_Equi,
			'Cod_Equipo' => $Equipo,
			'Codigo' => $Codigo,
			'Cantidad' => $Cantidad
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Listado de materiales segun los filtros
		$this->load->model('inventario/bodega_uso_m', 'bodega');
		$Variables['Materiales'] = $this->bodega->listar(
			$Codigo,
			$Proveedor,
			$Cantidad,
			$Equipo
		);
		
		$this->load->view('inventario/bodega_uso_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	
	public function detalle($Id_Inventario_Material, $Anho, $Mes)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$Mes = $this->seguridad_m->mysql_seguro($Mes);
		$Anho += 0;
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Consumo de Material',
			'Mensaje' => '',
			'Id_Inventario_Material' => $Id_Inventario_Material,
			'Mes' => $Mes,
			'Anho' => $Anho
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Listado de materiales segun los filtros
		$this->load->model('inventario/bodega_uso_m', 'bodega');
		$Variables['Notificaciones'] = $this->bodega->detalle(
			$Id_Inventario_Material,
			$Mes,
			$Anho
		);
		
		$this->load->view('inventario/bodega_detalle_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
}

/* Fin del archivo */