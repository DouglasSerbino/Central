<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Almacenar_cotizacion extends CI_Controller {

	public function index()
	{

		if('' == $this->input->post('cg_companhia'))
		{
			header('location: /home/cotizar/index.html');
			exit();
		}


		//Aqui se realiza el almacenado de la cotizacion

		if(!isset($_FILES['cg_file']))
		{
			header('location: /home/cotizar/index.html');
			exit();
		}

		
		
		//Informacion necesaria del archivo a guardar
		$Archivo = $_FILES['cg_file']['tmp_name'];
		$Nombre  = $this->seguridad_m->mysql_seguro(
			$_FILES['cg_file']['name']
		);
		$Nombre = strtolower($Nombre);
		$Nombre = str_replace('&', '', $Nombre);
		$Nombre = str_replace('acute;', '', $Nombre);
		$Nombre = str_replace('tilde;', '', $Nombre);
		$Nombre = str_replace(';', '', $Nombre);



		$cg_companhia  = $this->seguridad_m->mysql_seguro(
			$this->input->post('cg_companhia')
		);
		$gc_nombre  = $this->seguridad_m->mysql_seguro(
			$this->input->post('gc_nombre')
		);
		$gc_email  = $this->seguridad_m->mysql_seguro(
			$this->input->post('gc_email')
		);
		$cg_comentario  = $this->seguridad_m->mysql_seguro(
			$this->input->post('cg_comentario')
		);
			
			
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
			
			
			$Ubicacion = './adjunto_cotizacion/'.$Nombre_Nuevo;//strtolower($Nombre_Nuevo);

			//echo $Ubicacion;
			//El archivo se mueve de la carpeta temporal a la final
			$mover = move_uploaded_file($Archivo, $Ubicacion);

			if($mover)
			{



				$Mensaje = '
					<br /><strong>Compa&ntilde;&iacute;a</strong>: '.$cg_companhia.'
					<br /><strong>Nombre</strong>: '.$gc_nombre.'
					<br /><strong>Email</strong>: '.$gc_email.'
					<br /><strong>Comentarios</strong>: '.nl2br($cg_comentario).'

					<br><br><strong>Archivo</strong>: <a href=\"/adjunto_cotizacion/'.$Nombre_Nuevo.'\" target=\"_blank\">'.$Nombre.'</a>
				';
				echo $Mensaje;

				//Se guardan los datos necesarios en la base de datos
				$Consulta = '
					insert into pedido_adjuntos values(
						NULL,
						"0",
						"no",
						"'.date('Y-m-d H:i:s').'",
						"cliente",
						"'.$Mensaje.'"
					)
				';
				//Para los archivos de los cliente se utiliza la tabla que ya existe
				//0 porque no pertenece a un pedido existente
				//no para indicar que no se ha revisado y resuelto
				//nombre del archivo aunque no tiene logica
				//cliente palabra clave que le ayuda al sistema a saber que estos adjuntos son diferentes
				//mensaje con la informacion del archivo
				$this->db->query($Consulta);
			}
			else
			{
				show_404();
				exit();
			}

		}
		else
		{
			show_404();
			exit();
		}



		header('location: /home/cotizar/realizado.html');
		
	}
}
