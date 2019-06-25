<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infografia extends CI_Controller {
	
	
	public function index()
	{
		
		header('location: /reportes/infografia/puestos');
		exit();

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' =>'');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		$Dpto_Mostrar = 'Arte';
		$Anho = date('Y');
		$Mes = date('m');
		$ICliente = 'todos';
		
		if('' != $this->input->post('mes'))
		{
			$Anho = $this->seguridad_m->mysql_seguro(
				$this->input->post('anho')
			);
			$Mes = $this->seguridad_m->mysql_seguro(
				$this->input->post('mes')
			);
			$Dpto_Mostrar = $this->seguridad_m->mysql_seguro(
				$this->input->post('dpto_mostrar')
			);
			
			$ICliente = $this->seguridad_m->mysql_seguro(
				$this->input->post('icli')
			);
		}
		
		$Variables = array(
			'Titulo_Pagina' => 'Infograf&iacute;as',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes,
			'Dpto_Mostrar' => $Dpto_Mostrar,
			'ICliente' => $ICliente
		);
		
		
		$Variables['Meses'] = array(
			'01' => 'Enero',
			'02' => 'Febrero',
			'03' => 'Marzo',
			'04' => 'Abril',
			'05' => 'Mayo',
			'06' => 'Junio',
			'07' => 'Julio',
			'08' => 'Agosto',
			'09' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre'
		);

		$cadena = $this->generar_cache_m->quitar_tildes($Dpto_Mostrar);
		$pagina_cache = 'Infografia_'.$Anho.'_'.$Mes.'_'.$cadena.'_g'.$this->session->userdata('id_grupo');
		
		$Variables['Cache'] = $this->generar_cache_m->validar_cache($pagina_cache, $Anho, $Mes);
		
		if($Variables['Cache']['base_datos'] == 'si')
		{
			//Listado de los usuarios
			$this->load->model('usuarios/listado_usuario_m', 'lusus');
			$Variables['Usuarios'] = $this->lusus->departamento_usuario('', false);
			
			//Cuantos trabajos procesaron estos usuarios?
			$this->load->model('carga/trabajos_usuario_m', 'trab_usu');
			$Variables['Trabajos'] = $this->trab_usu->total_trabajos($Anho, $Mes);
			
			$Variables['Rechazos'] = $this->trab_usu->total_rechazos($Anho, $Mes, $Variables['Trabajos']);
			
			$Variables['TUtilizado'] = $this->trab_usu->tiempo_utili($Anho, $Mes);
			
			if('Planchas Fotopol&iacute;meras' == $Dpto_Mostrar)
			{
				//Informacion para crear el grafico para planchas.
				$this->load->model('planchas/reporte_planchas_m', 'planchas');
				$Variables['Planchas'] = $this->planchas->RepPlanchas($Anho, $Mes, "Mensual", $ICliente);
				$Variables['PlanchasAn'] = $this->planchas->RepPlanchas($Anho, $Mes, "Anual", $ICliente);
				$Variables['ClientesPlanchas'] = $this->planchas->ClientesPlanchas($Anho, $Mes);
			}

			//Total de horas por departamento
			$this->load->model('extras/extras_m', 'extras');
			$Variables['Extras'] = $this->extras->horas_dpto($Dpto_Mostrar, $Anho, $Mes);
			
			
			//El mes posee 133 horas habiles (un 70% del real) y eso por 60 minutos = 
			$Variables['THabil'] = 7980;
			
		}

		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista.
		$this->load->view('reportes/infografia_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
	}
	
	
	
	
	public function puestos($Id_Usuario = 0, $Anho = '', $Mes = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' =>'');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		$Id_Usuario += 0;
		
		$Anho = $this->seguridad_m->mysql_seguro($Anho);
		$Mes = $this->seguridad_m->mysql_seguro($Mes);
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Resumen por Puesto',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes,
			'Id_Usuario' => $Id_Usuario
		);
		
		
		$Variables['Meses'] = array(
			'01' => 'Enero',
			'02' => 'Febrero',
			'03' => 'Marzo',
			'04' => 'Abril',
			'05' => 'Mayo',
			'06' => 'Junio',
			'07' => 'Julio',
			'08' => 'Agosto',
			'09' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre'
		);
		

		$this->load->model('usuarios/listado_usuario_m', 'lusus');
		$Variables['Usuarios'] = $this->lusus->departamento_usuario();

		
		if(0 < $Id_Usuario)
		{
			//Trabajos procesados
			$this->load->model('carga/trabajos_usuario_m', 'trab_usu');
			//$Variables['Trabajos'] = $this->trab_usu->total_trabajos($Anho, $Mes, $Id_Usuario);
			
			//$Variables['Rechazos'] = $this->trab_usu->total_rechazos($Anho, $Mes, $Variables['Trabajos'], $Id_Usuario);
			
			$Variables['TUtilizado'] = $this->trab_usu->tiempo_utili($Anho, $Mes, $Id_Usuario);
			
			
			$this->load->model('usuarios/informacion_usuario_m', 'info_u');
			//Captura de la informacion del usuario.
			$Variables['Usuario'] = $this->info_u->datos($Id_Usuario, true);
			
			
			//Total de horas extras
			$this->load->model('extras/extras_m', 'extras');
			$Variables['Extras'] = $this->extras->horas_usuario($Id_Usuario, $Anho, $Mes);
			
			
			//Pedidos procesados en el anho
			$this->load->model('pedidos/pedido_usuario_m', 'peus');
			$Variables['Trab_Anual'] = $this->peus->pedidos_anuales($Id_Usuario, $Anho);
		}
		
		//print_r($Variables);
		
		
		//El mes posee 149 horas habiles (un 85% del real) y eso por 60 minutos = 
		$Variables['THabil'] = 8940;
		
		//print_r($Variables);
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Cargamos la vista.
		$this->load->view('reportes/infografia_puesto_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */