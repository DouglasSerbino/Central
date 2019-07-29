<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_clientes extends CI_Controller {
	
	/**
	 *Pagina que muestra la informacion de un cliente en especifico
	 *@param string $Id_cliente.
	 *@return nada.
	*/
	
	public function mostrar_datos($Id_cliente)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta informacion.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no tendran acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		
		$Id_cliente += 0;

		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificaci&oacuten de Clientes',
			'Mensaje' => ''
		);
		
		//Modelo que realiza la busqueda de los Menus.
		$this->load->model('clientes/busquedad_clientes_m', 'busqu_cliente');
		//busqu_cliente = Buscar clientes
		

		//capturar la informacion del cliente
		//Modelo que realiza la busqueda de los Clientes.
		$this->load->model('clientes/busquedad_clientes_m', 'busqu_cliente');
		$Resultado = $this->busqu_cliente->busquedad_especifica($Id_cliente);

		$Variables['general'] = array();
		if(1 == count($Resultado))
		{
			$Variables['general'] = $Resultado[0];
		}
		

		$Variables = $Variables + $this->busqu_cliente->cliente_caracteristicas($Id_cliente);
		
		$Producto = $Variables['producto'];
		$Variables['producto'] = array();
		foreach ($Producto as $value) {
			$Variables['producto'][$value['id_producto']] = $value;
		}

		
		$this->load->model('clientes/modificar_clientes_m', 'mod_clientes_m');
		
		//Llamamos el modelo para poder modificar los datos.
		$Variables['productos'] = $this->mod_clientes_m->productos();
		
		foreach($Variables['productos'] as $Index => $Datos)
		{
			if('n' == $Datos['activo'])
			{
				unset($Variables['productos'][$Index]);
			}
		}

		

		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();



		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Pagina que muestra la informacion de un cliente.
		$this->load->view('clientes/modificar_clientes_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');	
	}
	
	
	public function modificar_datos()
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta informacion.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no tendran acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		
		//Info General
		$Id_Cliente = $this->input->post('icliente');
		$Id_Cliente += 0;
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		$Direccion = $this->seguridad_m->mysql_seguro(
			$this->input->post('direccion')
		);
		$NIT = $this->seguridad_m->mysql_seguro(
			$this->input->post('nit')
		);
		$Web = $this->seguridad_m->mysql_seguro(
			$this->input->post('web')
		);
		$Credito = $this->seguridad_m->mysql_seguro(
			$this->input->post('credito')
		);
		$CPais = $this->seguridad_m->mysql_seguro(
			$this->input->post('cpais')
		);

		//Contactos
		$Contacto = $this->input->post('clie_contacto');
		$Cargo = $this->input->post('clie_cargo');
		$Email = $this->input->post('clie_email');
		$TOficina = $this->input->post('clie_tofic');
		$TDirecto = $this->input->post('clie_tdire');
		$TCelular = $this->input->post('clie_tcelu');
		$Contactos_v = array();
		
		if(is_array($Contacto))
		{
			foreach($Contacto as $Index => $Data)
			{

				if('' == $this->seguridad_m->mysql_seguro($Contacto[$Index]))
				{
					continue;
				}

				$Contactos_v[$Index]['nomb'] = $this->seguridad_m->mysql_seguro($Contacto[$Index]);
				$Contactos_v[$Index]['carg'] = $this->seguridad_m->mysql_seguro($Cargo[$Index]);
				$Contactos_v[$Index]['emai'] = $this->seguridad_m->mysql_seguro($Email[$Index]);
				$Contactos_v[$Index]['tofi'] = $this->seguridad_m->mysql_seguro($TOficina[$Index]);
				$Contactos_v[$Index]['tdir'] = $this->seguridad_m->mysql_seguro($TDirecto[$Index]);
				$Contactos_v[$Index]['tcel'] = $this->seguridad_m->mysql_seguro($TCelular[$Index]);

			}
		}


		//Maquinas
		$Maquina = $this->input->post('maquina');
		$Colores = $this->input->post('colores');
		$Maquinas_v = array();
		
		if(is_array($Maquina))
		{
			foreach($Maquina as $Index => $Data)
			{

				if('' == $this->seguridad_m->mysql_seguro($Maquina[$Index]))
				{
					continue;
				}

				$Maquinas_v[$Index]['maqu'] = $this->seguridad_m->mysql_seguro($Maquina[$Index]);
				$Maquinas_v[$Index]['colo'] = $this->seguridad_m->mysql_seguro($Colores[$Index]);

			}
		}


		//Placas
		$Altura = $this->input->post('altura');
		$Lineaje = $this->input->post('lineaje');
		$Marca = $this->input->post('marca');
		$Planchas_v = array();
		
		if(is_array($Altura))
		{
			foreach($Altura as $Index => $Data)
			{

				if('' == $this->seguridad_m->mysql_seguro($Altura[$Index]))
				{
					continue;
				}

				$Planchas_v[$Index]['altu'] = $this->seguridad_m->mysql_seguro($Altura[$Index]);
				$Planchas_v[$Index]['line'] = $this->seguridad_m->mysql_seguro($Lineaje[$Index]);
				$Planchas_v[$Index]['marc'] = $this->seguridad_m->mysql_seguro($Marca[$Index]);

			}
		}

		
		//Aniloxes
		$Anilox = $this->input->post('anilox');
		$BCM = $this->input->post('bcm');
		$Ani_Cantidad = $this->input->post('ani_cantidad');
		$Aniloxes_v = array();
		
		if(is_array($Anilox))
		{
			foreach($Anilox as $Index => $Data)
			{

				if('' == $this->seguridad_m->mysql_seguro($Anilox[$Index]))
				{
					continue;
				}

				$Aniloxes_v[$Index]['ani'] = $this->seguridad_m->mysql_seguro($Anilox[$Index]);
				$Aniloxes_v[$Index]['bcm'] = $this->seguridad_m->mysql_seguro($BCM[$Index]);
				$Aniloxes_v[$Index]['can'] = $this->seguridad_m->mysql_seguro($Ani_Cantidad[$Index]);

			}
		}

		
		//Productos
		$Prod_Precio = $this->input->post('clie_prod_precio');
		$Prod_Id = $this->input->post('hid_clie_prod');
		$Prod_Concepto = $this->input->post('clie_concepto');
		$Productos_v = array();
		
		if(is_array($Prod_Precio))
		{
			foreach($Prod_Precio as $Index => $Data)
			{

				if('on' !== $this->input->post('chk_clie_prod_'.$Prod_Id[$Index]))
				{
					continue;
				}

				$Productos_v[$Index]['pre'] = $this->seguridad_m->mysql_seguro($Prod_Precio[$Index]);
				$Productos_v[$Index]['idp'] = $this->seguridad_m->mysql_seguro($Prod_Id[$Index]);
				$Productos_v[$Index]['con'] = $this->seguridad_m->mysql_seguro($Prod_Concepto[$Index]);

			}
		}
		

		//Carga del modelo que permite ingresar los datos.
		$this->load->model('clientes/modificar_clientes_m', 'modi_m');
		
		//Llamamos el modelo para poder almacenar los datos.
		$modificar_cliente = $this->modi_m->modificar_sql(
			$this->session->userdata('id_grupo'),
			$Id_Cliente,
			$Nombre,
			$Direccion,
			$NIT,
			$Web,
			$Credito,
			$Contactos_v,
			$Maquinas_v,
			$Aniloxes_v,
			$Planchas_v,
			$Productos_v,
			$CPais
		);

		header('location: /clientes/administrar_clientes/index/ok');

	}
	
	public function agregar_productos()
	{
		//Solamente Sistemas podra agregar productos a los clientes.
		/*$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);*/
		//Los clientes no tienen ni deben de tener acceso a este metodo.
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_cliente')
		);
		$Cod_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cod_cliente')
		);
		$Id_Producto = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_producto')
		);
		$Precio = $this->seguridad_m->mysql_seguro(
			$this->input->post('precio')
		);
		$Cantidad = $this->seguridad_m->mysql_seguro(
			$this->input->post('cantidad')
		);
		$Concepto = $this->seguridad_m->mysql_seguro(
			$this->input->post('concepto')
		);
		
		
		
		//Carga del modelo que nos permite modificar la informacion
		$this->load->model('clientes/modificar_clientes_m', 'mod_clientes_m');
		
		//Llamamos el modelo para poder modificar los datos.
		$agregar_producto = $this->mod_clientes_m->agregar_producto($Id_cliente, $Cod_cliente, $Id_Producto, $Precio,$Cantidad,$Concepto);
		
		if('ok' == $agregar_producto)
		{//Si se modifico la informacion con exito.
			//lo redirigimos al listado de grupos por si se quiere modificar otro.
			header('location: /clientes/modificar_clientes/mostrar_datos/'.$Id_cliente);
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar modificar los datos.
			//Regresamos a la pagina para mostrar el listado
			//para inciar el proceso nuevamente.
			header('location: /clientes/administrar_clientes/index/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}

}

/* Fin del archivo */