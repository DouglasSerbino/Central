<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preingreso_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Existencia y actividad del proceso recibido.
	 *@param string $Proceso.
	 *@param string $Id_Cliente.
	 *@return array.
	*/
	function validar_proceso($Proceso, $Id_Cliente)
	{
		
		$Consulta = '
			select id_proceso, proc.nombre
			from procesos proc, cliente clie
			where clie.id_cliente = proc.id_cliente and clie.id_cliente = "'.$Id_Cliente.'"
			and proceso = "'.$Proceso.'" and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(1 == $Resultado->num_rows()){
			
			$Fila = $Resultado->row_array();
			
			
			$Consulta = '
				select id_pedido
				from pedido
				where id_proceso = "'.$Fila['id_proceso'].'" and fecha_reale = "0000-00-00"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(1 == $Resultado->num_rows())
			{
				return $Fila['nombre'].'[an]'.'procesando';
			}
			else
			{
				return $Fila['nombre'].'[an]'.$Fila['id_proceso'];
			}
			
		}
		else{
			return 'nuevo';
		}
		
	}

	function obtenerPolimeros(){
		$this->db->select('*');
		$this->db->from('constante_polimero');
		$query = $this->db->get();
		$polimeros = $query->result_array();
		return $polimeros;
	}
	
}

/* Fin del archivo */