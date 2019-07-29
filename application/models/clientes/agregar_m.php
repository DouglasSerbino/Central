<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a las divisiones
	 *@return array: Array con la informacion de lo tipos de divisiones.
	*/
	function buscar_tipo_cliente()
	{
		
		//Consultamos la base de datos para obtener la informacion de las divisiones.
		$Consulta = '
			select *
			from cliente_tipo
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los tipos de clientes encontradas
		return $Resultado->result_array();
		
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada a los vendedores
	 *@return array: Array con la informacion de los vendedores existentes..
	*/
	function buscar_vendedor()
	{
		
		//Consultamos la base de datos para obtener la informacion de las divisiones.
		$Consulta = '
			select id_usuario, usuario, nombre
			from usuario usu, departamentos dpto
			where usu.id_dpto = dpto.id_dpto and departamento = "Ventas"
			and usu.id_grupo ="'.$this->session->userdata["id_grupo"].'"
			and usu.activo = "s"
		';
		
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los tipos de clientes encontradas
		return $Resultado->result_array();
		
	}
	
	
	/**
	 *Agregar clientes al sistema.
	 *Agregaremos los clientes que sean requeridos en el sistema.
	 *@return string: 'ok' si el cliente se ingresa exitosamente
	 *@return string: 'error' si no se puede guardar el cliente.
	*/
	function guardar_datos(
		$Id_grupo,
		$Nombre,
		$Codigo_cliente,
		$Direccion,
		$NIT,
		$Web,
		$Credito,
		$Contactos_v,
		$Maquinas_v,
		$Aniloxes_v,
		$Planchas_v,
		$Productos_v,
		$CPais
	)
	{
		
		$Consulta = '
			select id_cliente
			from cliente
			where codigo_cliente= "'.$Codigo_cliente.'"
			and id_grupo = "'.$Id_grupo.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		//Si la consulta obtuvo resultados
		if(1 == $Resultado->num_rows())
		{
			return 'existe';
		}
		else
		{//Si no existe el cliente.
			
			$alea_usu = rand(100,999);// Se genera un numero aleatorio para asignar un usuario
			$alea_contra = rand(1000,9999);// Se genera un numero aleatorio para asignar un usuario
			$usuario = $Codigo_cliente.$alea_usu;
			$contrasena = $alea_contra;
			
			//Creamos la consulta para guardar los registros en la base de datos.
			$Consulta_sql = '
				INSERT INTO cliente values(
					NULL,
					"'.$Codigo_cliente.'",
					"'.$Nombre.'",
					"",
					"'.$usuario.'",
					"'.$contrasena.'",
					"s",
					"'.$Id_grupo.'",
					"'.$Direccion.'",
					"'.$NIT.'",
					"'.$Web.'",
					"'.$Credito.'",
					"'.$CPais.'"
				)
			';
			
			//echo $Consulta;
			//Ejecuto la consulta
			$Resultado_cons = $this->db->query($Consulta_sql);

			//Obtener el id del cliente
			$Id_Cliente = $this->db->insert_id();

			
			//Ingresar los contactos
			if(0 < count($Contactos_v))
			{
				
				$Consulta = array();

				foreach($Contactos_v as $Datos)
				{
					
					$Consulta[] = '
						(
							NULL,
							"'.$Id_Cliente.'",
							"'.$Datos['nomb'].'",
							"'.$Datos['carg'].'",
							"'.$Datos['emai'].'",
							"'.$Datos['tofi'].'",
							"'.$Datos['tdir'].'",
							"'.$Datos['tcel'].'"
						)
					';

				}

				$Consulta = '
					insert into cliente_contacto values '.implode(', ', $Consulta).'
				';
				$this->db->query($Consulta);

			}
			
			
			//Ingresar las Maquinas
			if(0 < count($Maquinas_v))
			{
				
				$Consulta = array();

				foreach($Maquinas_v as $Datos)
				{
					
					$Consulta[] = '
						(
							NULL,
							"'.$Id_Cliente.'",
							"'.$Datos['maqu'].'",
							"'.$Datos['colo'].'"
						)
					';

				}

				$Consulta = '
					insert into cliente_maquina values '.implode(', ', $Consulta).'
				';
				$this->db->query($Consulta);
				
			}
			
			
			//Ingresar las Planchas
			if(0 < count($Planchas_v))
			{
				
				$Consulta = array();

				foreach($Planchas_v as $Datos)
				{
					
					$Consulta[] = '
						(
							NULL,
							"'.$Id_Cliente.'",
							"'.$Datos['altu'].'",
							"'.$Datos['line'].'",
							"'.$Datos['marc'].'"
						)
					';

				}

				$Consulta = '
					insert into cliente_placa values '.implode(', ', $Consulta).'
				';
				$this->db->query($Consulta);
				
			}
			
			
			//Ingresar los Anilox
			if(0 < count($Aniloxes_v))
			{
				
				$Consulta = array();

				foreach($Aniloxes_v as $Datos)
				{
					
					$Consulta[] = '
						(
							NULL,
							"'.$Id_Cliente.'",
							"'.$Datos['ani'].'",
							"'.$Datos['bcm'].'",
							"'.$Datos['can'].'"
						)
					';

				}

				$Consulta = '
					insert into cliente_anilox values '.implode(', ', $Consulta).'
				';
				$this->db->query($Consulta);

			}
			
			
			//Ingresar los Productos
			if(0 < count($Productos_v))
			{
				
				$Consulta = array();

				foreach($Productos_v as $Datos)
				{
					
					$Consulta[] = '
						(
							NULL,
							"'.$Id_grupo.'",
							"'.$Id_Cliente.'",
							"'.$Codigo_cliente.'",
							"'.$Datos['idp'].'",
							"'.$Datos['pre'].'",
							1,
							"'.$Datos['con'].'",
							"s"
						)
					';

				}

				$Consulta = '
					insert into producto_cliente values '.implode(', ', $Consulta).'
				';

				$this->db->query($Consulta);
				
			}


			return 'ok';
			
		}
	}
}

/* Fin del archivo */