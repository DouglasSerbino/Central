<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_usuario_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Necesito saber el id_peus de quien este como != Terminado y != Agregado.
	 *@param string $Id_Pedido.
	 *@return string $Id_Peus.
	*/
	function buscar_id_peus($Id_Pedido)
	{
		
		$Consulta = '
			select id_peus, codigo
			from pedido_usuario peus, usuario usu, departamentos dpto
			where peus.id_usuario = usu.id_usuario and usu.id_dpto = dpto.id_dpto
				and id_pedido = "'.$Id_Pedido.'" and estado != "Terminado"
				and estado != "Agregado"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return 0;
		}
		else
		{
			return $Resultado->row_array();
		}
		
	}
	

	/****************/
	function pedidos_anuales($Id_Usuario, $Anho)
	{
		
		$Consulta = '
			select count(id_pedido) as pedidos, date_format(fecha_inicio, "%Y-%m") as fecha
			from pedido_usuario
			where id_usuario = "'.$Id_Usuario.'"
				and fecha_inicio >= "'.$Anho.'-01-01 00:00:00"
				and fecha_inicio <= "'.$Anho.'-12-31 23:59:59"
			group by date_format(fecha_inicio, "%Y-%m")
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		$Trabajos = array();
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Trabajos[$Fila['fecha']] = $Fila['pedidos'];
			}
		}
		
		return $Trabajos;
	}
}

/* Fin del archivo */