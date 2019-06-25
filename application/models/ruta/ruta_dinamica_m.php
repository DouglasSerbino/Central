<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ruta_dinamica_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}


	//***********************************************************
	function listar()
	{

		$Consulta = '
			select id_ruta, nombre, elemento, observacion
			from ruta, cliente clie
			where ruta.id_cliente = clie.id_cliente
			order by nombre asc, elemento asc
		';
		$Resultado = $this->db->query($Consulta);

		return $Resultado->result_array();

	}


	//***********************************************************
	function detalle_rutas($Id_Cliente)
	{

		$Consulta = '
			select id_ruta, elemento, observacion
			from ruta
			where id_cliente = "'.$Id_Cliente.'" or id_cliente = 0
			order by elemento asc, observacion asc
		';
		$Resultado = $this->db->query($Consulta);

		$Detalle_Rutas = array();

		foreach($Resultado->result_array() as $Fila)
		{
			$Detalle_Rutas[$Fila['id_ruta']]['ruta'] = array();
			$Detalle_Rutas[$Fila['id_ruta']]['elemento'] = $Fila['elemento'];
			$Detalle_Rutas[$Fila['id_ruta']]['observacion'] = $Fila['observacion'];
		}


		$Consulta = '
			select id_ruta_puesto as irup, id_ruta as iruta, id_dpto, id_usuario as iusu, tiempo
			from ruta_puesto
			where id_ruta in ('.implode(',', array_keys($Detalle_Rutas)).')
			order by irup asc
		';
		$Resultado = $this->db->query($Consulta);

		foreach($Resultado->result_array() as $Fila)
		{
			$Irup = $Fila['irup'];
			$Iruta = $Fila['iruta'];
			$Detalle_Rutas[$Iruta]['ruta'][$Irup]['iusu'] = $Fila['iusu'];
			$Detalle_Rutas[$Iruta]['ruta'][$Irup]['tiempo'] = $Fila['tiempo'];
			$Detalle_Rutas[$Iruta]['ruta'][$Irup]['id_dpto'] = $Fila['id_dpto'];
		}

		return $Detalle_Rutas;

	}


	//***********************************************************
	function cual_ruta($Id_Ruta_Puesto)
	{

		$Consulta = '
			select id_ruta
			from ruta_puesto
			where id_ruta_puesto in ('.$Id_Ruta_Puesto.')
		';
		$Resultado = $this->db->query($Consulta);

		$Id_Ruta = $Resultado->row_array();

		if(isset($Id_Ruta['id_ruta']))
		{
			return $Id_Ruta['id_ruta'];
		}
		else
		{
			return 0;
		}


	}
	
	

	//***********************************************************
	function almacenar($Ruta_Texto, $Elemento, $Id_Cliente, $Ruta_Nombre)
	{

		$Ruta_Texto = json_decode($Ruta_Texto, true);

		if(0 == count($Ruta_Texto))
		{
			return 'error';
			exit();
		}

		ksort($Ruta_Texto);
		
		$Consulta = '
			insert into ruta values(
				NULL,
				"'.$Id_Cliente.'",
				"'.$Elemento.'",
				"'.$Ruta_Nombre.'"
			)
		';

		$this->db->query($Consulta);

		$Id_Ruta = $this->db->insert_id();


		foreach ($Ruta_Texto as $Id_Dpto)
		{
			$Consulta = '
				insert into ruta_puesto values(
					NULL,
					"'.$Id_Ruta.'",
					"'.$Id_Dpto.'",
					"",
					""
				)
			';

			$this->db->query($Consulta);
		}


		return 'ok';

		
	}




	//***********************************************************
	function obtener($Id_Ruta)
	{

		$Ruta = array('info' => array(), 'dptos' => array());

		$Consulta = '
			select id_ruta, id_cliente, elemento, observacion
			from ruta
			where id_ruta = "'.$Id_Ruta.'"
		';
		$Resultado = $this->db->query($Consulta);

		$Ruta['info'] = $Resultado->row_array();


		$Consulta = '
			select id_dpto, id_usuario, tiempo, id_ruta_puesto
			from ruta_puesto
			where id_ruta = "'.$Id_Ruta.'"
			order by id_ruta_puesto asc
		';
		$Resultado = $this->db->query($Consulta);

		$Ruta['dptos'] = $Resultado->result_array();

		return $Ruta;

	}




	//***********************************************************
	function eliminar($Id_Ruta)
	{

		$Consulta = '
			delete from ruta
			where id_ruta = "'.$Id_Ruta.'"
		';
		$this->db->query($Consulta);

		$Consulta = '
			delete from ruta_puesto
			where id_ruta = "'.$Id_Ruta.'"
		';
		$this->db->query($Consulta);

	}



}

/* Fin del archivo */