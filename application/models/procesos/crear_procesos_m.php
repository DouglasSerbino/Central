<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear_procesos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Agregar procesos al sistema.
	 *Agregaremos los procesos que sean requeridos en el sistema.
	 *@param string $Id_Cliente: id del cliente que requiere el proceso.
	 *@param string $Procesos: numero de proceso que se utilizara.
	 *@param string $Producto: Nombre del producto que se ingresara.
	 *@param string $Id_Grupo.
	 *@return string: 'ok' si el proceso se ingresa exitosamente
	 *@return string: 'error' si no se puede guardar el proceso.
	*/
	function guardar_proceso($Id_Cliente, $Procesos, $Producto, $Id_Grupo, $Igual = false)
	{
		
		if('cli' == $this->session->userdata('tipo_grupo') && !$Igual)
		{
			
			$Consulta = '
				SELECT proceso
				FROM procesos proc, cliente clie
				WHERE proc.id_cliente = clie.id_cliente
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
				order by proceso+0 desc limit 0, 1
			';
			
			$Resultado = $this->db->query($Consulta);
			
			$Procesos = 1;
			if(1 == $Resultado->num_rows())
			{
				$Resultado = $Resultado->row_array();
				$Procesos = $Resultado['proceso'] + 1;
			}
			
			if(10 > $Procesos)
			{
				$Procesos = '000'.$Procesos;
			}
			elseif(100 > $Procesos)
			{
				$Procesos = '00'.$Procesos;
			}
			elseif(1000 > $Procesos)
			{
				$Procesos = '0'.$Procesos;
			}
			
		}
		
		$Consulta = 'SELECT proc.proceso
			FROM procesos proc, cliente cli, grupos
			WHERE proc.proceso = "'.$Procesos.'"
			AND cli.id_cliente = "'.$Id_Cliente.'"
			AND cli.id_grupo = grupos.id_grupo
			AND proc.id_cliente = cli.id_cliente
			and grupos.id_grupo = "'.$Id_Grupo.'"
		';
		
    $Resultado = $this->db->query($Consulta);
		//Si la consulta obtuvo resultados
		
		if(1 == $Resultado->num_rows())
		{
			return 'existe';
		}
		else
		{
			$Producto = $this->seguridad_m->mysql_seguro($Producto);
			$Procesos = $this->seguridad_m->mysql_seguro($Procesos);
			$Consulta = 'INSERT INTO procesos values (null, "'.$Id_Cliente.'", "'.$Procesos.'", "'.$Producto.'")';
			
			//echo $Consulta;
			//Ejecuto la consulta
			$Resultado = $this->db->query($Consulta);
			
			$Consulta = '
				SELECT id_proceso
				FROM procesos proc, cliente cli, grupos
				WHERE proc.proceso = "'.$Procesos.'"
				AND cli.id_cliente = "'.$Id_Cliente.'"
				AND cli.id_grupo = grupos.id_grupo
				AND proc.id_cliente = cli.id_cliente
				and grupos.id_grupo = "'.$Id_Grupo.'"
			';
			
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			//Si la consulta obtuvo resultados
			if(1 == $Resultado->num_rows())
			{
				$Id_Proceso = $Resultado->row_array();
				return $Id_Proceso['id_proceso'];
			}
			else
			{
				//Enviamos mensaje de error
				return 'error';
			}
	}
	}
    
}

/* Fin del archivo */