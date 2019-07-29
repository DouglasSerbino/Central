<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Ingresa en la base de datos el usuario indicado.
	 *@param string $Id_Usuario.
	 *@param string $Usuario.
	 *@param string $Password.
	 *@param string $Cod_empleado,
	 *@param string $Nombre.
	 *@param string $Puesto.
	 *@param string $Departamento.
	 *@param string $Email.
	 *@return string 'ok', 'error'.
	*/
	function usuario(
		$Id_Usuario,
		$Usuario,
		$Password,
		$Cod_empleado,
		$Nombre,
		$Puesto,
		$Departamento,
		$Email,
		$Usu_prog,
		$Pais
	)
	{
		
		//Consulta para insertar el usuario
		$Consulta = '
			update usuario set
			usuario = "'.$Usuario.'",
			contrasena = "'.$Password.'",
			nombre = "'.$Nombre.'",
			cod_empleado = "'.$Cod_empleado.'",
			puesto = "'.$Puesto.'",
			email = "'.$Email.'",
			id_dpto = "'.$Departamento.'",
			usu_prog = "'.$Usu_prog.'",
			upais = "'.$Pais.'"
			where id_usuario = "'.$Id_Usuario.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Informacion que todo fue bien realizado
		return 'ok';
		
	}
	
	
	/**
	 *Asignar los menus a los usuarios.
	 *@param string $Id_Usuario.
	 *@param string $Id_menu.
	 *@return string: 'ok' si se asigna exitosamente.
	 *@return string: 'error' si no se puede asignar.
	*/
	function activar_menu($Id_Usuario, $Id_menu)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'INSERT INTO menu_usuario values(null,
																								"'.$Id_menu.'",
																								"'.$Id_Usuario.'"
																								)';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			//Regresar mensaje de ok
			return 'ok';
		}
		else
		{//Si no se ingresa
			//Enviamos mensaje de error
			return 'error';
		}
	}
	
	/**
	 *Asignar los menus a los departamentos.
	 *@param string $Id_Usuario.
	 *@param string $Id_menu.
	 *@return string: 'ok', 'error'.
	*/
	function eliminar_menu($Id_Usuario, $Id_menu)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'DELETE FROM menu_usuario
								where id_menu="'.$Id_menu.'"
								and id_usuario = "'.$Id_Usuario.'"';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			//Regresar mensaje de ok
			return 'ok';
		}
		else
		{//Si no se ingresa
			//Enviamos mensaje de error
			return 'error';
		}
	}
	
	
	/**
	 *Asignar los grupos a los usuarios.
	 *@param string $Id_Usuario.
	 *@param string $Id_grupo.
	 *@return string: 'ok', 'error'.
	*/
	function asignar_los_grupos($Id_grupo, $Id_Usuario)
	{
		//Establecemos la consulta para verificar que no
		//se haya agregado el usuario a un grupo.
		$Consulta = 'select * from usuario_grupo
								where id_usuario = "'.$Id_Usuario.'"
								and id_grupo = "'.$Id_grupo.'"';
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		if($Resultado->num_rows > 0)
		{
			$Info = $Resultado->result_array();
			foreach($Info as $Datos)
			{
				$id_usu_grupo = $Datos['id_usuario_grupo'];
			}
			
			$Consulta = 'UPDATE usuario_grupo set
									id_usuario= "'.$Id_Usuario.'",
									id_grupo = "'.$Id_grupo.'"
									where id_usuario_grupo = "'.$id_usu_grupo.'"
									and id_usuario = "'.$Id_Usuario.'"';
		
				//Ejecuto la consulta
				$Resultado = $this->db->query($Consulta);
		}
		else
		{
			//Creamos la consulta para guardar los registros en la base de datos.
			$Consulta = 'INSERT INTO usuario_grupo values("null",
																										"'.$Id_Usuario.'",
																										"'.$Id_grupo.'")';

			//Ejecuto la consulta
			$Resultado = $this->db->query($Consulta);
		}
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			//Regresar mensaje de ok
			return 'ok';
		}
		else
		{//Si no se ingresa
			//Enviamos mensaje de error
			return 'error';
		}
	}
}

/* Fin del archivo */