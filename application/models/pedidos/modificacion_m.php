<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificacion_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Modificacion del pedido en la base de datos.
	 *@param string $Id_Pedido.
	 *@param string $Prioridad.
	 *@param string $Tipo_Trabajo.
	 *@param string $Id_Usu_Rechazo.
	 *@return string [$Id_Pedido|'error'].
	*/
	function index(
		$Id_Pedido,
		$Prioridad,
		$Tipo_Trabajo,
		$Id_Usu_Rechazo,
		$Fecha_Entrega,
		$Reproceso_razon
	)
	{
		
		//Campos a modificar
		$Consulta = '
			update pedido
			set prioridad = "'.$Prioridad.'", id_tipo_trabajo = "'.$Tipo_Trabajo.'",
			id_responsable = "'.$Id_Usu_Rechazo.'", fecha_entrega = "'.$Fecha_Entrega.'", id_repro_deta = "'.$Reproceso_razon.'"
			where id_pedido = "'.$Id_Pedido.'"
		';
		
		//Modificar pedido
		$this->db->query($Consulta);
		
		
		//Necesito cambiar el id_usuario que ha ingresado el pedido.
		//Es probable que este pedido se creo por un vendedor y el pedido tomo el
		//id_plani generico para el grupo. Hay que hacer que agarre el id_usuario
		//que lo esta modificando.
		$Consulta = '
			select id_peus
			from pedido_usuario peus, usuario usu
			where peus.id_usuario = usu.id_usuario and nombre = "Plani (Gen&eacute;rico)"
			and id_pedido = "'.$Id_Pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			
			$Id_Peus = $Resultado->row_array();
			
			$Consulta = '
				update pedido_usuario
				set id_usuario = "'.$this->session->userdata('id_usuario').'"
				where id_peus = "'.$Id_Peus['id_peus'].'"
			';
			
			$this->db->query($Consulta);
			
		}
		
		
		
		//Tiene un pedido hijo o padre?
		
		//Tiene un hijo?
		$Consulta = '
			select id_ped_secundario
			from pedido_pedido
			where id_ped_primario = "'.$Id_Pedido.'"
		';
		$Resultado = $this->db->query($Consulta);
		if(1 == $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Consulta = '
				update pedido
				set id_tipo_trabajo = "'.$Tipo_Trabajo.'"
				where id_pedido = "'.$Fila['id_ped_secundario'].'"
			';
			$this->db->query($Consulta);
		}
		
		//Tiene un padre?
		$Consulta = '
			select id_ped_primario
			from pedido_pedido
			where id_ped_secundario = "'.$Id_Pedido.'"
		';
		$Resultado = $this->db->query($Consulta);
		if(1 == $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Consulta = '
				update pedido
				set id_tipo_trabajo = "'.$Tipo_Trabajo.'"
				where id_pedido = "'.$Fila['id_ped_primario'].'"
			';
			$this->db->query($Consulta);
		}
		
		
		return 'ok';
		
	}
	
}

/* Fin del archivo */