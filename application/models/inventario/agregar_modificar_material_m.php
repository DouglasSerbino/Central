<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_modificar_material_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	/**
	 *Modificar los materiales del sistema
	 *@param int id del material que queremos modificar.
	 *@param string $Codigo_sap codigo del material que queremos modificar
	 *@param string $Valor: valor del material que queremos modificar.
	 *@param string $Cantidad_Unidad: Cantida o unidad del material que queremos modificar.
	 *@param string $Tipo: Tipo al que pertenece cada material.
	 *@param string $Nombre: Nombre del material que se quiere modificar.
	 *@param string $Existencias: Existencias en el sistema del material.
	 *@param string $Minimo: Cantidad minima que debe de existir dentro del inventario.
	 *@param string $Observaciones: Observaciones que se tengan del material.
	 *@param string $id_inventario_equipo: Id del equipo al que pertenece el material.
	 *@return string: 'ok' si el material se modifica exitosamente
	 *@return string: 'error' si no se puede modificar el material.
	*/
	function modificar_sql(
		$Id_inventario_material,
		$Codigo_sap,
		$Valor,
		$Cantidad_unidad,
		$Tipo,
		$Proveedor,
		$Existencias,
		$Id_inventario_equipo,
		$Nombre,
		$Observacion,
		$numero_individual,
		$numero_cajas,
		$MP_MT,
		$Pais
	)
	{
		//Validamos que no exista el codigo sap dentro de la base de datos.
		//Verificamos que el material a mostrar pertenezca al grupo seleccionado.
		$Consulta = 'select id_inventario_material
								from inventario_material
								where codigo_sap = "'.$Codigo_sap.'"
								and id_inventario_material != "'.$Id_inventario_material.'"
								and id_grupo = "'.$this->session->userdata["id_grupo"].'"';
	
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		//Verificamos el resultado.
		//Si es igual a cero significa que el codigo sap no existe
		//y podemos proceder y actualizar la informacion.
		if(0 == $Resultado->num_rows())
		{
			
			
			if($Proveedor != 0)
			{
				$Consulta = 'insert into inventario_material_proveedor
											values (null, "'.$Id_inventario_material.'","'.$Proveedor.'")';
				$Resultado = $this->db->query($Consulta);
			}
			
			
			$Consulta = 'update inventario_material set
									codigo_sap = "'.$Codigo_sap.'",
									valor = "'.$Valor.'",
									cantidad_unidad = "'.$Cantidad_unidad.'",
									tipo = "'.$Tipo.'",
									nombre_material = "'.$Nombre.'",
									existencias = "'.$Existencias.'",
									id_inventario_equipo = "'.$Id_inventario_equipo.'",
									observacion = "'.$Observacion.'",
									numero_individual = "'.$numero_individual.'",
									numero_cajas = "'.$numero_cajas.'",
									mp_mt = "'.$MP_MT.'",
									mat_pais = "'.$Pais.'"
									where id_inventario_material = "'.$Id_inventario_material.'"';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado)
			{//Si la consulta se realizo correctamente
				//Regresar mensaje de ok
				return 'ok';
			}
			else
			{//Si no se ingresa
				//Enviamos mensaje de error
				return 'error';
			}
		}
		else
		{
			return "error";
		}
	}
	
	/**
	 *Funcion que sirve para agregarle lotes a los materiales.
	 *@param int id del material al que le agregaremos un lote.
	 *@param string $Pedido: Pedido al cual le agregaremos el lote.
	 *@param string $Cantidad: Cantidad que ingresaremos.
	 *@param string $Date1: Fecha de ingreso del lote.
	 *@return string: 'ok' si el lote se ingreso correctamente.
	*/
	function lote_agregar($Id_inventario_material, $Pedido, $Cantidad, $Date1)
	{
		
		//Realizamos la validacion para obtener la fecha en
		//el formato correcto para ingresarlo al sistema.
		$Fecha_ingreso = $this->fechas_m->fecha_ymd_dmy($Date1);
		
		//Declaramos la variable estado para saber en que estado se encuentra el material.
		$estado = '1';
		
		//Declaramos la consulta para determinar el estado del material.
		$Consulta = 'select * from inventario_lote where id_inventario_material = "'.$Id_inventario_material.'" and estado = "1"';
		//Realizamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Obtenemos el resultado en un array.
		$Resultado2 = $Resultado->result_array();
		$i = 0;
		foreach($Resultado2 as $Datos)
		{
			$i++;
		}
		//Si el estado es mayor que 1 se le asigna el numero 2.
		if($i > 0)
		{
			$estado = '2';
		}
		//Declaramos la consulta SQL, para ingresar el lote a su respectiva tabla.
		$Consulta = $sql = 'insert into inventario_lote values(
												NULL,
												"'.$Id_inventario_material.'",
												"'.$Pedido.'",
												"'.$Fecha_ingreso.'",
												"0000-00-00",
												"'.$Cantidad.'",
												'.$estado.')';
		//Realizamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Declaramos la consulta para poder determinar cuanto hay en existencia en este momento.
		$Consulta = 'select existencias from inventario_material where id_inventario_material = "'.$Id_inventario_material.'"';
		
		//Realizamos la consulta.
		$Resultado = $this->db->query($Consulta);
		//Obtenemos el resultado en un array.
		$Resultado2 = $Resultado->result_array();
		//Exploramos el array para obtener la informacion
		$Cantidad_anterior = "";
		foreach($Resultado2 as $Datos_cantidad)
		{
			//Obtenemos el total del material que hay hasta el momento.
			$Cantidad_anterior = $Datos_cantidad["existencias"];
		}
		//Con la cantidad anterior podemos sumar la nueva cantidad.
		//Qu estamos ingresando.
		$Cantidad_total = $Cantidad_anterior + $Cantidad;
		
		//Declaramos la consulta para actualizar las existencias en la base de datos.
		$Consulta = 'update inventario_material set existencias = "'.$Cantidad_total.'" where id_inventario_material = "'.$Id_inventario_material.'"';
		//Realizamos la consulta.
		$Resultado = $this->db->query($Consulta);
			
		return "ok";
		
	}

	
	
	/**
	 *Agregar materiales al sistema
	 *@param string $Codigo_sap codigo del material que queremos agregar
	 *@param string $Valor: valor del material que queremos agregar.
	 *@param string $Cantidad_Unidad: Cantida o unidad del material que queremos agregar.
	 *@param string $Tipo: Tipo al que pertenece cada material.
	 *@param string $Nombre: Nombre del material que se quiere agregar.
	 *@param string $Minimo: Cantidad minima que debe de existir dentro del inventario.
	 *@param string $Observaciones: Observaciones que se tengan del material.
	 *@param string $id_inventario_equipo: Id del equipo al que pertenece el material.
	 *@return string: 'ok' si el material se modifica exitosamente
	 *@return string: 'error' si no se puede modificar el material.
	*/
	function agregar_sql(
		$Codigo_sap,
		$Valor,
		$Cantidad_unidad,
		$Tipo,
		$Proveedor,
		$Id_inventario_equipo,
		$Nombre,
		$Observacion,
		$numero_individual,
		$numero_cajas,
		$MP_MT,
		$Pais
	)
	{
		//Establecemos la consulta para verificar que el codigo sap no exista en otro material.
		$Consulta = 'select codigo_sap
								from inventario_material
								where codigo_sap = "'.$Codigo_sap.'"';
		//Realizamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si ya existe el material.
		if($Resultado->num_rows() > 0)
		{
			//regresamos el valor cod.
			//Para determinar que el codigo ingresado ya existe.
			return 'cod';
		}
		else
		{
			$Consulta = '
				insert into inventario_material values(
					null,
					"'.$this->session->userdata["id_grupo"].'",
					"'.$Codigo_sap.'",
					"'.$Valor.'",
					"'.$Cantidad_unidad.'",
					"'.$Tipo.'",
					"'.$Nombre.'",
					"0",
					"'.$Id_inventario_equipo.'",
					"0",
					"'.$Observacion.'",
					"'.$numero_individual.'",
					"'.$numero_cajas.'",
					"'.$MP_MT.'",
					"'.$Pais.'"
				)';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			
			if($Resultado)
			{//Si la consulta se realizo correctamente
				//Regresar mensaje de ok
				$Consulta = 'select id_inventario_material
									from inventario_material
									where codigo_sap = "'.$Codigo_sap.'"';
				
				$Resultado = $this->db->query($Consulta);
				$Datos = $Resultado->result_array();
				foreach($Datos as $Id_material)
				{
					$Id_inventario_material = $Id_material['id_inventario_material'];
				}
				
				//Si el proveedor tiene valores lo almacenamos.
				if($Proveedor != 0 and $Id_inventario_material != '')
				{
					$Consulta = 'insert into inventario_material_proveedor
												values (null, "'.$Id_inventario_material.'","'.$Proveedor.'")';
					$Resultado = $this->db->query($Consulta);
				}
			
				return 'ok';
			}
			else
			{//Si no se ingresa
				//Enviamos mensaje de error
				return 'error';
			}
		}
	}


}
?>