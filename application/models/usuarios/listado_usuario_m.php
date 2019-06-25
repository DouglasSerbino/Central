<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_Usuario_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos todos los usuarios almacenados y extrae la informacion
	 *de cada uno para enviarlo en un array.
	 *Los usuarios listados perteneceran al grupo del usuario que solicita el listado.
	 *@param string $Activo.
	 *@param string $Todos.
	 *@return array: Contiene el listado de los usuarios.
	*/
	function listado($Activo, $Todos = '', $Id_Grupo = 0, $Id_Dpto = 0)
	{
		
		$Id_Grupo += 0;
		$Id_Dpto += 0;
		
		
		if(0 === $Id_Grupo)
		{
			if(
				'Gerencia' == $this->session->userdata('codigo')
				|| 'Sistemas' == $this->session->userdata('codigo')
				|| 's' == $Todos
			)
			{
				//Si es un gerente quien solicita el listado, se le muestran todos los usuarios
				$Consulta = '
					select *
					from usuario
					where id_grupo = "'.$this->session->userdata('id_grupo').'"
					and activo = "'.$Activo.'"
					order by usuario asc
				';
			}
			else
			{
				$Consulta = '
					select usu.id_usuario, usu.usuario, usu.contrasena, usu.nombre,
					usu.puesto, usu.email, dpto.id_dpto, usu.id_grupo, usu.activo
					from usuario usu, departamentos dpto
					where usu.id_dpto = dpto.id_dpto and codigo != "Sistemas"
					and codigo != "Gerencia" and codigo != "Ventas" and codigo != "Admin"
					and codigo != "Plani" and codigo != "Grupo" and usu.activo = "'.$Activo.'"
					and id_grupo = "'.$this->session->userdata('id_grupo').'"
					order by usuario asc
				';
			}
		}
		else
		{
			$Consulta = '
				select *
				from usuario
				where id_grupo = "'.$Id_Grupo.'" and activo = "'.$Activo.'"
				and id_dpto = "'.$Id_Dpto.'"
				order by usuario asc
			';
		}
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los usuarios encontrados
		return $Resultado->result_array();
		
	}
	
	
	/**
	 *Listado de los usuarios ordenados por el departamento al que pertenecen.
	 *@param string $Departamento: Si es indicado se filtran los usuarios.
	 *@return array
	*/
	function departamento_usuario($Departamento = '', $Programable = true, $Activos = 's')
	{
		
		$Dpto_Usuario = array();
		
		if('n' != $Activos)
		{
			$Activos = 's';
		}


		//Consulta para pedir la informacion
		if('' == $Departamento)
		{
			
			if($Programable)
			{
				$Programable = 'and usu_prog = "s"';
			}
			else
			{
				$Programable = '';
			}
			
			$Consulta = '
				select usu.id_usuario, usu.id_dpto, usu.usuario, usu.nombre,
				usu.puesto, dpto.departamento, tiempo, usu.usu_prog, usu.activo
				from usuario usu, departamentos dpto
				where usu.id_dpto = dpto.id_dpto and usu.activo = "'.$Activos.'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
				'.$Programable.'
				order by dpto.id_dpto asc, usuario asc
			';
			
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Dpto_Usuario[$Fila['id_dpto']]['dpto'] = $Fila['departamento'];
				$Dpto_Usuario[$Fila['id_dpto']]['tiempo'] = $Fila['tiempo'];
				$Dpto_Usuario[$Fila['id_dpto']]['usuarios'][$Fila['id_usuario']]['activo'] = $Fila['activo'];
				$Dpto_Usuario[$Fila['id_dpto']]['usuarios'][$Fila['id_usuario']]['nombre'] = $Fila['nombre'];
				$Dpto_Usuario[$Fila['id_dpto']]['usuarios'][$Fila['id_usuario']]['puesto'] = $Fila['puesto'];
				$Dpto_Usuario[$Fila['id_dpto']]['usuarios'][$Fila['id_usuario']]['usuario'] = $Fila['usuario'];
				$Dpto_Usuario[$Fila['id_dpto']]['usuarios'][$Fila['id_usuario']]['programable'] = $Fila['usu_prog'];
			}
		}
		else
		{
			
			$Consulta = '
				select id_usuario, usu.id_dpto, usu.usuario,
				usu.nombre, usu.puesto, usu.usu_prog, tiempo
				from usuario usu, departamentos dpto
				where usu.id_dpto = dpto.id_dpto and usu.activo = "'.$Activos.'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
				and codigo = "'.$Departamento.'"
				order by usu.id_dpto asc, usuario asc
			';
			
			$Resultado = $this->db->query($Consulta);
			foreach($Resultado->result_array() as $Fila)
			{
				$Dpto_Usuario[$Fila['id_usuario']]['tiempo'] = $Fila['tiempo'];
				$Dpto_Usuario[$Fila['id_usuario']]['usuario'] = $Fila['usuario'];
				$Dpto_Usuario[$Fila['id_usuario']]['nombre'] = $Fila['nombre'];
				$Dpto_Usuario[$Fila['id_usuario']]['puesto'] = $Fila['puesto'];
				$Dpto_Usuario[$Fila['id_usuario']]['programable'] = $Fila['usu_prog'];
			}
		}
		return $Dpto_Usuario;
	}
	
	
	
	/**
	 *Busca en la base de datos todos los usuarios almacenados y extrae la informacion
	 *de cada uno para enviarlo en un array.
	 *Los usuarios mostrados seran los que se les pueden aplicar reprocesos.
	*/
	function listadoUsuariosRepro()
	{
		$Consulta = '
			select usu.id_usuario, usu.usuario, usu.contrasena, usu.nombre,
			usu.puesto, usu.email, dpto.id_dpto, usu.id_grupo, usu.activo
			from usuario usu, departamentos dpto
			where usu.id_dpto = dpto.id_dpto and codigo != "Sistemas"
			and codigo != "Gerencia" and codigo != "Ventas" and codigo != "Admin"
			and codigo != "Plani" and codigo != "Grupo"
			and codigo != "Facturacio" and usu.activo = "s"
			and codigo != "Seguimient"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by usuario asc
		';
		
		//echo $Consulta;
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los usuarios encontrados
		return $Resultado->result_array();
		
	}
	
	
}

/* Fin del archivo */