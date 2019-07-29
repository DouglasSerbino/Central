<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_ped_tran extends CI_Controller {
	
	/**
	 *Muestra el inventario de materiales y sus existencias.
	 *@param string $Codigo;
	 *@param string $Proveedor;
	 *@param string $Cantidad;
	 *@param string $Equipo;
	 *@return nada.
	*/
	public function index($Codigo = '', $orden= '', $tipo = '')
	{
		$this->ver_sesion_m->no_clientes();
		
		//Super validacion
		$Codigo += 0;
		if(0 == $Codigo)
		{
			show_404();
			exit();
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Pedidos en Transito',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		if($tipo != 'mod')
		{
			//Informacion del material
			$this->load->model('herramientas_sis/agregar_ped_tran_m', 'ped_tran');
			$Variables['info_material'] = $this->ped_tran->mostrar_material($Codigo);	
			$this->load->view('herramientas_sis/agregar_ped_tran_v', $Variables);
		}
		else
		{
			$this->load->model('herramientas_sis/modificar_ped_tran_m', 'mod_tran');
			$Variables['info_material'] = $this->mod_tran->mostrar_material($Codigo, $orden);	
			//Super validacion
			$resultado = count($Variables['info_material']);
			$Comprobar = $resultado += 0;
			
			if(0 == $Comprobar)
			{
				show_404();
				exit();
			}
			//Informacion del material
			
			$this->load->view('herramientas_sis/modi_ped_tran_v', $Variables);
		}
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	public function agregar()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Orden = $this->seguridad_m->mysql_seguro(
			$this->input->post('orden')
		);
		
		$Cantidad = $this->seguridad_m->mysql_seguro(
			$this->input->post('cantidad')
		);
		
		$Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		
		$Producto = $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		$Id_inventario_material = $this->seguridad_m->mysql_seguro(
				$this->input->post('id_inventario')
		);
		
		$Detalle = $this->seguridad_m->mysql_seguro(
				$this->input->post('detalle')
		);
		
		$this->load->model('herramientas_sis/agregar_ped_tran_m', 'ped_tran');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		$Agregar = $this->ped_tran->agregar_pedido_tran($Orden, $Cantidad,
																										$Tipo, $Id_inventario_material, $Detalle);
		
		
		if('ok' == $Agregar)
		{//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			
			header('location: /inventario/existencias');
			
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			
			header('location: /inventarios/existencias');
			
			//Evitamos que se continue ejecutando el script
			exit();
		}
		
	}
	
	/**
		Funcion para modificar la informacion de un pedido en transito.
	**/
	public function modificar()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Orden = $this->seguridad_m->mysql_seguro(
			$this->input->post('orden')
		);
		
		$Orden_ant = $this->seguridad_m->mysql_seguro(
			$this->input->post('orden_ant')
		);
		
		$Cantidad = $this->seguridad_m->mysql_seguro(
			$this->input->post('cantidad')
		);
		
		$Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		
		$Producto = $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		$Id_inventario_material = $this->seguridad_m->mysql_seguro(
				$this->input->post('id_inventario')
		);
		
		$Detalle = $this->seguridad_m->mysql_seguro(
				$this->input->post('detalle')
		);
		
		$this->load->model('herramientas_sis/modificar_ped_tran_m', 'mod_tran');
		
		//LLamamos el metodo encargado de la modificacion del proceso.
		$Modificar = $this->mod_tran->modificar_pedido_tran($Orden_ant, $Orden, $Cantidad,
																										$Tipo, $Id_inventario_material, $Detalle);
		
		
		if('ok' == $Modificar)
		{//Si el proceso se guardo con exito
			//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
			header('location: /herramientas_sis/info_transito/index/'.$Id_inventario_material.'/'.$Orden);
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /herramientas_sis/info_transito/index/'.$Id_inventario_material.'/'.$Orden);
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
	
}

/* Fin del archivo */