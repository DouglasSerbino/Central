<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Agregar grupos al sistema.
	 *Agregaremos los grupos que sean requeridos en esl sistema.
	 *@param string $Nombre: Nombre del grupo que ingresaremos al sistema.
	 *@param string $Abrev: Abreviatura del nuevo grupo.
	 *@param string $Tipo: Tipo al que pertence el grupo.
	 *@param string $Id_Cliente.
	 *@return string: 'ok' si el grupo se ingresa exitosamente
	 *@return string: 'error' si no se puede guardar el grupo.
	*/
	function almacenar($Nombre, $Abrev, $Tipo, $Id_Cliente)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'INSERT INTO grupos values(null,"'.$Nombre.'","'.$Abrev.'","'.$Tipo.'","s")';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			//Regresar mensaje de ok
			
			$Id_Grupo = $this->db->insert_id();
			
			
			
			if(0 < $Id_Cliente)
			{
				$Consulta = '
					insert into cliente_grupo values(
						NULL,
						"'.$this->session->userdata('id_grupo').'",
						"'.$Id_Cliente.'",
						"'.$Id_Grupo.'"
					)
				';
				$this->db->query($Consulta);
			}
			
			
			
			return $Id_Grupo;
		}
		else
		{//Si no se ingresa
			//Enviamos mensaje de error
			return 'error';
		}
	}
}

/* Fin del archivo */