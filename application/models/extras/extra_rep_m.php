<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_rep_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos las horas extras}
	 *que se han realizado en el periodo elegido por el usuario.
	 *@param $dia1, $mes1, $anho1, $dia2, $mes2, $anho2.
	 *@return array.
	*/
	function mostrar_extras($dia1, $mes1, $anho1, $dia2, $mes2, $anho2)
	{
		$Informacion = array();
		//Establecemos la consulta para determinar si el usuario
		//ha ingresado la contrasenha correctamente.
			$Consulta = '
								select usu.id_usuario, id_extra, contrasena, cod_empleado, nombre, hora, inicio, fin, fin_real,
								fecha, total_h, total_m, id_usu_adm
								from usuario usu, extra ext
								where usu.id_usuario = ext.id_usuario
								and fecha >= "'.$anho1.'-'.$mes1.'-'.$dia1.'"
								and fecha <= "'.$anho2.'-'.$mes2.'-'.$dia2.'"
								and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
								order by usu.id_usuario asc, fecha asc
			';

		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Veririficamos si obtuvimos informacion.
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	/**
	 *Busca en la base de datos las personas que ingresaron las horas extras.
	 *@param $HExtras contiene la informacion de los usuarios que tienen horas extras
	 *@return array.
	*/
	function mostrar_admon($HExtras)
	{
		$Informacion = '';
		if($HExtras != '')
		{
			foreach($HExtras as $Datos_extras)
			{
				$Id_usuario_admin = $Datos_extras['id_usu_adm'];
				$Consulta = 'select id_usuario, usuario
											from usuario usu
											where usu.id_usuario = "'.$Id_usuario_admin.'"
											and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
										';
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta);
				$Datos_administrador = $Resultado->result_array();
				foreach($Datos_administrador as $Datos_admon)
				{
					$Informacion .= $Datos_admon['usuario'].'-';
				}
			}
			return $Informacion;
		}
		else
		{
			return array();
		}
	}
}
/* Fin del archivo */