<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Validacion de usuarios del sistema.
	 *Se toman los datos enviados por el usuario y se procede a verificar su existencia
	 *en la base de datos.
	 *Si el usuario existe en el sistema se crea una sesion para guardar sus datos
	 *principales.
	 *@param string $Grupo: El grupo de la corporacion al que pertenece el usuario.
	 *@param string $Usuario: Nombre de usuario.
	 *@param string $Password: Password del usuario.
	 *@return string: 'ok' si usuario existe.
	 *@return string: 'error' si usuario no existe.
	*/
	function validar_usuario($Grupo, $Usuario, $Password)
	{
		
		//Consultamos la base de datos para que nos ofresca los grupos.
		//Debe coincidir el usuario, password y el grupo.
		$Consulta = '
			select id_usuario, usuario, contrasena, nombre, puesto, usu.id_dpto,
			usu.id_grupo, dpto.codigo, nombre_grupo, usu.cod_empleado, tipo_grupo,
			preferencias, upais
			from usuario usu, grupos gru, departamentos dpto
			where usuario = "'.$Usuario.'" and contrasena = "'.$Password.'"
			and usu.id_grupo = gru.id_grupo and abreviatura = "'.$Grupo.'"
			and usu.id_dpto = dpto.id_dpto and usu.activo = "s" and gru.activo = "s"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
		//Validamos que encontramos el usuario indicado
		if(1 == $Resultado->num_rows())
		{//Si la consulta envio un usuario valido
			
			//Tomamos los datos encontrados de la base
			$Info_Usuario = $Resultado->result_array();
			
			
			//Validacion para verificar que los datos ingresados por el usuario son
			//correctos, esto agrega un nivel de seguridad mas al comprobar las
			//mayusculas y minusculas.
			if(
				$Usuario != $Info_Usuario[0]['usuario']
				|| $Password != $Info_Usuario[0]['contrasena']
			)
			{//Si no son iguales en la capitalizacion de las letras
				//Enviamos mensaje de error
				return 'error';
			}
			
			
			//Crear la sesion para el usuario.
			//Vamos a guardar Id usuario, nombre, puesto, id_grupo,
			//id_dpto, Grupo
			
			
			$this->session->set_userdata(
				array(
					'id_usuario' => $Info_Usuario[0]['id_usuario'],
					'nombre' => $Info_Usuario[0]['nombre'],
					'puesto' => $Info_Usuario[0]['puesto'],
					'id_grupo' => $Info_Usuario[0]['id_grupo'],
					'codigo' => $Info_Usuario[0]['codigo'],
					'cod_empleado' => $Info_Usuario[0]['cod_empleado'],
					'id_dpto' => $Info_Usuario[0]['id_dpto'],
					'grupo' => $Grupo,
					'tipo_grupo' => $Info_Usuario[0]['tipo_grupo'],
					'nombre_grupo' => $Info_Usuario[0]['nombre_grupo'],
					'temporizador' => strtotime('now'),
					'preferencias' => $Info_Usuario[0]['preferencias'],
					'pais' => $Info_Usuario[0]['upais']
				)
			);
			
			
			
			
			$CI =& get_instance();
			$url = $CI->config->site_url($CI->uri->uri_string());
	
			$fecha = date('Y-m-d H:i:s');
			$Consulta = '
				select *
				from user_conectados
				where id_usuario = "'.$Info_Usuario[0]['id_usuario'].'"
			';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				if('pie' != $this->uri->segment(1) && 'usuarios_online' != $this->uri->segment(2))
				{
					$Consulta = '
						UPDATE user_conectados
						set fecha = "'.$fecha.'", pagina = "'.$url.'"
						where id_usuario = "'.$Info_Usuario[0]['id_usuario'].'"
					';
					$Resultado = $this->db->query($Consulta);
				}
			}
			else
			{
				$Consulta = '
					INSERT INTO user_conectados values(
						null,
						"'.$Info_Usuario[0]['id_usuario'].'",
						"'.$fecha.'",
						"'.$url.'"
					)
				';
				$Resultado = $this->db->query($Consulta);
			}
			
			//Regresar mensaje de ok
			return 'ok';
			
		}
		else
		{//Si no es valido
			//Enviamos mensaje de error
			return 'error';
		}
		
	}
	
	
	
	
	/**
	 *Validar Cliente, siempre y cuando pertenezca a este grupo.
	 *@param string $Grupo.
	 *@param string $Usuario.
	 *@param string $Password.
	 *@return 'ok'|'error'
	*/
	function validar_cliente($Grupo, $Usuario, $Password)
	{
		
		//Consultamos la base de datos para que nos ofresca los grupos.
		//Debe coincidir el usuario, password y el grupo.
		$Consulta = '
			select id_cliente, codigo_cliente, nombre, clie.id_grupo, usuario,
			contrasena, tipo_grupo
			from cliente clie, grupos gru
			where usuario = "'.$Usuario.'" and contrasena = "'.$Password.'"
			and clie.id_grupo = gru.id_grupo and abreviatura = "'.$Grupo.'"
			and clie.activo = "s" and gru.activo = "s"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
		//Validamos que encontramos el usuario indicado
		if(1 == $Resultado->num_rows())
		{//Si la consulta envio un usuario valido
			
			//Tomamos los datos encontrados de la base
			$Info_Cliente = $Resultado->result_array();
			
			
			//Validacion para verificar que los datos ingresados por el cliente son
			//correctos, esto agrega un nivel de seguridad mas al comprobar las
			//mayusculas y minusculas.
			if(
				$Usuario != $Info_Cliente[0]['usuario']
				|| $Password != $Info_Cliente[0]['contrasena']
			)
			{//Si no son iguales en la capitalizacion de las letras
				//Enviamos mensaje de error
				return 'error';
			}
			
			
			//Debo buscar el id del usuario asignado a ventas para este grupo
			$Consulta = '
				select id_usuario, id_dpto
				from usuario
				where nombre = "Ventas (Gen&eacute;rico)" and id_grupo = "'.$Info_Cliente[0]['id_grupo'].'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				$Info_Usuario = $Resultado->row_array();
			}
			
			
			
			//Crear la sesion para el usuario.
			//Vamos a guardar Id usuario, nombre, puesto, id_grupo,
			//id_dpto, Grupo
			$this->session->set_userdata(
				array(
					'id_usuario' => $Info_Usuario['id_usuario'],
					'id_cliente' => $Info_Cliente[0]['id_cliente'],
					'nombre' => $Info_Cliente[0]['nombre'],
					'codigo_cliente' => $Info_Cliente[0]['codigo_cliente'],
					'puesto' => '',
					'id_grupo' => $Info_Cliente[0]['id_grupo'],
					'codigo' => 'Ventas',
					'id_dpto' => $Info_Usuario['id_dpto'],
					'grupo' => $Grupo,
					'tipo_grupo' => $Info_Cliente[0]['tipo_grupo'],
					'temporizador' => strtotime('now')
				)
			);
			
			
			//Regresar mensaje de ok
			return 'ok';
			
		}
		else
		{//Si no es valido
			//Enviamos mensaje de error
			return 'error';
		}
		
	}
	
}

/* Fin del archivo */