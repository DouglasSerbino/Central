<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Modificar los grupos del sistema.
	 *@param int Id del grupo que queremos modificar.
	 *@param string $Nombre: Nombre del grupo que se modificara.
	 *@param string $Abrev: Abreviatura del grupo modificado.
	 *@param string $Tipo: Tipo al que pertence el grupo.
	 *@param string $Id_Cliente.
	 *@return string: 'ok' si el grupo se modifica exitosamente
	 *@return string: 'error' si no se puede modificar el grupo.
	*/
	function modificar_sql($Id_grupo, $Nombre, $Abrev, $Tipo, $Id_Cliente)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'UPDATE grupos SET nombre_grupo = "'.$Nombre.'", abreviatura ="'.$Abrev.'", tipo_grupo="'.$Tipo.'" where id_grupo ="'.$Id_grupo.'"';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			
			$Consulta = '
				delete from cliente_grupo
				where id_grupo = "'.$this->session->userdata('id_grupo').'"
				and id_grupo_externo = "'.$Id_grupo.'"
			';
			$this->db->query($Consulta);
			
			
			if(0 < $Id_Cliente)
			{
				$Consulta = '
					insert into cliente_grupo values(
						NULL,
						"'.$this->session->userdata('id_grupo').'",
						"'.$Id_Cliente.'",
						"'.$Id_grupo.'"
					)
				';
				$this->db->query($Consulta);
			}
			
			
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