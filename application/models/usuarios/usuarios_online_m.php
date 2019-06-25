<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_online_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Esta funcion nos permitira mostrar un listado
	 *de los usuarios que estan conectados al sistema.
	 *Nos servira de mucho para saber si hay usuarios de otro grupo conectados al sistema.
	*/
	function usuarios()
	{
		$fecha = date('Y-m-d H:i:s', strtotime('- 30 minutes', strtotime(date('Y-m-d H:i:s'))));
		$Info = array();
		
		$Consulta = 'delete from user_conectados where fecha < "'.$fecha.'"';
		$Resultado = $this->db->query($Consulta);
		$Consulta = 'select usu.nombre, usu.id_grupo, usu.id_usuario,
									onl.pagina, dpto.departamento, grup.nombre_grupo,
									onl.fecha, dpto.id_dpto
								from user_conectados onl, usuario usu,
									grupos grup, departamentos dpto
								where onl.id_usuario = usu.id_usuario
									and dpto.id_dpto = usu.id_dpto
									and grup.id_grupo = usu.id_grupo
								order by dpto.departamento';
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$Info[$Datos['id_grupo']]['grupo'] = $Datos['nombre_grupo'];
				$Info[$Datos['id_grupo']]['usuarios'][$Datos['id_dpto']]['departamento'] = $Datos['departamento'];
				$Info[$Datos['id_grupo']]['usuarios'][$Datos['id_dpto']]['usuarios'][$Datos['id_usuario']]['nombre'] = $Datos['nombre'];
				$Info[$Datos['id_grupo']]['usuarios'][$Datos['id_dpto']]['usuarios'][$Datos['id_usuario']]['fecha'] = $Datos['fecha'];
				$Info[$Datos['id_grupo']]['usuarios'][$Datos['id_dpto']]['usuarios'][$Datos['id_usuario']]['pagina'] = $Datos['pagina'];
			}
			return $Info;
		}
		else
		{
			return $Info;
		}
	}
}
/* Fin del archivo */