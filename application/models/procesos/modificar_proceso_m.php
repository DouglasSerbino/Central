<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class modificar_proceso_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Modificar el proceso que se ha seleccionado.
	 *@param string $Id_Cliente. Id del cliente seleccionado.
	 *@param string $Proceso. Numero de proceso
	 *@param string $Nombre. Nombre del cliente.
	 *@param string $Producto. Nombre del producto que se ha seleccionado.
	 *@return nada.
	*/
	function modificar_sql(
		$Cod_cliente,
		$Id_cliente,
		$Procesos,
		$Proceso_ant,
		$Id_proceso,
		$Producto,
		$Cod_clienteAnt
	)
	{
		
		$Consulta = '
			select id_cliente from cliente
			where codigo_cliente = "'.$Cod_cliente.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		//echo $Consulta.'<br><br>';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Datos)
		{
			$Cliente = $Datos['id_cliente'];
		}
		
		$Consulta2 = '
			Select proc.id_proceso, cli.id_grupo, cli.id_cliente
			from procesos proc, cliente cli
			where cli.id_cliente = proc.id_cliente
			and cli.codigo_cliente = "'.$Cod_clienteAnt.'"
			and proc.proceso = "'.$Proceso_ant.'"
		';
		//echo $Consulta2.'<br><br>';
		
		$Resultado = $this->db->query($Consulta2);
		if(0 < $Resultado->num_rows())
		{
			//print_r($Resultado->result_array());
			//echo '<br><br>';
			
			foreach($Resultado->result_array() as $Datos)
			{
				//Creamos la consulta para guardar los registros en la base de datos.
				$Consulta = '
					UPDATE procesos proc
					SET proc.proceso = "'.$Procesos.'", proc.id_cliente ="'.$Cliente.'",
					proc.nombre="'.$Producto.'"
					where proc.id_proceso ="'.$Datos['id_proceso'].'"
				';
				//echo $Consulta.'<br>';
				$Resultado = $this->db->query($Consulta);
			}
			
			//Validamos que se ejecute la consulta
			if($Resultado)
			{
				//Si la consulta se realizo correctamente
				//Regresar mensaje de ok
				return 'ok';
			}
			else
			{
				//Si no se ingresa
				//Enviamos mensaje de error
				return 'error consulta';
			}
		}
		else
		{
			return 'error';
		}
		
	}
	
}

/* Fin del archivo */