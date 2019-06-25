<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Planchas extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($codigo_pla = 0, $var = '')
	{
		
		$this->ver_sesion_m->no_clientes();

		$Mensaje = '';
		if($var == 'gua')
		{
			$Mensaje = 'La informaci&oacute;n ha sido ingresada con &eacute;xito.';
		}
		elseif($var == 'mod')
		{
			$Mensaje = 'La informaci&oacute;n ha sido modificada con &eacute;xito.';
		}
		elseif($var == 'existe')
		{
			$Mensaje = 'Error: El c&oacute;digo digitado ya existe en el sistema. Verifique el codigo a ingresar.';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'TIPOS DE PLANCHA',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Modelo que realiza la busqueda de las planchas.
		$this->load->model('planchas/planchas_m', 'planchas');
		
		$Variables['codigo_pla'] = $codigo_pla;
		
		//Asignamos el valor si es modificacion o agregar.
		if($codigo_pla <> "0")
		{
			$modifiqueme = "si";
			//Llamamos la funcion para mostrar la informacion de la plancha seleccionada.
			$Variables['plancha_especifica'] = $this->planchas->buscar_planchas($codigo_pla);
		}
		else
		{
			$modifiqueme = "no";
			$codigo_pla = "";
			$Variables['altura'] = "";
			$Variables['ubicacion'] = "";
			$Variables['tipo'] = "";
			$Variables['ana'] = "";
			$Variables['digi'] = "";
			$Variables['btnmodificar'] = "Agregar";
			$Variables['hdn'] = "\n";
			$Variables['id_plancha'] = '';
		}
		
		$Variables['modifiqueme'] = $modifiqueme;
		
		//Llamamos la funcion para mostrar todas las planchas.
			$codigo = '';
			$Variables['tipo_planchas'] = $this->planchas->buscar_planchas($codigo);
		
		//Cargamos la vista
		$this->load->view('planchas/planchas_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');

	}
	
	/**
	 *Funcion para agregar la informacion a la tabla plancha_mensual.
	*/
	public function planchas_modagr()
	{		
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('planchas/planchas_m', 'planchas');
		$codigo_pla = $this->seguridad_m->mysql_seguro($this->input->post('codigo_pla'));
		$codigo = $this->seguridad_m->mysql_seguro($this->input->post('codigo'));
		$altura = $this->seguridad_m->mysql_seguro($this->input->post('altura'));
		$ubicacion = $this->seguridad_m->mysql_seguro($this->input->post('ubicacion'));
		$tipo = $this->seguridad_m->mysql_seguro($this->input->post('tipo'));
		$id_plancha = $this->seguridad_m->mysql_seguro($this->input->post('id_pla'));
		$modi = $this->seguridad_m->mysql_seguro($this->input->post('modifiqueme'));
		
		if(isset($_POST['cod_viejo']))
		{
			$codigo_viejo = $_POST['cod_viejo'];
		}
	
		
		//Llamamos el modelo para poder almacenar o modificar los datos.
		$agrmodi_planchas = $this->planchas->guardar_modificar_planchas($codigo_pla, $codigo, $modi, $altura, $ubicacion, $tipo, $id_plancha);
		
		$cod = 0;
		if('ok_gua' == $agrmodi_planchas)
		{//Si la plancha se guardo con exito.
			//Enviamos a la pagina de agregar por si se quiere agregar
			//una nueva plancha..
			header('location: /planchas/planchas/index/'.$cod.'/gua');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('existe' == $agrmodi_planchas)
		{//Si el codigo de plancha ingresado ya existe.
			//Enviamos a la pagina donde estaba para corregir el error.
			header('location: /planchas/planchas/index/'.$cod.'/existe');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		elseif('ok_mod' == $agrmodi_planchas)
		{//Si la informacion de la plancha se modifico exitosamente.
			header('location: /planchas/planchas/index/'.$cod.'/mod');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar o modificar las planchas.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /planchas/planchas/index/'.$cod.'/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}	
	}


	public function eliminar($Cod_Plancha = '')
	{

		$Cod_Plancha = $this->seguridad_m->mysql_seguro($Cod_Plancha);

		$Consulta = '
			delete from planchas
			where cod_plancha = "'.$Cod_Plancha.'"
		';
		$this->db->query($Consulta);

		header('location: /planchas/planchas');
		exit();

	}
	
}

/* Fin del archivo */