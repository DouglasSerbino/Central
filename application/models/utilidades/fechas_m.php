<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fechas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	//Divido la fecha para poder utilizar cada elemento por aparte Y-m-d H:m:s. Regresa un Array()
	function fecha_subdiv($fecha)
	{
		
		$fecha_return = array();
		
		if(strlen($fecha) == "19")
		{
			$fecha_return["anho"] = substr($fecha, 0, 4);
			$fecha_return["mes"] = substr($fecha, 5 , 2);
			$fecha_return["dia"] = substr($fecha, 8, 2);
			$fecha_return["hora"] = substr($fecha, 11, 2);
			$fecha_return["minuto"] = substr($fecha, 14, 2);
			$fecha_return["segundo"] = substr($fecha, 17, 2);
			
			return $fecha_return;//Devuelvo un array con la fecha ya seccionada, jejejejeje
		}
		else
		{
			log_message('error', $this->uri->uri_string().' --> "'.$fecha.'" No es un formato de fecha valida.');
			return 'error';
		}
		
	}
	
	
	//Divido las fechas que estan en formato "d-m-Y H:m". Regresa un Array()
	function fecha_rsubdiv($fecha)
	{
		
		$fecha_return = array();
		if(strlen($fecha != "16"))
		{
			$fecha_return["dia"] = substr($fecha, 0, 2);
			 $fecha_return["mes"] = substr($fecha, 3 , 2);
			 $fecha_return["anho"] = substr($fecha, 6, 4);
			 $fecha_return["hora"] = substr($fecha, 11, 2);
			$fecha_return["minuto"] = substr($fecha, 14, 2);
			
			return $fecha_return;//Devuelvo un array con la fecha ya seccionada, jejejejeje
		}
		else
		{
			log_message('error', $this->uri->uri_string().' --> "'.$fecha.'" No es un formato de fecha valida.');
			return 'error';
		}
		
	}
	
	
	//Divido las fechas que estan en formato "Y-m-d". Regresa un Array()
	function fecha_rsubdiv2($fecha)
	{
		$fecha_return = array();
		if(strlen($fecha != "10"))
		{
			$fecha_return["anho"] = substr($fecha, 0, 4);
			$fecha_return["mes"] = substr($fecha, 5 , 2);
			$fecha_return["dia"] = substr($fecha, 8, 2);
			
			return $fecha_return;//Devuelvo un array con la fecha ya seccionada, jejejejeje
		}
		else
		{
			log_message('error', $this->uri->uri_string().' --> "'.$fecha.'" No es un formato de fecha valida.');
			return 'error';
		}
	}
	
	
	//Paso las fecha segun mysql a fechas segun central-g. Devuelvo una cadena.
	function fecha_ymd_dmy($fecha)
	{
		if(strlen($fecha != "10"))
		{
			list($anho, $mes, $dia) = explode('-', $fecha);
			return "$dia-$mes-$anho";
		}
		else
		{
			log_message('error', $this->uri->uri_string().' --> "'.$fecha.'" No es un formato de fecha valida.');
			return 'error';
		}
	}
	
	
	//Paso las fecha segun central-g a fechas segun mysql. Devuelvo una cadena.
	function fecha_dmy_ymd($fecha)
	{
		if(strlen($fecha != "10"))
		{
			list($dia, $mes, $anho) = explode('-', $fecha);
			return "$anho-$mes-$dia";
		}
		else
		{
			echo "<strong>Error #1</strong>: \"$fecha\" No es un formato de fecha valida.<br /></strong>";
			return "";
		}
	}
	
	
	//Deseo comparar dos fechas y saber cual es la mayor. Si fecha1 > fecha2 = true. Si fecha1 < fecha2 = false
	function fecha_mayor($fecha1, $fecha2)
	{
		$fecha1_v = $this->fecha_subdiv($fecha1);
		$fecha2_v = $this->fecha_subdiv($fecha2);
		
		if(
			mktime($fecha1_v["hora"],$fecha1_v["minuto"],0,$fecha1_v["mes"],$fecha1_v["dia"],$fecha1_v["anho"])
			>
			mktime($fecha2_v["hora"],$fecha2_v["minuto"],0,$fecha2_v["mes"],$fecha2_v["dia"],$fecha2_v["anho"])
		)
		{
			return true;//Si la fecha1 es mayor devuelvo verdadero: fecha1 > fecha2
		}
		else
		{
			return false;//Si la fecha2 es mayor devuelvo false: fecha1 < fecha2
		}
	}
	
	
	//Al ir hacia adelante es posible obtener un numero de dias invalido, entonces lo valido aqui
	function fecha_correcta($dia, $mes, $anho)
	{
		
		$salir = false;
		do
		{
			//Debo saber si no me paso del limite de dias validos para este mes
			$dias_mes = date("t",mktime(0,0,0,$mes,01,$anho));
			if($dia > $dias_mes)
			{// Si tengo mas dias de los que caben en este mes
				$mes++;//Debo pasar al siguiente mes
				if($mes > 12)
				{//Y si consigo mas meses de los que aguanta este anho
					$mes = 1;//Regreso el mes a enero
					$anho++;//Y paso al siguiente anho
				}
				$dia -= $dias_mes;//Resto el total de dias resultantes con el total de dias del mes
			}
			else
			{
				$salir = true;
			}
		}while(!$salir);
		
		if(strlen($mes) < 2)//Si el mes tiene un solo digito
		{
			$mes = "0".$mes;//Le pongo companhia
		}
		if(strlen($dia) < 2)//Si el dia tiene un solo digito
		{
			$dia = "0".$dia;//Le pongo algo para vacilar
		}
		
		return "$dia-$mes-$anho";
		
	}
	
	
	//Al ir hacia atras es posible obtener un numero de dias invalido, entonces lo valido aqui
	function fecha_rcorrecta($dia, $mes, $anho)
	{
		
		$salir = false;
		do
		{
			if($dia < 1)
			{//Si es un numero de dias no valido: 0 o -2 p.ej.
				$mes--;//Disminuyo el mes
				if($mes < 1)
				{//Si el mes es menor que uno
					$mes = 12;//Es diciembre
					$anho--;//Un anho anterior
				}
				//Debo saber cuantos dias tiene este mes para hacer un reasignamiento :S
				$dias_mes = date("t",mktime(0,0,0,$mes,01,$anho));
				$dia += $dias_mes;//Le sumo la cantidad de dias al resultado actual
			}
			else
			{
				$salir = true;
			}
		}while(!$salir);
		
		if(strlen($mes) < 2)//Si el mes tiene un solo digito
		{
			$mes = "0".$mes;//Le pongo el cero para que no este solito
		}
		if(strlen($dia) < 2)//Si el dia tiene un solo digito
		{
			$dia = "0".$dia;//No es justo que este solito tampoco
		}
		
		return "$dia-$mes-$anho";
		
	}
	
	
	//Deseo avanzar en el tiempo. Devuelvo un array.
	function fecha_avanzar($dia, $mes, $anho, $cuanto)
	{
		
		$dia += $cuanto;//Aumento el dia
		
		//Si el numero de dias fue incoherente con la realidad lo mando a arreglar
		list($dia, $mes, $anho) = explode('-', $this->fecha_correcta($dia, $mes, $anho));
		$fecha_avanzar = array();
		$fecha_avanzar["dia"] = $dia;
		$fecha_avanzar["mes"] = $mes;
		$fecha_avanzar["anho"] = $anho;
		
		return $fecha_avanzar;
		
	}
	
	
	//Deseo retroceder en el tiempo. Devuelvo un array.
	function fecha_retroceder($dia, $mes, $anho, $cuanto)
	{
		
		$dia -= $cuanto;//Disminuyo el dia
		
		//Si el numero de dias fue incoherente con la realidad lo mando a arreglar
		list($dia, $mes, $anho) = explode('-', $this->fecha_rcorrecta($dia, $mes, $anho));
		$fecha_retroceder = array();
		$fecha_retroceder["dia"] = $dia;
		$fecha_retroceder["mes"] = $mes;
		$fecha_retroceder["anho"] = $anho;
		
		return $fecha_retroceder;
		
	}
	
	
	//Deseo saber que fecha inicia la semana
	function inicio_semana($dia, $mes, $anho)
	{
		
		$milisegundos = mktime(0,0,1,$mes,$dia,$anho);
		$hoy = date("w",$milisegundos);//Busco el identificador del dia en busqueda: 0-6
		if($hoy == 0)//Si es Domingo me dara 0, pero necesito iniciar la semana con Lunes
		{
			$hoy = 7;//Asi que le pongo valor de 7 y eso indica que hace 7 dias inicio la semana :)
		}
		
		$hoy--;//Resto uno porque: si es lunes no debo restar valor alguno ya que ya llegue por default y si es domingo le resto 6 p.ej. dom 24 - 6 = lun 18 :S
		$dia -= $hoy;//A la fecha recibida le resto lo que ha avanzado la semana para regresar al lunes, sino estoy ya en el
		list($dia, $mes, $anho) = explode('-', $this->fecha_rcorrecta($dia, $mes, $anho));//Si el numero de dias fue incoherente con la realidad aqui se va a arreglar
		
		$inicio_semana = array();
		$inicio_semana["dia"] = $dia;
		$inicio_semana["mes"] = $mes;
		$inicio_semana["anho"] = $anho;
		
		return $inicio_semana;
		
	}
	
	
	//Calcular fecha de finalizacion de un trabajo
	function fin_trabajo($fecha_inicio, $tiempo_programado)
	{
		
		$f_f = $this->fecha_subdiv($fecha_inicio);//Valores dividios de la fecha de ultima prioridad en la lista :)
		
		$minutos_fecha = ($f_f["hora"] * 60) + $f_f["minuto"];//El tiempo en que iniciara el trabajo lo paso a minutos
		$minutos_programados = $tiempo_programado * 60;//El tiempo programado lo paso a minutos
		$minutos = $minutos_fecha + $minutos_programados;//Sumo ambos valores para saber hasta cuando llega el trabajo
		
		$salir = false;
		
		do
		{
			if($minutos > 1439)
			{//Si tuviera 1440 minutos significa que son las 24:00 es decir las 00:00 del siguiente dia
				$minutos -= 1439;//Le resto las 24 horas del dia en que inicia para que queden solo los minutos del dia que debe finalizar
				$f_a = $this->fecha_avanzar($f_f["dia"], $f_f["mes"], $f_f["anho"], 1);//Avanzo un dia
				$f_f["dia"] = $f_a["dia"]; $f_f["mes"] = $f_a["mes"]; $f_f["anho"] = $f_a["anho"];//Actualizo la fecha de fin
			}
			else
			{
				$salir = true;
			}
		}while(!$salir);
		
		$f_f["hora"] = floor($minutos / 60);
		if($f_f["hora"] < 10)//Si la hora es menor que 10
		{
			$f_f["hora"] = "0".$f_f["hora"];
		}
		$f_f["minuto"] = $minutos % 60;
		if($f_f["minuto"] < 10)//Si el minuto es menor que 10
		{
			$f_f["minuto"] = "0".$f_f["minuto"];
		}
		
		$fecha_fin = $f_f["anho"]."-".$f_f["mes"]."-".$f_f["dia"]." ".$f_f["hora"].":".$f_f["minuto"].":00";
		
		return $fecha_fin;
		
	}
	
	
	//Recibe minutos y los convierte a hora
	function minutos_a_hora($hora_decimal)
	{
		$hora = intval($hora_decimal / 60);
		$minutos = $hora_decimal % 60;
		if(10 > $minutos)
		{
			$minutos = '0'.$minutos;
		}
		
		return $hora.':'.$minutos;
		
	}
	
	
	//Recibe hora y los convierte a minutos
	function hora_a_minutos($hora)
	{
		
		list($hora, $minutos) = explode(':', $hora);
		if($minutos > 60)
		{
			$minutos = 0;
			$hora++;
		}
		$Decimal = $hora * 60;
		
		$Decimal += $minutos;
		
		return $Decimal;
		
	}
	
	
	//Convierte la hora de decimal a formato hh:mm
	function hora_convertir($horas)
	{
		
		list( $hora, $minutos ) = explode( '.', number_format($horas, 2) );
		$minutos = "0.$minutos";
		$minutos = floor($minutos * 60);
		if($minutos < 10)
		{
			$minutos = "0$minutos";
		}
		
		return("$hora:$minutos");
		
	}
	
	
	//Convierte la hora de formato hh:mm a decimal
	function hora_rconvertir($horas)
	{
		
		list( $hora, $minutos ) = explode( ':', $horas );
		if($minutos > 60)
		{
			$minutos = 0; $hora++;
		}
		$minutos = $minutos / 60;
		$hora_d = number_format($hora + $minutos, 2);
		
		return $hora_d;
		
	}
	
	
	//Calcula la fecha de inicio y fin de un trabajo dividiendo en segmentos de horas habiles. Regresa un Array con las divisiones. $fechas_fin_v[] = array("$fecha_inicio", "$tiempo");
	function inicio_fin_trabajo(
		$Fecha_Inicio,
		$Tiempo_Asignado,
		$Tiempo_Habil,
		$Id_Usuario,
		$Limite_Programa
	)
	{
		
		/**
		 *Fecha de inicio.
		 *Tiempo Asignado.
		 *Departamento.
		 *Usuario.
		 *Extras por Usuario.
		 *
		 *El tiempo de inicio debe estar en tiempo habil.
		 *Tiempo de inicio sera la fecha y hora de fin del ultimo trabajo asignado siempre y cuando sea mayor o igual que la fecha y hora actual. Si no posee trabajos se tomara la fecha y hora actual.
		 *Verificar horarios por operador y si posee horas extras.
		 *No considerar las 12 del dia.
		 *Dividir los segmentos  y regresarlo para guardarlos en la base de datos.
		 *
		 *Tabla: pedido_cronograma
		 *id_pedido_cronograma
		 *id_pedido
		 *id_usuario
		 *fecha_inicio
		 *fecha_fin
		 *segmentos
		 *
		 *Tabla actualizable:
		 *-Si se aumenta o disminuye el tiempo asignado.
		 *-Si se inicia o pausa un trabajo.
		 *-Si se agrega tiempo extra.
		 *
		 *Segmentos:
		 *{segmentos: [{inicio,tiempo},{inicio,tiempo},{inicio,tiempo},{inicio,tiempo}]}
		 *
		 *
		 *
		 *Tabla: tiempo_habil_dpto
		 *id_tiempo_habil_dpto
		 *id_grupo
		 *id_dpto
		 *tiempo_habil_grupo
		 *hora_inicio
		 *
		 *(Arte)
		 *id_tiempo_habil: 1
		 *id_grupo: 1
		 *id_dpto: 2
		 *tiempo_habil_grupo: 8
		 *hora_inicio: 8
		 *
		 *(Fotopolimeros)
		 *id_tiempo_habil: 2
		 *id_grupo: 1
		 *id_dpto: 9
		 *tiempo_habil_grupo: 22
		 *hora_inicio: 8
		 *
		 *
		 *
		 *
		 *Tabla: Feriados
		 *id_dia_feriado
		 *dia_inicio
		 *dia_fin
		 *activo.
		 *
		*/
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*
		$salir = false;//Mientras no se llegue a una hora exacta no puede salir del bucle
		$tiempo_temp = $tiempo_asignado;
		$fecha_hoy = date("Y-m-d H:i:s");
		//$tfin_v = array("12", "17", "20");
		$tinicio_v = array("08", "13", "06");
		$fechas_fin_v = array();
		
		do
		{
			$f = fecha_subdiv($fecha_inicio);
			//Necesito saber si este trabajo tiene extra
			$hora_fin = "17:00";
			if($ide_dpto == 9)
			{
				$hora_fin = "20:00";
			}
			
			if($ide_dpto == 9)
			{
				$fin_tra = $this->fin_trabajo($fecha_inicio, $tiempo_temp);
				$f_t = $this->fecha_subdiv($fin_tra);
				
				if($this->fecha_mayor($fin_tra, $f["anho"]."-".$f["mes"]."-".$f["dia"]." $hora_fin:00"))
				{
					list($horas_f, $minutos_f) = explode(':', $hora_fin);
					$minutos_para_recortar = ($horas_f * 60) + $minutos_f;
					$tiempo_recortado = number_format(($minutos_para_recortar - (($f["hora"] * 60) + $f["minuto"])) / 60, 2);
					$tiempo_temp -= $tiempo_recortado;
					$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_recortado");
					$f_i = $this->fecha_avanzar($f["dia"],$f["mes"],$f["anho"], 1);
					$milisegundos = mktime(0,0,1,$f_i["mes"],$f_i["dia"],$f_i["anho"]);
					$hoy = date("w",$milisegundos);//Busco el identificador del dia en busqueda: 0-6
					if($hoy == 0)
					{
						$f_i = $this->fecha_avanzar($f_i["dia"],$f_i["mes"],$f_i["anho"], 1);
					}
					$fecha_inicio = $f_i["anho"]."-".$f_i["mes"]."-".$f_i["dia"]." 06:00:00";
				}
				else
				{
					$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_temp");
					$salir = true;
					$fecha_inicio = $fin_tra;
				}
				
				continue;
			}
			
			if($f["hora"] >= $tinicio_v[0] && $f["hora"] <= 12)
			{//Si el trabajo inicia en la ma�ana
				$fin_tra = $this->fin_trabajo($fecha_inicio, $tiempo_temp);
				$f_t = $this->fecha_subdiv($fin_tra);
				if($this->fecha_mayor($fin_tra, $f["anho"]."-".$f["mes"]."-".$f["dia"]." 12:00:00"))
				{
					$tiempo_recortado = number_format((720 - (($f["hora"] * 60) + $f["minuto"])) / 60, 2);
					$tiempo_temp -= $tiempo_recortado;
					$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_recortado");
					$milisegundos = mktime(0,0,1,$f["mes"],$f["dia"],$f["anho"]);
					$hoy = date("w",$milisegundos);//Busco el identificador del dia en busqueda: 0-6
					if($hoy == 6)
					{
						$f_i = $this->fecha_avanzar($f["dia"],$f["mes"],$f["anho"], 2);
						$fecha_inicio = $f_i["anho"]."-".$f_i["mes"]."-".$f_i["dia"]." 08:00:00";
					}
					else
					{
						$fecha_inicio = $f["anho"]."-".$f["mes"]."-".$f["dia"]." 13:00:00";
					}
				}
				else
				{
					$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_temp");
					$salir = true;
					$fecha_inicio = $fin_tra;
				}
				
				continue;
			}
			
			if($f["hora"] >= $tinicio_v[1] && $f["hora"] <= $hora_fin)
			{//Si el trabajo inicia en la tarde
				$fin_tra = $this->fin_trabajo($fecha_inicio, $tiempo_temp);
				$f_t = $this->fecha_subdiv($fin_tra);
				if($this->fecha_mayor($fin_tra, $f["anho"]."-".$f["mes"]."-".$f["dia"]." $hora_fin:00"))
				{
					list($horas_f, $minutos_f) = explode(':', $hora_fin);
					$minutos_para_recortar = ($horas_f * 60) + $minutos_f;
					$tiempo_recortado = number_format(($minutos_para_recortar - (($f["hora"] * 60) + $f["minuto"])) / 60, 2);
					$tiempo_temp -= $tiempo_recortado;
					$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_recortado");
					$f_i = $this->fecha_avanzar($f["dia"],$f["mes"],$f["anho"], 1);
					$fecha_inicio = $f_i["anho"]."-".$f_i["mes"]."-".$f_i["dia"]." 08:00:00";
				}
				else
				{
					$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_temp");
					$salir = true;
					$fecha_inicio = $fin_tra;
				}
				
				continue;
			}
			
			//$fin_tra = fin_trabajo($fecha_inicio, $tiempo_temp);
			$fechas_fin_v[] = array("$fecha_inicio", "$tiempo_temp");
			$salir = true;
		}while(!$salir);
		
		return $fechas_fin_v;
		//mysql_close($con);
		
		*/
		
	}
	
	
	//Deseo saber si este trabajo va a iniciar en horas habiles y en dias habiles, segun el departamento. Devuelve la fecha corregida (si fuere necesario).
	function fecha_sabdom_hora($fecha_inicio, $ide_dpto)
	{
		
		$fecha = $this->fecha_subdiv($fecha_inicio);
		
		
		
		if($fecha["hora"] >= 17)
		{
			if($this->hoy_es($fecha_inicio) < 6)
			{
				$f_n = $this->fecha_avanzar($fecha["dia"], $fecha["mes"], $fecha["anho"], 1);
			}
			if($this->hoy_es($fecha_inicio) == 6)
			{
				$f_n = $this->fecha_avanzar($fecha["dia"], $fecha["mes"], $fecha["anho"], 2);
			}
			if($this->hoy_es($fecha_inicio) == 7)
			{
				$f_n = $this->fecha_avanzar($fecha["dia"], $fecha["mes"], $fecha["anho"], 1);
			}
			$fecha_inicio = $f_n["anho"]."-".$f_n["mes"]."-".$f_n["dia"]." 08:00:01";
		}
		
		if($fecha["hora"] < 8)
		{
			if($this->hoy_es($fecha_inicio) == 7)
			{
				$f_n = $this->fecha_avanzar($fecha["dia"], $fecha["mes"], $fecha["anho"], 1);
				$fecha_inicio = $f_n["anho"]."-".$f_n["mes"]."-".$f_n["dia"]." 08:00:01";
			}
			else
			{
				$fecha_inicio = $fecha["anho"]."-".$fecha["mes"]."-".$fecha["dia"]." 08:00:01";
			}
		}
		
		
		
		return $fecha_inicio;
		
	}
	
	
	//Yo se que dia es hoy. 1 Lunes... 7 Domingo.
	function hoy_es($fecha_base)
	{
		
		$fecha = $this->fecha_subdiv($fecha_base);
		$milisegundos = mktime(0,0,1,$fecha["mes"],$fecha["dia"],$fecha["anho"]);
		$hoy = date("w",$milisegundos);//Busco el identificador del dia en busqueda: 0-6   -   0 (for Sunday) through 6 (for Saturday)
		if($hoy == 0) $hoy = 7;
		
		return $hoy;
		
	}
	
	
	//Voy a convertir en segundos la fecha proporcionada
	function fecha_a_segundos($fecha)
	{
		$fecha_v = $this->fecha_subdiv($fecha);
		$segundos = mktime(
			$fecha_v["hora"],
			$fecha_v["minuto"],
			0
			,
			$fecha_v["mes"],
			$fecha_v["dia"],
			$fecha_v["anho"]
		);
		return $segundos;
	}
	
	
	//Debo saber cuantos minutos tardo en realizarse este trabajo
	function minutos_de_trabajo($Inicio, $Fin)
	{
		
		//Deseo saber los segundos correspondientes a las fechas y horas de inicio y fin
		$Inicio = $this->fecha_subdiv($Inicio);
		$Inicio = mktime($Inicio["hora"],$Inicio["minuto"],0,$Inicio["mes"],$Inicio["dia"],$Inicio["anho"]);
		$Fin = $this->fecha_subdiv($Fin);
		$Fin = mktime($Fin["hora"],$Fin["minuto"],0,$Fin["mes"],$Fin["dia"],$Fin["anho"]);
		
		//Cuantos segundos quedan entre ambos rangos
		$Segundos = $Fin - $Inicio;
		//Conversion a minutos
		$Minutos = number_format(($Segundos / 60), 0, '', '');
		
		if(0 > $Minutos)
		{
			$Minutos = 0;
		}
		
		//Regreso los minutos
		return $Minutos;
		
	}
	
	
	//Recibo dos fechas y calculo el tiempo habil que se utilizo entre el rango dado
	function tiempo_habil($Fecha_Inicio, $Fecha_Fin){
		
		$Minutos = 0;
		
		//Fecha_Inicio
		$F_I = $this->fecha_subdiv($Fecha_Inicio);
		/*if(intval($F_I["hora"]) < 8){
			$F_I["hora"] = "08";
			$F_I["minuto"] = "00";
		}*/
		//Fecha_Fin
		$F_F = $this->fecha_subdiv($Fecha_Fin);
		
		
		
		if(//Si regresa verdadero es que es el mismo dia
			$this->fecha_mayor(
				$F_I["anho"]."-".$F_I["mes"]."-".$F_I["dia"]." 01:00:01",
				$F_F["anho"]."-".$F_F["mes"]."-".$F_F["dia"]." 00:00:01"
			)
		)
		{
			//echo 'Mismo dia<br />';
			//Se convierten a segundo las fechas para hacer una resta
			$Inicio = $this->fecha_a_segundos(
				$F_I["anho"]."-".$F_I["mes"]."-".$F_I["dia"]." ".$F_I["hora"].":".$F_I["minuto"].":".$F_I["segundo"]
			);
			$Fin = $this->fecha_a_segundos($Fecha_Fin);
			
			//Se resta de la fecha final la de inicio y se convierte a minutos
			$Minutos = ($Fin - $Inicio) / 60;
			
			//Si el rango de tiempo incluia el medio dia, debe restarse la hora
			/*if($F_I["hora"] < 12 && $F_F["hora"] > 12)
			{
				$Minutos -= 60;
			}
			
			//Si el tiempo iniciaba al medio dia se le resta la hora
			if(12 == $F_I["hora"])
			{
				$Minutos -= (60 - $F_I["minuto"]);
			}
			
			//Si el tiempo finalizo a medio dia, se le restan los minutos que pasaron
			if(12 == $F_F["hora"])
			{
				$Minutos -= $F_F["minuto"];
			}
			
			if(17 <= $F_F['hora'])
			{
				$Minutos -= $F_F['minuto'];
				//Cuantas horas pasa de las cinco?
				$Horas_Pasadas = ($F_F['hora'] - 17) * 60;
				$Minutos -= $Horas_Pasadas;
			}*/
			
			//Si resulta un numero negativo, pongo el contador a cero
			if(0 > $Minutos)
			{
				$Minutos = 0;
			}
			
			//echo 'Minutos: '.$Minutos.'<br />';
			
		}
		else
		{
			//echo 'Otro dia<br />';
			//La fecha de inicio y fin no corresponden al mismo dia
			
			//Paso a segundos las fechas, para saber cuantos dias han sido utilizados
			$Inicio = $this->fecha_a_segundos(
				$F_I["anho"]."-".$F_I["mes"]."-".$F_I["dia"]." 00:00:01"
			);
			$Fin = $this->fecha_a_segundos(
				$F_F["anho"]."-".$F_F["mes"]."-".$F_F["dia"]." 00:00:01"
			);
			
			//Los segundos se pasan a dias, asi recorro uno por uno para tomar sus horas habiles
			$Tiempo_Usado = ceil(($Fin - $Inicio) / 86400);
			//echo 'Tiempo Usado: '.$Tiempo_Usado.'<br />';
			
			
			//Se recorre dia por dia para ver el tiempo habil para cada uno
			for($i = 0; $i <= $Tiempo_Usado; $i++)
			{
				
				//En minutos se indica que el dia termina a las 24:59
				//Aplica de lunes a viernes.
				$Termina_Dia = 1439;
				
				//Deseo saber que dia hoy, asi se sabra hasta que hora para el conteo de minutos
				//Se toma como base la fecha de inicio y se le aumentan los dias que correspondan
				$hoy_es = $this->fecha_avanzar(
					$F_I["dia"], $F_I["mes"], $F_I["anho"], $i
				);
				/*echo '<br />Hoy es: ';
				print_r($hoy_es);
				echo '<br />';
				//Si esta fecha es domingo
				/*echo 'Dia de la semana: '.$this->hoy_es(
					$hoy_es["anho"]."-".$hoy_es["mes"]."-".$hoy_es["dia"]." 00:00:01"
				).'<br />';*/
				if(
					$this->hoy_es(
						$hoy_es["anho"]."-".$hoy_es["mes"]."-".$hoy_es["dia"]." 00:00:01"
					) == 7
				)
				{
					//echo 'Soy Domingo<br />';
					//No se cuentan los minutos
					continue;
				}
				
				//Si esta fecha es sabado
				if(
					$this->hoy_es(
						$hoy_es["anho"]."-".$hoy_es["mes"]."-".$hoy_es["dia"]." 00:00:01"
					) == 6
				){
					//Solo se cuenta hasta medio dia
					$Termina_Dia = 720;
				}
				
				//Si es el dia de inicio
				if($i == 0)
				{
					//Se calculan los minutos utilizadas este dia a partir de la hora de inicio
					$Minutos_Actuales = ($F_I["hora"] * 60) + $F_I["minuto"];
					//Al total de minutos habiles, le resto los minutos en que inicio
					//y obtengo los minutos utilizados.
					//Ej.: $Minutos_Actuales = 1020 (Lunes) - 528 = 492 minutos utilizados.
					$Minutos_Actuales = $Termina_Dia - $Minutos_Actuales;
					
					//Si el trabajo inicio antes del medio dia
					/*if($F_I["hora"] < 12)
					{
						//Se resta la hora
						$Minutos_Actuales -= 60;
					}
					
					//Si el tiempo inicio a las doce
					if($F_I["hora"] == 12)
					{
						//Se restan los minutos que tomo de esa hora
						$Minutos_Actuales -= (60 - $F_I["minuto"]);
					}*/
					
					//Si la cuenta de minutos es menor que cero
					if($Minutos_Actuales < 0)
					{
						//Se asigna a cero
						$Minutos_Actuales = 0;
					}
					//Se aumenta el conteo global de minutos
					$Minutos += $Minutos_Actuales;
					//echo 'Actuales: '.$Minutos_Actuales.'<br />';
					//echo 'Minutos: '.$Minutos.'<br />';
					
					//Se pasa al siguiente dia
					continue;
				}
				
				//Si es el dia final
				if($i == $Tiempo_Usado)
				{
					//Se calculan los minutos de este dia
					$Minutos_Actuales = ($F_F["hora"] * 60) + $F_F["minuto"];
					//Se le restan las ocho horas de la manhana
					//$Minutos_Actuales = $Minutos_Actuales - 480;
					/*
					if($F_F['hora'] >= 17)
					{
						$Minutos_Actuales -= $F_F['minuto'];
						//Cuantos minutos se pasa despues de las cinco?
						$Horas_Pasadas = ($F_F['hora'] - 17) * 60;
						$Minutos_Actuales -= $Horas_Pasadas;
					}
					
					//Si el tiempo finaliza despues del medio dia
					if($F_F["hora"] > 12)
					{
						//Se le quita la hora
						$Minutos_Actuales -= 60;
					}
					
					//Si termina a medio dia
					if($F_F["hora"] == 12)
					{
						//Se le restan los minutos de esa hora
						$Minutos_Actuales -= $F_I["minuto"];
					}*/
					
					//Si queda insuficiente de minutos
					if($Minutos_Actuales < 0)
					{
						//Se asigna a cero
						$Minutos_Actuales = 0;
					}
					
					//Se aumenta el contador global de minutos
					$Minutos += $Minutos_Actuales;
					//echo 'Actuales: '.$Minutos_Actuales.'<br />';
					//echo 'Minutos: '.$Minutos.'<br />';
					
					//Se pasa al siguiente dia (Se finaliza el bucle)
					continue;
				}
				
				
				//En el caso que el bucle pase por un dia intermedio entre la fecha de inicio
				//y fin, deseo saber si es lunes - viernes o sabado
				
				//Si esta fecha es sabado
				if(
					$this->hoy_es(
						$hoy_es["anho"]."-".$hoy_es["mes"]."-".$hoy_es["dia"]." 00:00:01"
					) == 6
				)
				{
					//Se agregan al contador global los minutos correspondientes a 8 horas
					$Minutos += 720;
					//echo 'Actuales: 720<br />';
				}
				else
				{
					
					//Se agregan al contador global los minutos correspondientes a 24 horas
					$Minutos += 1439;
					//echo 'Actuales: 1439<br />';
				}
				
				
				//echo 'Minutos: '.$Minutos.'<br />';
				
			}
		}
		
		
		//Si los minutos quedaron insuficientes
		if(0 > $Minutos)
		{
			//Se asigna a cero
			$Minutos = 0;
		}
		
		return $Minutos;
	}
	
	
}
/* Fin del archivo */


