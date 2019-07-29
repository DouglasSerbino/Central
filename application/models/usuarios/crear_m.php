<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Ingresa en la base de datos el usuario indicado.
	 *@param string $Usuario.
	 *@param string $Password.
	 *@param String $Cod_empleado.
	 *@param string $Nombre.
	 *@param string $Puesto.
	 *@param string $Departamento.
	 *@param string $Email.
	 *@return string 'ok', 'error'.
	*/
	function usuario(
		$Usuario,
		$Password,
		$Cod_empleado,
		$Nombre,
		$Puesto,
		$Departamento,
		$Email,
		$Id_Grupo,
		$Enviar_Id = false,
		$Pais
	)
	{
		
		//Consulta para insertar el usuario
		$Consulta = '
			insert into usuario values(
				NULL,
				"'.$Usuario.'",
				"'.$Password.'",
				"'.$Nombre.'",
				"'.$Cod_empleado.'",
				"'.$Puesto.'",
				"'.$Email.'",
				"'.$Departamento.'",
				"'.$Id_Grupo.'",
				"0",
				"s",
				"s",
				"",
				"'.$Pais.'"
			)
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		//Informacion que todo fue bien realizado
		if($Enviar_Id)
		{
			return $this->db->insert_id();
		}
		else
		{
			return 'ok';
		}
		
	}
	
}

/* Fin del archivo */