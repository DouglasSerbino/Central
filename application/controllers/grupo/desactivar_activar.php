<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desactivar_Activar extends CI_Controller {
	
	public function desactivar_grp($Id_grupo)
	{
		/*
		 *Definimos que departamentos tendran acceso a desacticar o activar un grupo.
		*/
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes no deberan tener acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que desactiva los grupos
		$this->load->model('grupo/desactivar_activar_m', 'desac_m');
		
		//Llamamos el modelo para desactivar los grupos.
		$desactivar_grupos= $this->desac_m->desactivar_grupos($Id_grupo);
		
		//Si el grupo se desactivo con exito
		//Enviamos a la pagina listado para mostrar los grupos
		header('location: /grupo/listado');
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
	
	
	public function activar_grp($Id_grupo)
	{
		//Determinamos los departamentos que tendran acceso a activar un grupo
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes no deberan tener acceso a sta información.
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que activa los grupos
		$this->load->model('grupo/desactivar_activar_m', 'act_m');
		
		//Llamamos el modelo para desactivar los grupos.
		$desactivar_grupos= $this->act_m->activar_grupos($Id_grupo);
		
		//Si el grupo se desactivo con exito
		//Enviamos a la pagina listado para mostrar los grupos
		header('location: /grupo/listado');
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
}