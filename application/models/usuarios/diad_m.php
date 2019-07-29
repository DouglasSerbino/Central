<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diad_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	

	function calcular()
	{
		
		//Consulta para insertar el usuario
		$Consulta = '
			select id_usuario, nombre, salario
			from usuario
			where planilla = "sa" or  planilla = "se"
		';
		
		$Resultado = $this->db->query($Consulta);
		

		$Tabla_Renta = $this->tabla_renta();

		
		$Planilla = array();

		foreach($Resultado->result_array() as $Fila)
		{

			$Planilla[$Fila['id_usuario']]['Empleado'] = $Fila['nombre'];
			$Planilla[$Fila['id_usuario']]['Salario'] = $Fila['salario'];
			$Planilla[$Fila['id_usuario']]['AFP'] = $Fila['salario'] * 0.062499;
			$Planilla[$Fila['id_usuario']]['ISSS'] = $Fila['salario'] * 0.03;
			if(685.71 < $Fila['salario'])
			{
				$Planilla[$Fila['id_usuario']]['ISSS'] = 20.57;
			}

			$Sueldo = $Fila['salario'] - $Planilla[$Fila['id_usuario']]['AFP'];

			

			if(472.00 >= $Sueldo)
			{
				$Planilla[$Fila['id_usuario']]['Renta'] = 0;
			}
			elseif(895.24 >= $Sueldo)
			{
				$Planilla[$Fila['id_usuario']]['Renta'] = (
					($Sueldo - 472.00) * 10 / 100
				) + 17.67;
			}
			elseif(2038.10 >= $Sueldo)
			{
				$Planilla[$Fila['id_usuario']]['Renta'] = (
					($Sueldo - 895.24) * 20 / 100
				) + 60.00;
			}
			else
			{
				$Planilla[$Fila['id_usuario']]['Renta'] = (
					($Sueldo - 2038.10) * 30 / 100
				) + 288.57;
			}

			$Sueldo = $Sueldo - $Planilla[$Fila['id_usuario']]['ISSS'];
			$Sueldo = $Sueldo - $Planilla[$Fila['id_usuario']]['Renta'];
			$Planilla[$Fila['id_usuario']]['Recibir'] = $Sueldo;

			$Planilla[$Fila['id_usuario']]['Retenciones'] = $Planilla[$Fila['id_usuario']]['AFP'];
			$Planilla[$Fila['id_usuario']]['Retenciones'] += $Planilla[$Fila['id_usuario']]['ISSS'];
			$Planilla[$Fila['id_usuario']]['Retenciones'] += $Planilla[$Fila['id_usuario']]['Renta'];

		}

		
		return $Planilla;
		
	}
	

	function tabla_renta()
	{
		
		//Consulta para insertar el usuario
		$Consulta = '
			select *
			from tabla_renta
			order by id_tabla_renta asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
}

/* Fin del archivo */