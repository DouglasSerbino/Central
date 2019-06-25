<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguridad_m extends CI_Model {
	
	/**
	 *Clase encriptadora invencion mia,
	 *si ya existia en algun lado no lo se,
	 *pero de tanto dar vueltas a la imaginacion se me ocurrio esto.
	 *Para entender su funcionamiento te invito a leer cada metodo.
	 *Un tip: Este metodo debe ser usado solamente con cadenas pequenhas.
	*/
	
	function __construct()
	{
		parent::__construct();
		
		/*
		 Estoy dudando si es necesaria esta funcion
		if(FALSE === $this->session->userdata('letras_numeros'))
		{
			$this->letras_shuffled();
		}
		*/
		
		if('AL')
		{
			$this->session->set_userdata(
				array('temporizador' => strtotime('now'))
			);
		}

		if('pie' != $this->uri->segment(1))
		{
			if(
				'' != $this->session->userdata('temporizador')
				&& 3600 < (strtotime('now') - $this->session->userdata('temporizador'))
			)
			{
				$this->session->sess_destroy();
			}

			$this->session->set_userdata(
				array('temporizador' => strtotime('now'))
			);
		}

	}
	
	
	/**
	 *Creo una secuencia de letras y numeros en orden aleatorio y lo almaceno en sesiones
	*/
	function letras_shuffled(){
		
		/**Voy a generar claves aleatorias cada vez que entre un usuario,
		 *espero me funcione.
		 *Primero creo un array con todas las letras permitidas,
		 *cada una ordenada en una posicion de cero a uno con las letras ordenadas
		*/
		
		$Letras_Numeros = array(
			'A','B','C','D','E','F','G','H','I','J',
			'K','L','M','N','O','P','Q','R','S','T',
			'U','V','W','X','Y','Z','a','b','c','d',
			'e','f','g','h','i','j','k','l','m','n',
			'o','p','q','r','s','t','u','v','w','x',
			'y','z','0','1','2','3','4','5','6','7',
			'8','9','_', '/'
		);
		
		
		//Asigno a una nueva variable el array anterior
		$Letras_Numeros_Shuffled = $Letras_Numeros;
		//Luego cambio su orden nuevamente de manera aleatoria
		//pues me va a servir como metodo de encriptacion, jejejeje
		shuffle($Letras_Numeros_Shuffled);
		
		//Creo una session por cada variable con la que realizare
		//el trabajo de encriptar las claves y los nombres
		$Sesiones = array(
			'letras_numeros' => $Letras_Numeros,
			'letras_numeros_shuffled' => $Letras_Numeros_Shuffled
		);
		$this->session->set_userdata($Sesiones);
		
		//Para que sirve? Sigue mirando--->>>>
	}
	
	/**
	 *Encripcion de manera personalizada los datos del usuario.
	 *IMPORTANTE: Nunca se te ocurra guardar en la base de datos
	 *algo encriptado con esta funcion, sino pobre de vos!
	 *@param string $Cadena_a_Encriptar Cadena que el usuario desea encriptar
	 *@return string La cadena de texto con la encripcion aplicada
	*/
	function encriptar($Cadena_a_Encriptar){
		/**
		 *La manera de realizar esto es la siguiente:
		 *Avanzare en la cadena proporcionada caracter
		 *por caracter para cambiar el index normal al index shuffled
		*/
		
		//Cuantas letras hay?
		$Total_Letas = strlen($Cadena_a_Encriptar);
		//Cadena con el texto encriptado
		$Cadena_Encriptada = '';
		
		$Array_Shuffled = $this->session->userdata('letras_numeros_shuffled');
		
		//Recorrere letra por letra la cadena para hacer la encripcion
		for($i = 0; $i < $Total_Letas; $i++){
			//Deseo saber el index de esta letra en el array que contiene
			//las letras normales (en orden)
			$Index_Array_Normal = array_search(
				substr($Cadena_a_Encriptar, $i, 1),
				$this->session->userdata('letras_numeros')
			);
			//Le pongo la letra que esta en la misma posicion en el array con las letras aleatorias
			$Cadena_Encriptada .= $Array_Shuffled[$Index_Array_Normal];
		}
		
		//Regreso la nueva cadena
		$Cadena_Encriptada = base64_encode($Cadena_Encriptada);
		$Cadena_Encriptada = str_replace('=', '', $Cadena_Encriptada);
		return $Cadena_Encriptada;
	}
	
	/**
	 *Desencriptacion de la informacion
	 *@param string $Cadena_a_Desencriptar Cadena encriptada que deseamos pasar a texto legible
	 *@return string Cadena con el texto entendible
	*/
	function desencriptar($Cadena_a_Desencriptar){
		//La manera de realizar esto es la siguiente:
		//Avanzare en la cadena encriptada caracter por caracter para cambiar el index shuffled al index normal
		
		$Cadena_a_Desencriptar = base64_decode($Cadena_a_Desencriptar);
		
		//Cuantas letras hay?
		$Total_Letas = strlen($Cadena_a_Desencriptar);
		//Cadena con el texto desencriptados
		$Cadena_Desencriptada = '';
		
		$Array_Normal = $this->session->userdata('letras_numeros');
		
		//Recorrere letra por letra la cadena para hacer la desencriptacion (si es que se llama asi)
		for($i = 0; $i < $Total_Letas; $i++){
			//Obtengo el indice que ocupa esta letra en el array con las letras aleatorias
			$Index_Array_Shuffled = array_search(
				substr($Cadena_a_Desencriptar, $i, 1),
				$this->session->userdata('letras_numeros_shuffled')
			);
			//Le pongo la letra que esta en la misma posicion en el array con las letras normales
			$Cadena_Desencriptada .= $Array_Normal[$Index_Array_Shuffled];
		}
		
		//Regreso la nueva cadena
		return $Cadena_Desencriptada;
	}
	
	/**
	 *Fin de las funciones encriptadoras.
	 *Por favor apague las luces al salir.
	*/
	
	
	/**
	 *Caracteres raros en mi Base de Datos?
	 *No gracias!
	 */
	function mysql_seguro($Cadena)
	{
		
		//$Cadena = nl2br($Cadena);
		
		$Cadena = stripslashes($Cadena);
		
		//$Cadena = str_replace('<br />', '[br/]', $Cadena);
		
		$Cadena = htmlentities($Cadena, ENT_QUOTES, 'UTF-8');
		
		//$Cadena = str_replace('[br/]', '<br />', $Cadena);
		
		return $Cadena;
		
	}
	
}


/* Fin del archivo */
