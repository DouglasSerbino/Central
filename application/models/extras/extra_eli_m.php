<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_eli_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Horas extras que se le programaron al usuario seleccionado.
	 
	 *@param $id_extra. Id de la extra que queremos modificar.
	 *@return array
	*/
	function eliminar_horas_extras($id_extra, $dia, $mes, $anho)
	{
      $Consulta = 'delete from extra where id_extra = "'.$id_extra.'"';
			$Resultado = $this->db->query($Consulta);
			$Consulta = 'delete from extra_pedido where id_extra = "'.$id_extra.'"';
			$Resultado = $this->db->query($Consulta);
			$Consulta = 'delete from extra_otro where id_extra = "'.$id_extra.'"';
			$Resultado = $this->db->query($Consulta);
		return 'ok';
	}
}