<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nueva_revision_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	function hoja_revision($Id_Pedido)
	{

		$Consulta = '
			select id_revision_pedido
			from revision_pedido
			where id_pedido = "'.$Id_Pedido.'"
			and id_usuario = "'.$this->session->userdata('id_usuario').'"
			order by id_revision_pedido desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return 'si';
		}
		else
		{
			return '';
		}
		
	}
	

	//Consulto que dptos poseen hojas de revision
	function dptos_hojas()
	{

		$Consulta = '
			select distinct dpto.id_dpto, departamento
			from departamentos dpto, revision_item item
			where dpto.id_dpto = item.id_dpto
		';
		$Resultado = $this->db->query($Consulta);


		return $Resultado->result_array();

	}
	

	//Consulto que dptos poseen hojas de revision
	function items_completo($Id_Dpto = 0)
	{

		$Id_Dpto += 0;


		$Condicion_dpto = '';
		if(0 < $Id_Dpto)
		{
			$Condicion_dpto = '
				where id_dpto = "'.$Id_Dpto.'"
			';
		}



		$Consulta = '
			select id_revision_item as id_item, item, activo
			from revision_item
			'.$Condicion_dpto.'
			order by id_revision_item asc
		';
		$Resultado = $this->db->query($Consulta);

		$Items_Revision = array();
		
		if(0 < $Resultado->num_rows())
		{

			foreach ($Resultado->result_array() as $Fila)
			{
				$Items_Revision[$Fila['id_item']]['item'] = $Fila['item'];
				$Items_Revision[$Fila['id_item']]['activo'] = $Fila['activo'];
				$Items_Revision[$Fila['id_item']]['sub_item'] = array();
			}


			$Consulta = '
				select id_revision_item as id_item, id_revision_sub_item as id_sitem,
				sub_item, activo
				from revision_sub_item
				where id_revision_item in ('.implode(',', array_keys($Items_Revision)).')
				order by id_revision_sub_item asc
			';
			$Resultado = $this->db->query($Consulta);

			foreach ($Resultado->result_array() as $Fila)
			{
				$Items_Revision[$Fila['id_item']]['sub_item'][$Fila['id_sitem']]['sub_item'] = $Fila['sub_item'];
				$Items_Revision[$Fila['id_item']]['sub_item'][$Fila['id_sitem']]['activo'] = $Fila['activo'];
			}

		}


		return $Items_Revision;

	}
	

	//****************
	function agregar($Item, $Nivel, $Id_Dpto)
	{

		if(0 == $Nivel)
		{
			$Consulta = '
				insert into revision_item value(
					NULL,
					"'.$Item.'",
					"'.$Id_Dpto.'",
					"s"
				)
			';
			$this->db->query($Consulta);
		}
		else
		{
			$Consulta = '
				insert into revision_sub_item value(
					NULL,
					"'.$Nivel.'",
					"'.$Item.'",
					"s"
				)
			';
			$this->db->query($Consulta);
		}


		return 'ok';

	}



	
	function revision($Id_Pedido, $Items_Revision)
	{
		
		$Observacion = $this->seguridad_m->mysql_seguro(
			$this->input->post('rev_observaciones')
		);
		

		$Revision = array();
		foreach ($Items_Revision as $Index => $Sub_Item)
		{
			foreach ($Sub_Item['sub_item'] as $ISub => $Datos)
			{
				$Revision[$ISub] = $this->seguridad_m->mysql_seguro(
					$this->input->post('item_'.$ISub)
				);
			}
		}

		

		$Consulta = "
			insert into revision_pedido values(
				NULL,
				'".$Id_Pedido."',
				'".json_encode($Revision)."',
				'".$Observacion."',
				'".date('Y-m-d')."',
				'".$this->session->userdata('id_usuario')."'
			)
		";
		$this->db->query($Consulta);
			
	}


	function eliminar($Tipo, $Item)
	{

		if('item' == $Tipo)
		{
			$Consulta = '
				delete from revision_item
				where id_revision_item = "'.$Item.'"
			';
			$this->db->query($Consulta);
		}

		if('sub' == $Tipo)
		{
			$Consulta = '
				delete from revision_sub_item
				where id_revision_sub_item = "'.$Item.'"
			';
			$this->db->query($Consulta);
		}

	}
	

	//****************
	function modificar($Item, $Tipo, $Id_Item)
	{

		if('item' == $Tipo)
		{
			$Consulta = '
				update revision_item
				set item = "'.$Item.'"
				where id_revision_item = "'.$Id_Item.'"
			';
			$this->db->query($Consulta);
		}
		else
		{
			$Consulta = '
				update revision_sub_item
				set sub_item = "'.$Item.'"
				where id_revision_sub_item = "'.$Id_Item.'"
			';
			$this->db->query($Consulta);
		}

	}
	
	
}

/* Fin del archivo */
?>