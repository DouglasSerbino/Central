<form id="crear_dpto" method="post" action="/departamentos/agregar/agregar_datos" onsubmit="return validar('crear_dpto')">
    <table>
        <tr>
            <td><strong>Codigo</strong></td>
            <td><input type="text" name="codigo" size="10" class="requ">*</td>
        </tr>
        <tr>
            <td><strong>Departamento</strong></td>
            <td><input type="text" name="nombre_dpto" size="25" class="requ">*</td>
        </tr>
        <tr>
            <td><strong>Tipo de Inv</strong></td>
            <td><input type="text" name="tipo_inv" size="10" class="requ">*</td>
        </tr>
        <tr>
            <td><strong>Cantidad Mensual</strong></td>
            <td><input type="text" name="cant_mensual" size="10" class="requ">*</td>
        </tr>
        <tr>
            <td><strong>Iniciales</strong></td>
            <td><input type="text" name="iniciales" size="10" class="requ">*</td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Guardar"></td>
        </tr>
    </table>
</form>