<form id="mod_dpto" method="post" action="/departamentos/modificar/modificar_datos" onsubmit="return validar('mod_dpto')">
    <table>
<?php
$Id_dpto = '';
//foreach($Departamentos as $Departamento)
//{
    $Id_dpto = $Departamento["id_dpto"];
?>
        <input type="hidden" name="id_dpto" value="<?=$Id_dpto?>">
        <tr>
            <td><strong>Codigo</strong></td>
            <td><input type="text" name="codigo" size="10" class="requ" value="<?=$Departamento["codigo"]?>" >*</td>
        </tr>
        <tr>
            <td><strong>Departamento</strong></td>
            <td><input type="text" name="nombre_dpto" class="requ" size="25" value="<?=$Departamento["departamento"]?>">*</td>
        </tr>
        <tr>
            <td><strong>Tipo de Inv</strong></td>
            <td><input type="text" name="tipo_inv" class="requ" size="10" value="<?=$Departamento["tipo_inv"]?>">*</td>
        </tr>
        <tr>
            <td><strong>Cantidad Mensual</strong></td>
            <td><input type="text" name="cant_mensual" class="requ" size="10" value="<?=$Departamento["cant_mensual"]?>">*</td>
        </tr>
        <tr>
            <td><strong>Iniciales</strong></td>
            <td><input type="text" name="iniciales" class="requ" size="10" value="<?=$Departamento["iniciales"]?>">*</td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Modificar"></td>
        </tr>
<?php
//}
?>
    </table>
</form>
