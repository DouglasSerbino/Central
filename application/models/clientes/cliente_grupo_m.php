<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_grupo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Clientes que hacen referencia al grupo que va a ingresar un pedido.
	 *@param string $Usu_Grup.
	 *@return array.
	*/
	function listado($Usu_Grup)
	{
		
		$Cliente_Grupo = array();
		
		if(0 < count($Usu_Grup))
		{
			
			$Grupos = array();
			foreach($Usu_Grup as $Grupo)
			{
				$Grupos[] = 'id_grupo = "'.$Grupo.'"';
			}
			
			
			$Consulta = '
				select id_grupo, id_cliente
				from cliente_grupo
				where ('.implode(' or ', $Grupos).')
				and id_grupo_externo = "'.$this->session->userdata('id_grupo').'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Cliente_Grupo[$Fila['id_grupo']] = $Fila['id_cliente'];
			}
			
		}
		
		return $Cliente_Grupo;
		
	}
	
	
	
	function soy_cliente_de_repro()
	{
		
		//Funcion que llama los pedidos de los trabajos viejitos para los grupos
		//que ya eran clientes de Repro.
		$Consulta = '
				select clie.id_cliente, codigo_cliente
				from cliente_grupo clgr, cliente clie
				where clgr.id_cliente = clie.id_cliente and clgr.id_grupo = "1"
				and id_grupo_externo = "'.$this->session->userdata('id_grupo').'"
		';
		//echo $Consulta.'<br />';
		$Resultado = $this->db->query($Consulta);
		
		if(1 == $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		else
		{
			array();
		}
		
	}
	
}

/* Fin del archivo */