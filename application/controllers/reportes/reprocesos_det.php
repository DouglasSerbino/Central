<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reprocesos_det extends CI_Controller {

	public function index($mes = '', $anho = '', $id_cliente = '', $razon_reproceso = '')
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion
		if($mes != 'anual')
		{
			$mes += 0;
			$anho += 0;
			
			if(0 == $anho or $mes == 0 or $id_cliente == '')
			{
				show_404();
				exit();
			}
		}		
		
		$Variables = array(
			'Titulo_Pagina' => 'REPORTE DE REPROCESOS',
			'Mensaje' => ''
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
			$pagina_cache = 'reproc_det_'.$anho.'_'.$mes.'_cli'.$id_cliente.'reprodeta'.$razon_reproceso.'_g'.$this->session->userdata('id_grupo');
		
			$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $anho, $mes);
		
			if($Variables['Cache']['base_datos'] == 'si')
			{
				$Meses = array(
									'01'=> 'Enero',
									'02'=> 'Febrero',
									'03'=> 'Marzo',
									'04'=> 'Abril',
									'05'=> 'Mayo',
									'06'=> 'Junio',
									'07'=> 'Julio',
									'08'=> 'Agosto',
									'09'=> 'Septiembre',
									'10'=> 'Octubre',
									'11'=> 'Noviembre',
									'12'=> 'Diciembre');
				
				
				$Variables['Meses'] = $Meses;
				//Cargamos el modelo encargado de mostrar los proceso finalizados.
				$this->load->model('reportes/reprocesos_det_m', 'reprocesos');
				$pedidos = $this->reprocesos->pedidos_reprocesos($id_cliente, $mes, $anho, $razon_reproceso);
				$Variables['pedidos_grafico'] = $this->reprocesos->pedidos_reprocesos($id_cliente, 'anual', $anho, $razon_reproceso);
				$Variables['informacion_pedidos'] = $pedidos;
				$Variables['informacion_general'] = $this->reprocesos->info_general($pedidos);
				$Variables['informacion_materiales'] = $this->reprocesos->info_materiales($pedidos);
				$Variables['clientes'] = $this->reprocesos->clientes($id_cliente);
				$Variables['tipo_reporte'] = $mes;
			}
		
		//Cargamos la vista.
		$this->load->view('/reportes/reprocesos_det_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	
	
	public function agregar_datos()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		
		$Id_grupo = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_grupo')
		);
        
		$Codigo_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo')
		);
		
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		
        $Contacto = $this->seguridad_m->mysql_seguro(
			$this->input->post('contacto')
		);
        
        $Telefono = $this->seguridad_m->mysql_seguro(
			$this->input->post('telefono')
		);
		
		$Correo= $this->seguridad_m->mysql_seguro(
			$this->input->post('correo')
		);
		
		$Vendedor= $this->seguridad_m->mysql_seguro(
			$this->input->post('vendedor')
		);
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('clientes/agregar_m', 'agre_m');
		
		//Llamamos el modelo para poder almacenar los datos.
		$agregar_cliente = $this->agre_m->guardar_datos(
			$Id_grupo,
			$Tipo,
			$Codigo_cliente,
			$Nombre,
			$Contacto,
			$Telefono,
			$Correo,
			$Vendedor
		);
		
		
		if('ok' == $agregar_cliente)
		{//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /clientes/agregar/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('existe' == $agregar_cliente)
		{//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			// ex es la abreviatura de que existe el cliente.
			header('location: /clientes/agregar/index/ex');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /clientes/agregar/index/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}	
	}
}