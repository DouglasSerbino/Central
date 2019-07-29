<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_cilindro_keep_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function buscar_info_cilindro($pulgas, $mas, $menos)
	{
		$SQL = '';
		$where = 'where';
		
		if($pulgas != 0)
		{
			for($a=1;$a<=12;$a++)
			{
				$largo = $pulgas * $a;
				//echo $largo.'--'.$menos.'<br>';
				if($a == 12)
				{
					$or = '';
				}
				else
				{
					$or = 'or';
				}
				
				if($a <= 12)
				{
					$SQL .= $where.' (largo_impresion >= '.(($pulgas - $menos) * $a).'
												and largo_impresion <= '.(($pulgas + $mas) * $a).') '.$or.'';
				}
				$where = '';
			}
		}
		
		
		//echo $SQL;
		$Consulta = "select * from info_cilindro $SQL order by largo_impresion asc";
		
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		$info = array(
			'menores' => array(),
			'iguales' => array(),
			'mayores' => array()
		);
		if($this->session->userdata('id_grupo') == 1 or $this->session->userdata('id_grupo') == 2)
		{
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Datos)
				{
					if($Datos['largo_impresion'] == $pulgas)
					{
						$tipo = 'iguales';
					}
					elseif($Datos['largo_impresion'] > $pulgas)
					{
						$tipo = 'mayores';
					}
					else
					{
						$tipo = 'menores';
					}
					
					$info[$tipo]['impres'][$Datos['impresora']]['maquina'] = $Datos['impresora'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['articulo'] = $Datos['articulo'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['detalle'] = $Datos['detalle'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['cod'] = $Datos['cod'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['cliente'] = $Datos['cliente'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['colores'] = $Datos['colores'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['impresora'] = $Datos['impresora'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['ancho_cliente'] = $Datos['ancho_cliente'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['repe_alto'] = $Datos['repe_alto'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['ancho_impresion'] = $Datos['ancho_impresion'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['largo_cliente'] = $Datos['largo_cliente'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['repe_desarrollo'] = $Datos['repe_desarrollo'];
					$info[$tipo]['impres'][$Datos['impresora']]['info_maquinas'][$Datos['id_info_cilindro']]['largo_impresion'] = $Datos['largo_impresion'];
				}
				//ksort($info);
				//print_r($info);
				return $info;
			}
			else
			{
				return $info;
			}
		}
		else
		{
			return $info;
		}
	}
}
/* Fin del archivo */