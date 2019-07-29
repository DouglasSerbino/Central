<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_agr_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Listado de los usuarios ordenados por el departamento al que pertenecen.
	 *Actualmente busca solamente los usuarios activos.
	 *@param string $Departamento: Si es indicado se filtran los usuarios.
	 *@return array
	*/
	function Usuarios()
	{
        $Consulta = '
						select id_usuario, nombre
						from usuario usu, departamentos dpto
						where dpto.departamento != "Planificacion"
						and dpto.departamento != "Ventas"
						and dpto.departamento != "Grupo"
						and usu.id_dpto = dpto.id_dpto
						and usu.activo = "s"
						and id_grupo = "'.$this->session->userdata('id_grupo').'"
						order by nombre asc
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return $Resultado->result_array();
	}
	
	/**
	 *Busca en la base de datos si hay horas extras para el dia seleccionado.
	 *@param string $fecha: Fecha seleccionada.
	 *@return array
	*/
	function Buscar_extras($tipo, $fecha, $id_usuario)
	{
		$Info_gen = array();
		$SQL = '';
		
		if($tipo == 'todos')
		{
			$SQL = ' and fecha = "'.$fecha.'"';
		}
		if($tipo == 'indi')
		{
			$SQL .= " and usu.id_usuario = $id_usuario and fecha = '$fecha'";
		}
		
		$Consulta = '
						select usu.id_usuario, usu.nombre, usu.cod_empleado, usu.hora, ext.inicio, usu.contrasena,
						ext.fin_real, ext.id_extra, ext.total_h, ext.total_m, ext.id_usu_adm
						from usuario usu, extra ext
						where usu.id_usuario = ext.id_usuario
						'.$SQL.' and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		$Datos = $Resultado->result_array();
		
		foreach($Datos as $Datos_generales)
		{
			$id_extra = $Datos_generales['id_extra'];
			$Info_gen[$Datos_generales['id_extra']]['id_extra'] = $Datos_generales['id_extra'];
			$Info_gen[$Datos_generales['id_extra']]['id_usuario'] = $Datos_generales['id_usuario'];
			$Info_gen[$Datos_generales['id_extra']]['nombre'] = $Datos_generales['nombre'];
			$Info_gen[$Datos_generales['id_extra']]['cod_empleado'] = $Datos_generales['cod_empleado'];
			$Info_gen[$Datos_generales['id_extra']]['hora'] = $Datos_generales['hora'];
			$Info_gen[$Datos_generales['id_extra']]['inicio'] = $Datos_generales['inicio'];
			$Info_gen[$Datos_generales['id_extra']]['fin_real'] = $Datos_generales['fin_real'];
			$Info_gen[$Datos_generales['id_extra']]['total_h'] = $Datos_generales['total_h'];
			$Info_gen[$Datos_generales['id_extra']]['total_m'] = $Datos_generales['total_m'];
			$Info_gen[$Datos_generales['id_extra']]['id_usu_adm'] = $Datos_generales['id_usu_adm'];
			$Info_gen[$Datos_generales['id_extra']]['extra_pedido'] = array();
			$Info_gen[$Datos_generales['id_extra']]['extra_otro'] = array();
			$Info_gen[$Datos_generales['id_extra']]['Flete']['id_extra_rec'] = array();
		
		
			$Consulta_flete = 'select id_extra_rec
												from extra_recibo
												where id_extra = "'.$id_extra.'"';
			
			$Resultado_flete = $this->db->query($Consulta_flete);
			$Info_flete = $Resultado_flete->result_array();
			if($Resultado_flete->num_rows > 0)
			{
				foreach($Info_flete as $Datos_flete)
				{
					$Info_gen[$Datos_generales['id_extra']]['Flete']['id_extra_rec'] = $Datos_flete['id_extra_rec'];
				}
			}
			else
			{
				$Info_gen[$Datos_generales['id_extra']]['Flete']['id_extra_rec'] = 0;
			}
		
			$Consulta_extra_pedidos = '
										select cli.codigo_cliente, proc.proceso, proc.nombre, extpe.id_extped,
										fecha_entrega, comentario
										from procesos proc, pedido ped, extra_pedido extpe, cliente cli
										where proc.id_proceso = ped.id_proceso
										and ped.id_pedido = extpe.id_pedido
										and cli.id_cliente = proc.id_cliente
										and extpe.id_extra = "'.$id_extra.'"
										order by extpe.id_extped asc
										';
			
			$Resultado_extra_pedidos = $this->db->query($Consulta_extra_pedidos);
			$Info_extra_pedidos = $Resultado_extra_pedidos->result_array();

			foreach($Info_extra_pedidos as $Datos_ext)
			{
				$Info_gen[$Datos_generales['id_extra']]['extra_pedido'][$Datos_ext['id_extped']]['codigo_cliente'] = $Datos_ext['codigo_cliente'];
				$Info_gen[$Datos_generales['id_extra']]['extra_pedido'][$Datos_ext['id_extped']]['proceso'] = $Datos_ext['proceso'];
				$Info_gen[$Datos_generales['id_extra']]['extra_pedido'][$Datos_ext['id_extped']]['nombre_traba']= $Datos_ext['nombre'];
				$Info_gen[$Datos_generales['id_extra']]['extra_pedido'][$Datos_ext['id_extped']]['fecha_entrega'] = $Datos_ext['fecha_entrega'];
				$Info_gen[$Datos_generales['id_extra']]['extra_pedido'][$Datos_ext['id_extped']]['comentario'] = $Datos_ext['comentario'];
			}
			
			
			$Consulta_extra_otro = '
											select otro, comentario, id_exto
											from extra_otro
											where id_extra = "'.$id_extra.'"
											order by id_exto asc
										';
			
			$Resultado_extra_otro = $this->db->query($Consulta_extra_otro);
			$Info_extra_otro = $Resultado_extra_otro->result_array();

			foreach($Info_extra_otro as $Datos_ext_otro)
			{
				$Info_gen[$Datos_generales['id_extra']]['extra_otro'][$Datos_ext_otro['id_exto']]['otro'] = $Datos_ext_otro['otro'];
				$Info_gen[$Datos_generales['id_extra']]['extra_otro'][$Datos_ext_otro['id_exto']]['comentario'] = $Datos_ext_otro['comentario'];
			}
		}
		return $Info_gen;
	}
	
	/**
	 *Funcion que servira para agregar el flete.
	 *@param $Id_extra. Id de la extra a la que le agregaremos el flete.
	 *@return ok
	*/
	function flete_agr($id_extra)
	{
        $Consulta = '
								insert into extra_recibo values(NULL, "'.$id_extra.'");
        ';
        
		$Resultado = $this->db->query($Consulta);
			
		return 'ok';
	}
	
}