<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cargar_scan_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Guarda los archivos adjuntos en el servidor
	 *@param string $Id_Proceso.
	 *@param string $Id_Pedido.
	 *@param $scan_proceso nos servira cuando se quieran subir imagenes del proceso.
	 *@return string "ok"
	*/
	function cargar($Id_Proceso, $Id_Pedido = '', $num = 0)
	{
		$NombreA = array();
		if($Id_Pedido == 'imagen_proceso')
		{
			$contador = 1;
		}
		else
		{
			$contador = 3;
		}
		
		if(0 < $num)
		{
			$contador = $num;
		}
		//echo $contador;
		//Se recorren los tres campos de archivo recibidos
		for ($j = 0; $j < $contador; $j++)
		{
			
			if(!isset($_FILES['archivo_'.$j]))
			{
				continue;
			}
			
			//Informacion necesaria del archivo a guardar
			$Archivo = $_FILES['archivo_'.$j]['tmp_name'];
			$Mime_Type = $_FILES['archivo_'.$j]['type'];
			$Nombre  = $this->seguridad_m->mysql_seguro(
				$_FILES['archivo_'.$j]['name']
			);
			$Nombre = strtolower($Nombre);
			
			//Necesito saber la extension para ponerle un icono
			$Extension = explode('.', $Nombre);
			$Total_Partes = count($Extension);
			$Total_Partes--;
			
			
			$Tipo = 'varios';
			
			switch($Extension[$Total_Partes])
			{
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'gif':
				case 'tiff':
				case 'tif':
				case 'psd':
					$Tipo = 'imagen';
					break;
				case 'pdf':
				case 'ai':
					$Tipo = 'pdf';
					break;
				case 'zip':
				case 'rar':
				case '7zip':
					$Tipo = 'zip';
					break;
				case 'doc':
					$Tipo = 'texto';
					break;
				case 'xls':
					$Tipo = 'calculo';
					break;
				case 'ppt':
				case 'pps':
					$Tipo = 'presentacion';
					break;
				default:
					$Tipo = 'varios';
					break;
			}
			
			
			//Si hay informacion para este campo procedemos a procesarla
			if($Archivo != "")
			{
				
				//No renombrare el archivo con su nombre completo, puede ser muy largo
				$Longitud_Nombre = strlen($Nombre);
				//Por eso tomare unicamente las diez ultimas letras, en ellas se incluye
				//la extension
				$Ultimas_Diez = $Longitud_Nombre - 10;
				if(0 > $Ultimas_Diez)
				{
					$Ultimas_Diez = 0;
				}
				$Ultimas_Letras = substr($Nombre, $Ultimas_Diez, $Longitud_Nombre);
				
				//Nombre con el que sera guardado el archivo
				$Nombre_Nuevo = date('U').'-'.$Ultimas_Letras;
				$NombreA[] = $Nombre_Nuevo;
				if(!file_exists('./arc_adj_corp/'.$Id_Proceso))
				{
					mkdir('./arc_adj_corp/'.$Id_Proceso, 0777);
					chmod('./arc_adj_corp/'.$Id_Proceso, 0777);
				}
				
				if($Id_Pedido == 'imagen_proceso')
				{
					//Ubicacion en la que debe ser guardado el archivo
					$Ubicacion = './arc_adj_corp/'.$Id_Proceso.'/'.strtolower($Nombre_Nuevo);
				}
				else
				{
					//Ubicacion en la que debe ser guardado el archivo
					$Ubicacion = './arc_adj_corp/'.$Id_Proceso.'/'.$Id_Pedido.'/'.strtolower($Nombre_Nuevo);
					if(!file_exists('./arc_adj_corp/'.$Id_Proceso.'/'.$Id_Pedido))
					{
						mkdir('./arc_adj_corp/'.$Id_Proceso.'/'.$Id_Pedido, 0777);
						chmod('./arc_adj_corp/'.$Id_Proceso.'/'.$Id_Pedido, 0777);
					}
				}
				//echo $Ubicacion;
				//El archivo se mueve de la carpeta temporal a la final
				$mover = move_uploaded_file( $Archivo, $Ubicacion );
				
				//Cuando los archivos tienen guiones bajos se produce una mala visualizacion
				//en la pagina. Se reemplazaran por espacios
				$Nombre = str_replace('_', ' ', $Nombre);
				if($mover)
				{
					if($Id_Pedido == 'imagen_proceso')
					{
						//Se guardan los datos necesarios en la base de datos
						$Consulta = '
							insert into proceso_imagenes values(
								NULL,
								"'.$Id_Proceso.'",
								"/arc_adj_corp/'.$Id_Proceso.'/'.strtolower($Nombre_Nuevo).'",
								"'.$Nombre.'",
								"'.$Tipo.'",
								"'.$Mime_Type.'"
							)
						';
					}
					else
					{
						//Se guardan los datos necesarios en la base de datos
						$Consulta = '
							insert into pedido_adjuntos values(
								NULL,
								"'.$Id_Pedido.'",
								"/arc_adj_corp/'.$Id_Proceso.'/'.$Id_Pedido.'/'.strtolower($Nombre_Nuevo).'",
								"'.$Nombre.'",
								"'.$Tipo.'",
								"'.$Mime_Type.'"
							)
						';
					}
					//echo $Consulta;
					$this->db->query($Consulta);
				}
			}
		}
		
		if(0 < $num)
		{
			
			$Antecedentes = $this->seguridad_m->mysql_seguro(
				$this->input->post('antecedentes')
			);
			
			$Observaciones = $this->seguridad_m->mysql_seguro(
				$this->input->post('observaciones')
			);
			
			$Mejora = $this->seguridad_m->mysql_seguro(
				$this->input->post('mejora')
			);
			
			$Adicionales = $this->seguridad_m->mysql_seguro(
				$this->input->post('adicional')
			);
			
			$URL = "/arc_adj_corp/$Id_Proceso/$Id_Pedido/";
			$img0 = '';
			$img1 = '';
			$img2 = '';
			$img3 = '';
			$img4 = '';
			$img5 = '';
			if(isset($NombreA[0]))
			{
				$img0 = $URL.$NombreA[0];
			}
			if(isset($NombreA[1]))
			{
				$img1 = $URL.$NombreA[1];
			}
			if(isset($NombreA[2]))
			{
				$img2 = $URL.$NombreA[2];
			}
			if(isset($NombreA[3]))
			{
				$img3 = $URL.$NombreA[3];
			}
			if(isset($NombreA[4]))
			{
				$img4 = $URL.$NombreA[4];
			}
			if(isset($NombreA[5]))
			{
				$img5 = $URL.$NombreA[5];
			}
			
			
			$Consulta = '
			insert into pedido_adjunto_reproceso values(
				NULL,
				"'.$Id_Pedido.'",
				"'.$img0.'",
				"'.$img1.'",
				"'.$img2.'",
				"'.$img3.'",
				"'.$img4.'",
				"'.$img5.'",
				"'.$Antecedentes.'",
				"'.$Observaciones.'",
				"'.$Mejora.'",
				"'.$Adicionales.'"
				)
			';
			
			$this->db->query($Consulta);
		}
	
		
	}
}

/* Fin del archivo */