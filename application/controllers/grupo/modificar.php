<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Modificar extends CI_Controller {

	public function datos($Abrev)
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Grupo',
			'Mensaje' => ''
		);
		
		
		//Listado de los clientes
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
			'id_cliente, codigo_cliente, nombre'
		);
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Obtencion de la informacion perteneciente al grupo seleccionado
		$this->load->model('grupo/informacion_grupo_m', 'info_grupo');
		
		//Limpiamos la variable.
		$Abreviatura = $this->seguridad_m->mysql_seguro($Abrev);
		
		//La pasamos la varible limpia para ejecutar la consulta
		$Variables_Vista['Grupos'] = $this->info_grupo->grupo($Abreviatura);
		
		
		
		//Cargamos la vista.
		$this->load->view('/grupo/modificar_v',$Variables_Vista);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	public function modificar_datos()
	{
		//Determinar que departamentos tendran acceso a la modificacion de los grupos.
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes no pueden ni deben de modificar esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_grupo = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_grupo')
		);
		
		$Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre')
		);
		$Abrev = $this->seguridad_m->mysql_seguro(
			$this->input->post('abrev')
		);
		
		$Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		$Id_Cliente = $this->input->post('cliente') + 0;
		
		
		//Carga del modelo que nos permite modificar la informacion
		$this->load->model('grupo/modificar_m', 'mod_m');
		
		//Llamamos el modelo para poder modificar los datos.
		$agregar_grupos= $this->mod_m->modificar_sql(
			$Id_grupo,
			$Nombre,
			$Abrev,
			$Tipo,
			$Id_Cliente
		);
		
		//Regresamos a la pagina para de listado
		//para inciar el proceso nuevamente.
		header('location: /grupo/listado');
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
	
	
	
	/**
	 *Realizar cambio de id_grupo en la sesion para el usuario actual.
	*/
	function cambia_grupo($Id_Nuevo_Grupo)
	{
		/*Departamentos conm permiso de cambiar el grupo*/
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes no tienen acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Nuevo_Grupo += 0;
		
		$Var['Ajax'] = '';
		
		if(0 < $Id_Nuevo_Grupo)
		{
			$Var['Ajax'] = $this->ver_sesion_m->cambia_grupo($Id_Nuevo_Grupo);
		}
		
		$this->load->view('ajax_v', $Var);
		
	}
	
}