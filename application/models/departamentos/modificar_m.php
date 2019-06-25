<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Modificar los departamentos del sistema.
	 *@param int id del departamento que queremos modificar.
	 *@param string $Codigo codigo del departamento que queremos modificar
	 *@param string $Departamento: Nombre del departamento que queremos modificar.
	 *@param string $Tipo_inv Tipo de inventario del departamento.
	 *@param string $$Cant_mensual: Cantidad mensual referente a cada departamento.
	 *@param string $Iniciales: Iniciales de cada departamento
	 *@return string: 'ok' si el departamento se modifica exitosamente
	 *@return string: 'error' si no se puede modificar el departamento.
	*/
	function modificar_sql($Id_dpto, $Codigo, $Departamento, $Tipo_inv, $Cant_mensual, $Iniciales)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'UPDATE departamentos SET codigo = "'.$Codigo.'", departamento ="'.$Departamento.'", tipo_inv="'.$Tipo_inv.'", cant_mensual="'.$Cant_mensual.'", iniciales ="'.$Iniciales.'" where id_dpto ="'.$Id_dpto.'"';
		
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
	
	
	/**
	 *Asignar los menus a los departamentos.
	 *@param string $Id_dpto: Id del departamento para asignarlo a un menu.
	 *@param string $Id_menu Id del menu para asignar a un departamento.
	 *@return string: 'ok' si se asigna exitosamente.
	 *@return string: 'error' si no se puede asignar el departamento.
	*/
	function activar_menu($Id_dpto, $Id_menu)
	{

		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'INSERT INTO menu_departamento values(null, "'.$Id_menu.'", "'.$Id_dpto.'")';
		
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
	
	/**
	 *Asignar los menus a los departamentos.
	 *@param string $Id_dpto: Id del departamento para asignarlo a un menu.
	 *@param string $Id_menu Id del menu para asignar a un departamento.
	 *@return string: 'ok' si se asigna exitosamente.
	 *@return string: 'error' si no se puede asignar el departamento.
	*/
	function eliminar_menu($Id_dpto, $Id_menu)
	{
		
		//Creamos la consulta para guardar los registros en la base de datos.
		$Consulta = 'DELETE FROM menu_departamento where id_menu="'.$Id_menu.'" and id_departamento = "'.$Id_dpto.'"';
		
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