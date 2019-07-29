<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_eliminar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a las divisiones
	 *@return array: Array con la informacion de lo tipos de divisiones.
	*/
	function eliminar_pedido($id_pedido)
	{
		$id_especificacion_general = '';
		
		$Consulta = "delete from pedido where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		$Consulta = "delete from pedido_adjuntos where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		$Consulta = "delete from pedido_usuario where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		$Consulta = "delete from observacion where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		//$Consulta = "delete from hojas_revision where id_pedido = $id_pedido";
		//$Resultado = $this->db->query($Consulta);
		$Consulta = "delete from producto_pedido where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		$Consulta = "delete from pedido_sap where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		
		//Si se elimina un pedido, el tiempo que un usuario ocupo no debe ser eliminado, debe quedar para su reporte de tiempo
		$Consulta = "select id_tiempo, inicio from pedido_tiempos where id_pedido = $id_pedido and fin = '0000-00-00 00:00:00'";
		$Resultado = $this->db->query($Consulta);
		$id_tiempo = "";
		foreach($Resultado->result_array() as $fila)
		{
			$id_tiempo = $fila["id_tiempo"];
			$inicio = $fila["inicio"];
		}
		
		
		if($id_tiempo != "")
		{
			//Debo saber cuantas horas tardo en realizarse este trabajo
			$f_i = $this->fechas_m->fecha_subdiv($inicio);
			$f_f = $this->fechas_m->fecha_subdiv(date('Y-m-d H:m:s'));
			$fecha = date('Y-m-d H:m:s');
			$segundos = mktime($f_f["hora"],$f_f["minuto"],0,$f_f["mes"],$f_f["dia"],$f_f["anho"]) - mktime($f_i["hora"],$f_i["minuto"],0,$f_i["mes"],$f_i["dia"],$f_i["anho"]);
		//	echo $segundos;
			$tiempo_guardar = $segundos / 60;
			//echo $horas.'---'.$segundos;
			$Consulta = "update pedido_tiempos set fin = '$fecha', tiempo = '$tiempo_guardar' where id_tiempo = $id_tiempo";
			$Resultado = $this->db->query($Consulta);
			
		}
		
		$Consulta = "select id_especificacion_general from especificacion_general where id_pedido = $id_pedido";
		$Resultado = $this->db->query($Consulta);
		foreach($Resultado->result_array() as $fila)
		{
			$id_especificacion_general = $fila["id_especificacion_general"];
		}
		
		if($id_especificacion_general != "")
		{
			$Consulta = "delete from especificacion_general where id_pedido = $id_pedido";
			$Resultado = $this->db->query($Consulta);
			$Consulta = "delete from especificacion_matsolgru where id_pedido = $id_pedido";
			$Resultado = $this->db->query($Consulta);
			$Consulta = "delete from especificacion_matrecgru where id_pedido = $id_pedido";
			$Resultado = $this->db->query($Consulta);
			$Consulta = "delete from especificacion_guias where id_especificacion_general = $id_especificacion_general";
			$Resultado = $this->db->query($Consulta);
			$Consulta = "delete from especificacion_colores where id_especificacion_general = $id_especificacion_general";
			$Resultado = $this->db->query($Consulta);
			$Consulta = "delete from especificacion_distorsion where id_especificacion_general = $id_especificacion_general";
			$Resultado = $this->db->query($Consulta);
		}
		
		return 'ok';
		
	}
}

/* Fin del archivo */