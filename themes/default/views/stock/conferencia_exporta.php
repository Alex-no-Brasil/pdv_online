<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Conferencia</title>
    </head>
    <body>
        <table>
            <tr>
                <th colspan="12" b style="text-align:center;background-color: #ffff00;border:1px solid #000000;">
                    <?= $categoria ?> de R$ <?= $produtos[0]->price ?>
                </th>
            </tr>
            <tr>
                <th style="border:1px solid #000000; text-align: center">Código</th>
                <th style="border:1px solid #000000; text-align: center">Tipo</th>
                <th style="border:1px solid #000000; text-align: center">Estoque</th>
                <th style="border:1px solid #000000; text-align: center">Valor</th>
                <th colspan="7" style="border:1px solid #000000; text-align: center">Confirmação</th>
                <th width="20" style="border:1px solid #000000; text-align: center">Observação</th>
            </tr>

            <?php foreach ($produtos as $produto) : ?>
            <tr>
                <td style="border:1px solid #000000;">
                    <?= $produto->code ?>
                </td>
                <td width="30" style="border:1px solid #000000; text-align: center">
                    <?= $produto->name ?>
                </td>
                <td style="border:1px solid #000000; text-align: center">
                    <?= $produto->estoque ?>
                </td>
                <td style="border:1px solid #000000; text-align: center">
                    R$ <?= $produto->price ?>
                </td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="2" style="border:1px solid #000000;">&nbsp;</td>
                <td width="20" style="border:1px solid #000000;"></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>