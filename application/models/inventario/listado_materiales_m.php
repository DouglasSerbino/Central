<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_materiales_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos los materiales que correspondan a los filtros recibidos.
	 *@param string $Codigo.
	 *@param string $Proveedor.
	 *@param string $Cantidad.
	 *@param string $Equipo.
	 *@return array.
	*/
	function listar(
		$Codigo,
		$Proveedor,
		$Cantidad,
		$Equipo
	)
	{
		
		//Declaramos un array que contendra la consulta SQL.
		$Condicion = array();
		
		if('todos' != $Codigo)
		{
			$Condicion[] = 'mp_mt = "'.$Codigo.'"';
		}
		
		if('con' == $Cantidad)
		{
			$Condicion[] = 'existencias > 0';
		}
		
		if('sin' == $Cantidad)
		{
			$Condicion[] = 'existencias = 0';
		}
		
		if('--' != $Equipo)
		{
			$Condicion[] = 'mate.id_inventario_equipo = "'.$Equipo.'" ';
		}
		
		if('--' != $Proveedor)
		{
			$Condicion[] = 'mat_prov.id_inventario_proveedor = "'.$Proveedor.'"';
		}
		
		$Condicion[] = 'mate.id_grupo = "'.$this->session->userdata["id_grupo"].'"';

		
		$Condicion_Sql = implode(' and ', $Condicion);
		
		
		if('--' == $Proveedor)
		{
			
			$Consulta = '
				select id_inventario_material, codigo_sap, valor, cantidad_unidad, tipo,
				nombre_material, existencias, numero_individual, numero_cajas, mat_pais
				from inventario_material mate where
			';
			
			if('' != $Condicion_Sql)
			{
				$Consulta .= '
				 '.$Condicion_Sql;
			}
			$Consulta .= '
				order by codigo_sap+0 asc
			';
			
		}
		else
		{
			//Declaramos la consuta SQL.
			$Consulta = '
				select distinct mate.id_inventario_material, codigo_sap, valor, cantidad_unidad, tipo,
				nombre_material, existencias, numero_individual, numero_cajas, mat_pais
				from inventario_material mate, inventario_material_proveedor mat_prov,
				inventario_proveedor prov, inventario_equipo equi
				where mate.id_inventario_material = mat_prov.id_inventario_material
				and equi.id_inventario_equipo = mate.id_inventario_equipo
				and mate.id_grupo = prov.id_grupo
				and prov.id_inventario_proveedor = mat_prov.id_inventario_proveedor
				and '.$Condicion_Sql.'
				order by codigo_sap+0 asc
			';
		}
		
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//Regresamos un array con la informacion.
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}		
	}
	
}

/* Fin del archivo */