<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guardar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Guarda el comentario en la base de datos.
	 *@param string $Id_Pedido.
	 *@param string $Observacion.
	 *@param string $Fecha_Hora.
	 *@return string 'ok'.
	*/
	function index($Id_Pedido, $Observacion, $Fecha_Hora, $Aprobacion = 'n')
	{
		
		if('' != $Observacion)
		{
			//Ingresamos la observacion en la base de datos
			$Consulta = '
				insert into observacion values(
					NULL,
					"'.$Id_Pedido.'",
					"'.$this->session->userdata('id_usuario').'",
					"'.$Fecha_Hora.'",
					"'.$Observacion.'",
					"'.$Aprobacion.'"
				)
			';
			
			$this->db->query($Consulta);
			
		}
		
		return 'ok';
		
	}
}

/* Fin del archivo */