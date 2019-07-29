<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extras_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la fecha de creacion del usuario que quiere ingresar.
	 *@return array.
	*/
	function verificar_contra($Contrasenha)
	{
		//Establecemos la consulta para determinar si el usuario
		//ha ingresado la contrasenha correctamente.
			$Consulta = '
								select fecha_creacion
								from extra_contra extcon, usuario usu
								where usu.id_usuario = "'.$this->session->userdata('id_usuario').'"
								and extcon.contrasena = "'.$Contrasenha.'"
								and usu.id_usuario = extcon.id_usuario
								and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"

			';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Veririficamos si obtuvimos informacion.
		if(1 == $Resultado->num_rows())
		{
			//Si el usuario es valido
			//Regresamos OK
			return 'ok';
		}
		else
		{
			//Si la contrasenha es incorrecta devolvemos ERROR.
			return 'error';
		}
	}
	
	/**
	 *Busca en la base de datos si hay horas extras para este dia.
	 *Verifica si los otros dias tambien ha habido horas.
	 *@return array.
	*/
	function buscar_extras($dias_mes, $anho, $mes)
	{
		//Variable que almacenara los dias que tiene extras.
		$numero_dias = array();
		//Hacemos un for con el numero de dias que corresponde al mes elegido.
		for($i = 1; $i <= $dias_mes; $i++)
		{
			//Determinamos el dia en el que estamos.
			$dia_c = $i;
			if($dia_c < 10)
			{
				$dia_c = "0$dia_c";
			}
			//Establecemos la fecha que iremos comparando.
			$fecha_compare = $anho.'-'.$mes.'-'.$dia_c;
			
			//Establecemos la consulta para realizar la busquedad.
			$Consulta = '
								select ext.id_extra, id_extped
								from extra ext, extra_pedido extped, usuario usu
								where ext.id_extra = extped.id_extra
								and usu.id_usuario = ext.id_usuario
								and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
								and fecha = "'.$fecha_compare.'"
								';
		//echo $Consulta.'<br>';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Le asignamos el valor a una variable.
		$numero = $Resultado->num_rows();
		
		//Establecemos la consulta para realizar la busquedad.
		$Consulta2 = '
								select id_exto
								from extra ext, extra_otro exto, usuario usu
								where ext.id_extra = exto.id_extra
								and usu.id_usuario = ext.id_usuario
								and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
								and fecha = "'.$fecha_compare.'"
								';
		//Ejecutamos la segunda consulta.
		$Resultado2 = $this->db->query($Consulta2);
		//Sumamos el valor de 
		$numero += $Resultado2->num_rows();
		$numero_dias[$i] = $numero;
		//Regresamos la variable numero a 0 para poder hacer una nueva suma.
		$numero = 0;
		}
		//Regresamos un array con los dias que tienen horas extras.
		
		return $numero_dias;
	}
	
	
	/**
	 *Busca en la base de datos las proyecciones para el departamento y dia.
	 *Verifica si los otros dias tambien ha habido horas.
	 *@return array.
	*/
	function proyecciones($anho, $mes)
	{
		$Proyeccion = array();
			//Establecemos la consulta para realizar la busquedad.
			$Consulta = '
								select dpto.id_dpto, codigo, departamento, proyeccion
								from departamentos dpto, extra_proy expr, grupos
								where dpto.id_dpto = expr.id_dpto and dpto.activo = "s"
								and grupos.id_grupo = "'.$this->session->userdata["id_grupo"].'"
								and mes = "'.$mes.'" and anho = "'.$anho.'"
								order by id_dpto asc
								';
								
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		$Proyecciones = $Resultado->result_array();
		
		foreach($Proyecciones as $Datos_proyeccion)
		{
			$id_dpto = $Datos_proyeccion['id_dpto'];
			$Proyeccion[$Datos_proyeccion['departamento']]['id_dpto'] = $Datos_proyeccion['id_dpto'];
			$Proyeccion[$Datos_proyeccion['departamento']]['codigo'] = $Datos_proyeccion['codigo'];
			$Proyeccion[$Datos_proyeccion['departamento']]['departamento'] = $Datos_proyeccion['departamento'];
			$Proyeccion[$Datos_proyeccion['departamento']]['proyeccion'] = $Datos_proyeccion['proyeccion'];
		
			$Consulta2 = 'select sum(total_h) as horas, sum(total_m) as t_d
									from extra ext, usuario usu
									where ext.id_usuario = usu.id_usuario
									and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
									and id_dpto = "'.$id_dpto.'"
									and fecha >= "'.$anho.'-'.$mes.'-01"
									and fecha <= "'.$anho.'-'.$mes.'-31"
									';
			
			$Resultado2 = $this->db->query($Consulta2);
		
			$Porcentajes = $Resultado2->result_array();
			
			foreach($Porcentajes as $Datos_porcentaje)
			{
				$Proyeccion[$Datos_proyeccion['departamento']]['horas'] = $Datos_porcentaje["horas"];
				$Proyeccion[$Datos_proyeccion['departamento']]['t_d'] = $Datos_porcentaje["t_d"];
			}
		}
		return $Proyeccion;
	}
	
	
	
	/*************************/
	function horas_usuario($Id_Usuario = 0, $Anho = '', $Mes = '')
	{		
		$Consulta = '
			select sum(total_h) as horas
			from extra ext, usuario usu
			where ext.id_usuario = usu.id_usuario
			and usu.id_grupo = "'.$this->session->userdata("id_grupo").'"
			and ext.id_usuario = "'.$Id_Usuario.'"
			and fecha >= "'.$Anho.'-'.$Mes.'-01"
			and fecha <= "'.$Anho.'-'.$Mes.'-31"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->row_array();
		
	}
	
	
	
	/*************************/
	function horas_dpto($Id_Dpto = 0, $Anho = '', $Mes = '')
	{		
		$Consulta = '
			select sum(total_h) as horas
			from extra ext, usuario usu
			where ext.id_usuario = usu.id_usuario
			and usu.id_grupo = "'.$this->session->userdata("id_grupo").'"
			and id_dpto = "'.$Id_Dpto.'"
			and fecha >= "'.$Anho.'-'.$Mes.'-01"
			and fecha <= "'.$Anho.'-'.$Mes.'-31"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->row_array();
		
	}
	
	
}

/* Fin del archivo */