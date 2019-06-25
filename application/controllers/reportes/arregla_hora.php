<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arregla_horaAA extends CI_Controller {
	
	
	public function index()
	{
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		$anho = date('Y');
		$mes = date('m');
		$dia = '01';
		
		
		$Consulta = '
			select id_tiempo, inicio, fin, usuario
			from pedido_tiempos tiem, usuario usu where tiem.id_usuario = usu.id_usuario
			and (id_dpto = 23 or id_dpto = 1 or id_grupo = 28 or id_grupo = 29)
			and inicio >= "'.$anho.'-'.$mes.'-01 00:00:00"
		';
		
		echo '
		<style>body{font-size:13px;line-height:19px;} span{display:inline-block;width:35px;text-align:right;margin-right:3px;}</style>
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Contador = 1;
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			//echo '<span>'.$Contador.'</span>$Tiempo = $this->fechas_m->tiempo_habil('.$Fila['inicio'].', '.$Fila['fin'].'):';
			$Contador++;
			$Tiempo = $this->fechas_m->tiempo_habil($Fila['inicio'], $Fila['fin']);
			//echo $Tiempo.'<br />';
			$Fecha_Upd = '';
			if('0000-00-00 00:00:00' == $Fila['fin'])
			{
				$Fecha_Upd = ', fin = "'.$Fila['inicio'].'"';
			}
			$Consulta = '
				update pedido_tiempos
				set tiempo = "'.$Tiempo.'"'.$Fecha_Upd.'
				where id_tiempo = "'.$Fila['id_tiempo'].'"
			';
			$this->db->query($Consulta);
		}
		
		
		
		/*
		 En esta pagina recorrere todos los trabajos :S para arreglar sus tiempos
		 y dejar los registros en la tabla pedido_tie_repro espero no cargar mucho
		 el script porque puede explotar.
		 Debo calcular tiempos 
		*/
		$inicio = "$anho-$mes-$dia";
		
		
		$id_pedido_v = array();
		
		$Consulta = '
			select ped.id_pedido, fecha_entrada, fecha_reale
			from cliente clie, procesos proc, pedido ped
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = id_pedido and fecha_reale != "0000-00-00"
			and fecha_reale >= "'.$inicio.'" and id_grupo = 1
			order by ped.id_pedido asc
		';
		
		//echo '<br />'.$Consulta.'<br />';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$id_pedido_v[$Fila['id_pedido']]['entrada'] = $Fila['fecha_entrada'];
			$id_pedido_v[$Fila['id_pedido']]['reale'] = $Fila['fecha_reale'];
			
		}
		
		/*echo '<pre>';
		print_r($id_pedido_v);
		echo '</pre>';*/
		
		foreach($id_pedido_v as $id_pedido => $datos)
		{
			
			$fecha_entrada = $datos['entrada'];
			$fecha_reale = $datos['reale'];
			
			//Debo calcular entonces el tiempo de resplani, arte, aprobacion, efinales; y guardarlo en una nueva tabla :S
			
			//Dias entre fecha de inicio y fin del pedido actual
			//Pasar a segundos la fecha de entrada
			$segundos_entrada = $this->fechas_m->fecha_a_segundos($fecha_entrada.' 00:00:01');
			//Pasar a segundos la fecha de finalizacion
			$segundos_real = $this->fechas_m->fecha_a_segundos($fecha_reale.' 00:00:01');
			
			//Convierto a total de dias que estuvo el trabajo en central-g
			$DiasTotalRepro = ($segundos_real - $segundos_entrada) / 86400 + 1;
			
			//Total de tiempo en aprobacion para este pedido y la fecha en que termino la ultima aprobacion
			//Es posible que este pedido no tenga tiempo en aprobacion registrado
			$tiempo_apr = 0;//Tiempo total
			$fecha_fin_apr = '';//Ultima fecha aprobacion
			//Total de dias en aprobacion, inicializado como n/a en el caso que no tenga tiempo este pedido
			$DiasAprobacion = 'n/a';
			$DiasArte = 'n/a';//Total dias en arte, inicializamos como n/a por si no fuera el caso
			$DiasEFinal = 'n/a';//Total dias eFinal, inicializamos como n/a por si no fuera el caso
			
			
			//-------------------------------------------
			//Busco si este pedido tiene elementos finales solicitados
			$Consulta = '
				select id_material_solicitado
				from especificacion_matsolgru espe, material_solicitado_grupo mate
				where espe.id_material_solicitado_grupo = mate.id_material_solicitado_grupo
				and id_pedido = "'.$id_pedido.'" and (id_material_solicitado = 4
				or id_material_solicitado = 6 or id_material_solicitado = 8
				or id_material_solicitado = 9 or id_material_solicitado = 10
				or id_material_solicitado = 11 or id_material_solicitado = 12
				or id_material_solicitado = 13 or id_material_solicitado = 15)
			';
			//echo $Consulta.'<br /><br />';
			
			$Resultado = $this->db->query($Consulta);
			
			$elementos_f = false;//Hay elementos finales en este pedido?
			if(0 < $Resultado->num_rows())
			{
				$elementos_f = true;
			}
			
			//Hago las validaciones para calcular los tiempos
			
			$Consulta = '
				select id_material_solicitado
				from especificacion_matsolgru espe, material_solicitado_grupo mate
				where espe.id_material_solicitado_grupo = mate.id_material_solicitado_grupo
				and id_pedido = "'.$id_pedido.'" and (id_material_solicitado = 1
				or id_material_solicitado = 2 or id_material_solicitado = 16)
			';
			//echo $Consulta.'<br /><br />';
			
			$Resultado = $this->db->query($Consulta);
			
			//Hay elementos finales en este pedido?
			$elementos_a = false;
			if(0 < $Resultado->num_rows())
			{
				$elementos_a = true;
			}
			
			
			if($elementos_a){
				//Al total de dias de arte le aplico el total que estuvo en central-g
				$DiasArte = number_format($DiasTotalRepro, 2);
			}
			if($elementos_f){
				$DiasArte = 'n/a';
				//Quiere decir que venia solo de salida
				$DiasEFinal = number_format($DiasTotalRepro, 2);//Al total de dias efinal le asigno el total de dias que estuvo en repro
			}
			
			//Si este pedido ya tuviere un registro en pedido_tie_repro, es posible que sea un pedido traido del mas alla
			$sql = "delete from pedido_tie_repro where id_pedido = $id_pedido";//Debe ser eliminado
			$this->db->query($sql);
			//Esto no aplicara en todos los trabajos, son muy pocos
			
			//Todo listo, ingreso de nuevo registro
			$sql = "insert into pedido_tie_repro values(NULL, $id_pedido, 'n/a', '$DiasArte', '$DiasAprobacion', '$DiasEFinal', 0, 0, 0)";
			//echo "$sql<br />";
			$this->db->query($sql);
			//-------------------------------------------
			
			//break;
			
		}
		
		
	}
	
}
