<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desactivar_Activar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Desactivar los clientes del sistema.
	 *@param int Id del cliente que queremos desactivar.
	 *@return string: 'ok' si el cliente se desactiva exitosamente
	 *@return string: 'error' si no se puede desactivar el cliente.
	*/
	function acciones($Tipo, $Id_cliente)
	{
		
		if('a' == $Tipo)
		{
			//Creamos la consulta para activar los clientes.
			$Consulta = 'UPDATE cliente SET activo="s" where id_cliente = "'.$Id_cliente.'"';
		}
		else
		{
			//Creamos la consulta para desactivar los clientes.
			$Consulta = 'UPDATE cliente SET activo="n" where id_cliente = "'.$Id_cliente.'"';
		}
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Validamos que se ejecute la consulta
		if($Resultado)
		{//Si la consulta se realizo correctamente
			//Regresar mensaje de ok
			return 'ok';
		}
		else
		{//Si no se ingresa
			//Enviamos mensaje de error
			return 'error';
		}
	}
	
}

/* Fin del archivo */