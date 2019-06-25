<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_pre extends CI_Controller {
	
	/**
	 *Podremos mostrar las notas de envio.
	*/
	public function index($cajas='', $id_cliente='',$id_pedido='')
	{
		//Validamos que los clientes no puedan acceder a esta pagina.
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion
		$cajas += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $cajas or '' == $id_cliente or '' == $id_pedido)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
			//Asignamos los pedidos recibidos a un array.
			$ped = explode('-',$id_pedido);
			//Exploramos el array
			foreach($ped as $mandar)
			{
				//Si hay valores en el array asignamos su valor a un nuevo array.
				if('' != $mandar)
				{
					$pedidos[] = $mandar;
				}
			}

			//Cargamos el modelo para mostrar el cliente.
			$this->load->model('notas/despacho_m', 'despacho');
			$clientes = $this->despacho->clientes($id_cliente);
			
			//Cargamos el modelo para mostrar los materiales usados en este pedido.
			$this->load->model('notas/nota_envio_m', 'nota_m');
			$especificacion = $this->nota_m->mostrar_especificacion($pedidos);
			
			//Variables necesarias en el encabezado
			$Variables = array(
				'id_cliente' => $id_cliente,
				'Clientes' => $clientes,
				'especificacion' => $especificacion,
				'pedidos' => $pedidos
			);
			
			//Se carga la vista.
			$this->load->view('notas/nota_pre_v', $Variables);
	}
	
	//Funcion para almacenar las notas de envio.
	function nota_sql()
	{
			//Cargamos el modelo para poder almacenar la informacion.
			$this->load->model('notas/nota_pre_sql_m', 'nota_pre');
			//Llamamos la funcion encargada de alamacenar la informacion.
			$notas= $this->nota_pre->notas_envio_sql();
			
			if(0 != $notas)
			{
				//Lo dirigimos a la pagina para imprimir...
				header('location: /notas/nota_ver/index/'.$notas);
				//Evitamos que se continue ejecutando el script
				exit();
			}
			else
			{//Ocurrio un error al intentar ingresar.
				header('location: /notas/despacho');
				//Evitamos que se continue ejecutando el script
				exit();
			}
	}
}

/* Fin del archivo */