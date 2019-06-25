<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingresar extends CI_Controller {
	
	/**
	 *Pantalla de inicio de sesion para la captura del usuario y el password.
	 *@param string $Grupo: Variable que indica a que grupo de la corporacion pertenece el usuario que solicita ingresar al sistema.
	 *@param string $Error: Valor que puede estar presente si el usuario cometio un error en la informacion proporcionada en el ingreso.
	 *@return Nada.
	*/
	public function grupo($Grupo ='', $Error = '')
	{
		
		//Si no se especifica el grupo al que se desea ingresar, debo mostrar un
		//mensaje de error.
		if('' == $Grupo)
		{
			show_404();
			//Evitamos que se siga ejecutando el script
			exit();
		}
		
		//Limpieza de variables
		$Grupo = $this->seguridad_m->mysql_seguro($Grupo);
		$Error = $this->seguridad_m->mysql_seguro($Error);
		
		
		//Carga del modelo que nos da informacion del grupo que se le solicite.
		$this->load->model('grupo/informacion_grupo_m', 'info_g');
		//info_g = Informacion del Grupo
		//Hago una busqueda para obtener la informacion del grupo que se ha recibido
		//por la url, si existe obtendre un array de valores sino la palabra 'error'.
		$Informacion = $this->info_g->grupo($Grupo);
		
		//En caso que el grupo indicado no exista.
		if('error' == $Informacion)
		{
			//mensaje de error.
			show_404();
			//Evitamos que se siga ejecutando el script
			exit();
		}
		
		
		//Si este grupo no esta activo
		if('n' == $Informacion['activo'])
		{
			//mensaje de error.
			show_404();
			//Evitamos que se siga ejecutando el script
			exit();
		}
		
		//Variables a utilizar en la vista.
		$Variables = array(
			'Grupo' => $Grupo,
			'Error' => $Error,
			'Informacion' => $Informacion
		);
		
		//Pagina con el formulario de ingreso al sistema.
		$this->load->view('ingresar_v', $Variables);
	}
	
	
	public function validar($Grupo ='')
	{
		
		//Si no se especifica el grupo al que se desea ingresar, debo mostrar un
		if('' == $Grupo)
		{
			//mensaje de error.
			show_404();
			//Evitamos que se siga ejecutando el script
			exit();
		}
		
		
		//Limpiamos las variables para evitar inyecciones
		$Grupo = $this->seguridad_m->mysql_seguro($Grupo);
		$Usuario = $this->seguridad_m->mysql_seguro(
			$this->input->post('usuario')
		);
		$Password = $this->seguridad_m->mysql_seguro(
			$this->input->post('password')
		);
		
		
		//Carga del modelo que realiza la validacion
		$this->load->model('validar_m', 'validar');
		
		//Se solicita al modelo que realice la validacion con los datos previamente
		//validados y limpiados.
		//El resultado se guarda en una variable para ser trabajada luego.
		$Validacion = $this->validar->validar_usuario($Grupo, $Usuario, $Password);
		
		if('ok' == $Validacion)
		{//Si el usuario es valido
			
			
			//Cargamos el modelo que crea su menu de usuario
			$this->load->model('menu/render_m', 'render');
			
			//Se realiza el "Renderizado" del menu de usuario
			$this->render->usuario();
			
			
			
			//Enviamos a la pagina principal del sistema
			header('location: /principal');
			
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//El usuario no fue encontrado
			
			//Posiblemente sea un cliente.
			$Validacion = $this->validar->validar_cliente($Grupo, $Usuario, $Password);
			
			if('ok' == $Validacion)
			{
				
				//Cargamos el modelo que crea su menu de usuario
				$this->load->model('menu/render_m', 'render');
				
				//Se realiza el "Renderizado" del menu de usuario
				$this->render->cliente();
				
				
				//Enviamos a la pagina principal del sistema
				header('location: /principal');
			}
			else
			{
				//Regresamos a la pagina de ingreso
				header('location: /ingresar/grupo/'.$Grupo);
				
				//Evitamos que se continue ejecutando el script
				exit();
			}
			
		}
		
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */