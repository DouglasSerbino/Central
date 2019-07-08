<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Render_m extends CI_Model {
	
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
	function usuario()
	{
		
		//Consultamos la base de datos para que nos ofresca los menus.
		//Primero los menus correspondientes al departamento al que pertenece
		$Consulta = '
			select menu.id_menu, etiqueta, enlace, id_menu_padre
			from menu, menu_departamento mede
			where menu.id_menu = mede.id_menu and activo = "s"
			and id_departamento = "'.$this->session->userdata('id_dpto').'"
			order by id_menu_padre asc, menu.id_menu asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Guardare los menus en este array, prestar atencion a como se guardaran
		//jerarquicamente
		$Menu = array();
		//$Menu_Lat = array();
		$identificadores = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			if('0' == $Fila['id_menu_padre'])
			{//Si es el menu principal
				$identificadores[] = $Fila['etiqueta'];
				//Creo un indice con el id_menu y guardo el enlace principal
				$Menu[$Fila['id_menu']]['principal'] = '<a href="/'.$Fila['enlace'].'"><span></span>'.$Fila['etiqueta'].'</a>';
				//$Menu_Lat[$Fila['id_menu']]['principal'] = '<span></span>'.$Fila['etiqueta'];
				//Ademas de crear un elemento para el submenu
				$Menu[$Fila['id_menu']]['submenu'] = '';
				//$Menu_Lat[$Fila['id_menu']]['submenu'] = '';
			}
			else
			{
				$Menu[$Fila['id_menu_padre']]['submenu'] .= '<li><a href="/'.$Fila['enlace'].'">'.$Fila['etiqueta'].'</a></li>';
				//$Menu_Lat[$Fila['id_menu_padre']]['submenu'] .= '<li><a href="/'.$Fila['enlace'].'">'.$Fila['etiqueta'].'</a></li>';
			}
			
		}
		
		
		
		//Consultamos la base de datos para que nos ofresca los menus.
		//Despues los menus propios del usuario
		$Consulta = '
			select menu.id_menu, etiqueta, enlace, id_menu_padre
			from menu, menu_usuario meus
			where menu.id_menu = meus.id_menu and activo = "s"
			and id_usuario = "'.$this->session->userdata('id_usuario').'"
			order by id_menu_padre asc, menu.id_menu asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Continuare recolectando los menus y guardandolos.
		
		foreach($Resultado->result_array() as $Fila)
		{
			$identificadores[] = $Fila['etiqueta'];
			if('0' == $Fila['id_menu_padre'])
			{//Si es el menu principal
				//Creo un indice con el id_menu y guardo el enlace principal
				$Menu[$Fila['id_menu']]['principal'] = '<a href="/'.$Fila['enlace'].'"><span></span>'.$Fila['etiqueta'].'</a>';
				//$Menu_Lat[$Fila['id_menu']]['principal'] = '<a href="/'.$Fila['enlace'].'"><span></span>'.$Fila['etiqueta'].'</a>';
				//Ademas de crear un elemento para el submenu
				$Menu[$Fila['id_menu']]['submenu'] = '';
				//$Menu_Lat[$Fila['id_menu']]['submenu'] = '';
			}
			else
			{
				if(!isset($Menu[$Fila['id_menu_padre']]['submenu']))
				{
					$Menu[$Fila['id_menu_padre']]['submenu'] = '';
					//$Menu_Lat[$Fila['id_menu_padre']]['submenu'] = '';
				}
				//Si es el sub_menu voy agregando los elementos bajo el id de su grupo
				$Menu[$Fila['id_menu_padre']]['submenu'] .= '<li><a href="/'.$Fila['enlace'].'">'.$Fila['etiqueta'].'</a></li>';
				//$Menu_Lat[$Fila['id_menu_padre']]['submenu'] .= '<li><a href="/'.$Fila['enlace'].'">'.$Fila['etiqueta'].'</a></li>';
			}
			
		}
		
		
		
		$a=0;
		//Creacion del html
		$Menu_Usuario = '<ul id="navegacion">';
		// $MenuLateral = '<ul id="menu-lateral">';
		//Se recorre el array previamente creado
		foreach($Menu as $Index => $Item)
		{
				$identificador = explode(' ', $identificadores[$a]);
			
				if($identificador[0] != '')
				{
					$clase = $identificador[0];
				}
				else
				{
					$identificador2 = explode('/', $identificadores[$a]);
					if($identificador2[0] != '')
					{
						$clase = $identificador2[0];
					}
					else
					{
						$clase = $identificador;
					}
				}
			
			if(isset($Item['principal']))
			{
				//Se agrega el item principal
				$Menu_Usuario .= '<li class="MID_'.strtolower($clase).'">'.$Item['principal'].'<ul>';
				//Luego los submenus, si hubieren.
				$Menu_Usuario .= $Item['submenu'];
				//Se cierran las etiquetas de este grupo
				$Menu_Usuario .= '</ul></li>';
				
				// $MenuLateral .= '<li id="MLT_'.strtolower($clase).'" class="">'.//$Menu_Lat[$Index]['principal'].'<ul>';
				//Luego los submenus, si hubieren.
				// $MenuLateral .= //$Menu_Lat[$Index]['submenu'];
				//Se cierran las etiquetas de este grupo
				// $MenuLateral .= '</ul></li>';
			}
			
			$a++;
		}
		//Se cierra la etiqueta principal
		$Menu_Usuario .= '</ul>';
		// $MenuLateral .= '</ul>';
		
		//Se almacena en una sesion
		//Cada vez que el usuario cargue una pagina, su menu se tomara de las
		//sesiones, las cuales estaran almacenadas en la base de datos.
		$this->session->set_userdata(array('menu' => $Menu_Usuario, 'menula' => $MenuLateral));
		
		//Regreso el array con los grupos encontrados
		return 'ok';
		
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 *Busca en la base de datos todos los menus de usuario y los ordena en
	 *un array asociativo por menu principal y sus submenus.
	 *@param nada.
	 *@return array: Contiene el Menu.
	*/
	function cliente()
	{
		
		//Guardare los menus en este array, prestar atencion a como se guardaran
		//jerarquicamente
		$Menu = array();
		
		//Consultamos la base de datos para que nos ofresca los menus.
		//Despues los menus propios del usuario
		$Consulta = '
			select menu.id_menu, etiqueta, enlace, id_menu_padre
			from menu, menu_usuario meus
			where menu.id_menu = meus.id_menu and activo = "s"
			and id_usuario = "'.$this->session->userdata('id_usuario').'"
			order by id_menu_padre asc, menu.id_menu asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		$identificadores = array();
		//Continuare recolectando los menus y guardandolos.
		foreach($Resultado->result_array() as $Fila)
		{
			$identificadores[] = $Fila['etiqueta'];
			if('0' == $Fila['id_menu_padre'])
			{//Si es el menu principal
				//Creo un indice con el id_menu y guardo el enlace principal
				$Menu[$Fila['id_menu']]['principal'] = '<a href="/'.$Fila['enlace'].'"><span></span>'.$Fila['etiqueta'].'</a>';
				//Ademas de crear un elemento para el submenu
				$Menu[$Fila['id_menu']]['submenu'] = '';
			}
			else
			{
				//Si es el sub_menu voy agregando los elementos bajo el id de su grupo
				$Menu[$Fila['id_menu_padre']]['submenu'] .= '<li><a href="/'.$Fila['enlace'].'">'.$Fila['etiqueta'].'</a></li>';
			}
		}
		
		
		//Creacion del html
		$Menu_Usuario = '<ul id="navegacion">';
		//Se recorre el array previamente creado
		$a = 0;
		foreach($Menu as $Item)
		{
			$identificador = explode(' ', $identificadores[$a]);
			if($identificador[0] != '')
			{
				$clase = $identificador[0];
			}
			else
			{
				$identificador2 = explode('/', $identificadores[$a]);
				if($identificado2[0] != '')
				{
					$clase = $identificado2[0];
				}
				else
				{
					$clase = $identificador;
				}
			}
			//Se agrega el item principal
			$Menu_Usuario .= '<li class="MIDC_'.strtolower($clase).'">'.$Item['principal'].'<ul>';
			
			//Luego los submenus, si hubieren
			$Menu_Usuario .= $Item['submenu'];
			//Se cierran las etiquetas de este grupo
			$Menu_Usuario .= '</ul></li>';
			$a++;
		}
		//Se cierra la etiqueta principal
		$Menu_Usuario .= '</ul>';
		
		//Se almacena en una sesion
		//Cada vez que el usuario cargue una pagina, su menu se tomara de las
		//sesiones, las cuales estaran almacenadas en la base de datos.
		$this->session->set_userdata(array('menu' => $Menu_Usuario));
		
		
		//Regreso el array con los grupos encontrados
		return 'ok';
		
		
	}
	
}

/* Fin del archivo */