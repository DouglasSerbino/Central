<script>
		$(function(){
			$("[name=fecha1]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
			$("[name=fecha2]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
			$("[name=fecha3]").datepicker({dateFormat: 'yy-mm-dd', showButtonPanel: true});
		});
		function validar_info()
		{
				var select = $("select option:selected").val();
				var soli = $('#solicitado').val();
				var proceso = $('#proc1').val();
				var fecha = $('#fecha1').val();
				
				if(proceso == '')
				{
						alert('Ingrese un proceso');
						$('#proc1').focus();
						exit();
				}
				
				if(fecha== '')
				{
						alert('Seleccione una fecha');
						$('#fecha1').focus();
						exit();
				}
				
				if(select == '')
				{
						alert('Seleccione un motivo');
						exit();
				}
				
				if(soli == '')
				{
						alert('Quien solicita el cambio?');
						$('#solicitado').focus();
						exit();
				}
				
				
				if('' != proceso && '' != soli && '' != select)
				{
						$('#guardar_cambio').submit();
				}
		}
</script>

<form method='post' action='/herramientas_sis/cambio_fecha/sol_cambio' id='guardar_cambio'>
	<table>
		<tr>
				<th></th>
				<th>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Proceso
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Nueva Fecha
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Motivo</th>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type='text' name='proc1' id='proc1'>
				<input type="text" name="fecha1" id="fecha1" size="12" value="" readonly="readonly" />
				<select name='opcion1'>
					<option value=""></option>
					<option value="Falta de Informaci&oacute;n">Falta de Informaci&oacute;n</option>
					<option value="Prioridades">Prioridades</option>
					<option value="Carga Laboral">Carga Laboral</option>
					<option value="Nuevo Digital">Nuevo Digital</option>
				</select>
				<br />
				
				<input type='text' name='proc2' id='proc2'>
				<input type="text" name="fecha2" id="fecha2" size="12" value="" readonly="readonly" />
				<select name='opcion2'>
					<option value=""></option>
					<option value="Falta de Informaci&oacute;n">Falta de Informaci&oacute;n</option>
					<option value="Prioridades">Prioridades</option>
					<option value="Carga Laboral">Carga Laboral</option>
					<option value="Nuevo Digital">Nuevo Digital</option>
				</select>
				<br />
				
				<input type='text' name='proc3' id='proc3'>
				<input type="text" name="fecha3" id="fecha3" size="12" value="" readonly="readonly">
				<select name='opcion3'>
					<option value=""></option>
					<option value="Falta de Informaci&oacute;n">Falta de Informaci&oacute;n</option>
					<option value="Prioridades">Prioridades</option>
					<option value="Carga Laboral">Carga Laboral</option>
					<option value="Nuevo Digital">Nuevo Digital</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>Solicitado por</strong></td>
			<td><input type='text' name='solicitado' id='solicitado' size='50'></td>
		</tr>
		<tr>
			<td colspan='2' style='text-align: center;'>
				<br />
				<input type='button' value='Guardar' onclick='validar_info();'>
			</td>
		</tr>
	</table>
</form>