<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto extends CI_Controller {

	public function modificarProducto(){

		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);

		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		$id_producto = $this->seguridad_m->mysql_seguro($this->input->post('id_producto'));
		$descripcion_producto = $this->seguridad_m->mysql_seguro($this->input->post('descripcion_producto'));

		$this->load->model('productos/prod_cliente_m', 'producto');
		$modificarProducto = $this->producto->modificar_producto($id_producto, $descripcion_producto);
		return '';
	}

	public function agregarProductoNuevo(){

		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);

		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		$descripcion_producto = $this->seguridad_m->mysql_seguro($this->input->post('descripcion_producto'));

		$this->load->model('productos/prod_cliente_m', 'producto');
		$modificarProducto = $this->producto->ingresar_producto($descripcion_producto);

		//add the header here
	    header('Content-Type: application/json');
	    echo json_encode( 'Producto Guardado Exitosamente' );
	}
}