<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos todos los menus de usuario y los ordena en
	 *un array asociativo por menu principal y sus submenus.
	 *@param nada.
	 *@return array: Contiene el Menu.
	*/
	function listado()
	{
		
		//Consultamos la base de datos para que nos ofresca los menus.
		$Consulta = '
			select *
			from menu
			order by id_menu_padre asc, id_menu asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Guardare los menus en este array, prestar atencion a como se guardaran
		//jerarquicamente
		$Menu = array();
		foreach($Resultado->result_array() as $Fila)
		{
			
			if('0' == $Fila['id_menu_padre'])
			{//Si es el menu principal
				//Creo un indice con el id_menu el cual a su vez sera un array de elementos
				//Dentro de el se almacenan la etiqueta y el enlace
				$Menu[$Fila['id_menu']]['etiqueta'] = $Fila['etiqueta'];
				$Menu[$Fila['id_menu']]['enlace'] = $Fila['enlace'];
				$Menu[$Fila['id_menu']]['activo'] = $Fila['activo'];
				//Se crea un indice adicional en el cual se guardaran los submenus
				$Menu[$Fila['id_menu']]['submenu'] = array();
			}
			else
			{//Si es el sub_menu voy agregando los elementos bajo el id de su grupo
				//Se crea un indice bajo submenu con el id_menu para identificar a cada
				//uno, guardamos su etiqueta y su enlace.
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['etiqueta'] = $Fila['etiqueta'];
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['enlace'] = $Fila['enlace'];
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['activo'] = $Fila['activo'];
			}
			
		}
		
		//Regreso el array con los grupos encontrados
		return $Menu;
		
		
	}
	
	
	/**
	 *Extrae de la base de datos los menus que tiene un id_menu_padre = 0.
	 *@param nada.
	 *@return array: Listado de los menus encontrados.
	*/
	function padres()
	{
		
		//Extraccion de los menus principales
		$Consulta = '
			select id_menu, etiqueta
			from menu
			where id_menu_padre = 0
			and activo = "s"
			order by id_menu asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con el listado de menus
		return $Resultado->result_array();
		
	}
	
	/**
	 *Buscar los menus de un departamento.
	 *@param string $Id_dpto: Id del departamento.
	 *@return array con los menus encontrados.
	*/
	function menu_departamento($Id_dpto)
	{
		
		//Creamos la consulta
		$Consulta = 'Select * from menu_departamento where id_departamento = "'.$Id_dpto.'"';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Menu_Depto = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Menu_Depto[$Fila['id_menu']] = true;
		}
		
		return $Menu_Depto;
		
	}
	
	/**
	 *Buscar los menus de un usuario.
	 *@param string $Id_Usuario.
	 *@return array con los menus encontrados.
	*/
	function menu_usuario($Id_Usuario)
	{
		
		//Creamos la consulta
		$Consulta = 'Select * from menu_usuario where id_usuario = "'.$Id_Usuario.'"';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Menu_Usuario = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Menu_Usuario[$Fila['id_menu']] = true;
		}
		
		return $Menu_Usuario;
		
	}
	
	/**
	 *Buscar los menus de un usuario.
	 *@param string $Id_Usuario.
	 *@return array con los menus encontrados.
	*/
	function menu_click_tabs($Id_Usuario)
	{
		
		//Creamos la consulta
		$Consulta = 'Select menu.id_menu, etiqueta, enlace, id_menu_padre, menu.activo
								from menu_departamento dpto, menu, usuario usu, departamentos dptos
								where dpto.id_menu = menu.id_menu
								and usu.id_usuario = "'.$Id_Usuario.'"
								and dpto.id_departamento = dptos.id_dpto
								and usu.id_dpto = dptos.id_dpto
								and menu.activo = "s"
								and usu.id_grupo = "'.$this->session->userdata('id_grupo').'"
								order by id_menu_padre asc, id_menu asc';
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		$Menu = array();
		foreach($Resultado->result_array() as $Fila)
		{
			
			if('0' == $Fila['id_menu_padre'])
			{//Si es el menu principal
				//Creo un indice con el id_menu el cual a su vez sera un array de elementos
				//Dentro de el se almacenan la etiqueta y el enlace
				$Menu[$Fila['id_menu']]['etiqueta'] = $Fila['etiqueta'];
				$Menu[$Fila['id_menu']]['enlace'] = $Fila['enlace'];
				$Menu[$Fila['id_menu']]['activo'] = $Fila['activo'];
				$Menu[$Fila['id_menu']]['id_menu_padre'] = $Fila['id_menu'];
				//Se crea un indice adicional en el cual se guardaran los submenus
				$Menu[$Fila['id_menu']]['submenu'] = array();
			}
			else
			{//Si es el sub_menu voy agregando los elementos bajo el id de su grupo
				//Se crea un indice bajo submenu con el id_menu para identificar a cada
				//uno, guardamos su etiqueta y su enlace.
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['etiqueta'] = $Fila['etiqueta'];
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['enlace'] = $Fila['enlace'];
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['activo'] = $Fila['activo'];
				$Menu[$Fila['id_menu_padre']]['submenu'][$Fila['id_menu']]['id_menu'] = $Fila['id_menu'];
			}
			
		}
		return $Menu;
		
	}
	
}

/* Fin del archivo */