<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sol_material extends CI_Controller {
	
	
	public function index($Mensaje = '')
	{
		$this->ver_sesion_m->no_clientes();

			if($Mensaje == 'ok')
			{
				$Mensaje = 'Solicitud agregada';
			}
			
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Solicitud de Materiales',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->model('herramientas_sis/sol_material_m', 'solicitud');
		$Variables['MatOperador'] = $this->solicitud->SolMaterialOperador();
		
		$this->load->view('herramientas_sis/sol_material_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
		//Funcion para mostrar la informacion de los materiales.
	public function solicitar_material()
	{
		$info = array();
		$e = 0;
		foreach($_POST as $a => $Datos)
		{
			$info[$e] = $this->seguridad_m->mysql_seguro($Datos);
			$e++;
		}
		
		//Carga del modelo que nos mostrar la informacion.
		$this->load->model('herramientas_sis/sol_material_m', 'solicitud');
		
		$Ingresar = $this->solicitud->guardar_solicitud($info);
		if('ok' == $Ingresar)
		{
			header('location: /herramientas_sis/sol_material/index/ok');
			break;
		}
		else
		{
			header('location: /herramientas_sis/sol_material/index/error');
			break;
		}
	}	
}
?>