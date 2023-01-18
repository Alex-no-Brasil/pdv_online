<?php foreach ($produtos as $produto) : ?>

    <tr>
        <td style="color:red">
            <?= $produto->code ?>
        </td>
        <td>
            <?= $produto->name ?>
        </td>
        <td style="text-align:center">
            <?= $produto->estoque ?>
        </td>
        <td style="text-align:center">
            R$ <?= $produto->price ?>
        </td>
        <td>
            <table style="width: 100%">
                <tr>
                    <td class="tdEsquerda">&nbsp;</td>
                    <td class="tdCentro">&nbsp;</td>
                    <td class="tdCentro">&nbsp;</td>
                    <td class="tdCentro">&nbsp;</td>
                    <td class="tdCentro">&nbsp;</td>
                    <td class="tdCentro">&nbsp;</td>
                    <td class="tdDireita">&nbsp;</td>                                        
                </tr>
            </table>                                
        </td>
        <td></td>
    </tr>
<?php endforeach; ?>