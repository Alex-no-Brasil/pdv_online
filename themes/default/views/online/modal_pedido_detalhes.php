<style>
    .borda{
        border: solid 1px black;
    }
    .centro{
        text-align: center;
    }
</style>

<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">Romaneio pedido: <?= $pedido->externalId ?></h4>
</div>

<div class="modal-body">

    <table width="100%" id="relatorio">
        <tbody>
            <tr>
                <th class="borda" width="20%">DATA</td>
                <td class="borda centro"><?= $pedido->externalCreated; ?></td>
            </tr>
            <tr>
                <th class="borda">VENDEDOR(A)</td>
                <td class="borda centro"><?= $pedido->sellerName; ?></td>
            </tr>
            <tr>
                <th class="borda">NOME CLIENTE</td>
                <td class="borda centro"><?= $cliente->name; ?></td>
            </tr>
            <tr>
                <th class="borda">TELEFONE CLIENTE</td>
                <td class="borda centro"><?= $cliente->phone; ?></td>
            </tr>
            <tr>
                <th class="borda">CPF/CNPJ CLIENTE</td>
                <td class="borda centro"><?= $cliente->cpfCnpj; ?></td>
            </tr>
            <tr>
                <th class="borda">ID DO PEDIDO</td>
                <td class="borda centro"><?= $pedido->externalId ?></td>
            </tr>
            <tr>
                <th class="borda">ORIGEM</td>
                <td class="borda centro"><?= $pedido->origem ?></td>
            </tr>
            <tr>
                <th class="borda">FORMA DE ENVIO</td>
                <td class="borda centro"><?= $entrega->service ?></td>
            </tr>
            <tr>
                <th class="borda">FORMA DE PGTO</td>
                <td class="borda centro"><?= $pedido->paymentType ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%">
        <thead>
            <tr>
                <th class="borda centro">QTD PEÇAS</th>
                <th class="borda centro" style="background-color:yellow">TOTAL PEDIDO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="borda centro"><?= $pedido->totalItems; ?></td>
                <td class="borda centro"><?= $pedido->totalAmount; ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%">
        <thead>
            <tr>
                <th class="centro" width="30%">Código</th>
                <th class="centro" width="30%">Nome</th>
                <th class="centro" width="10%">Quantidade</th>
                <th class="centro" width="15%">Preço</th>
                <th class="centro" width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td class="centro"><?= $item->sku; ?></td>
                    <td class="centro"><?= $item->name; ?></td>
                    <td class="centro"><?= intval($item->quantity); ?></td>
                    <td class="centro">R$ <?= $item->price; ?></td>
                    <td class="centro">R$ <?= $item->quantity * $item->price; ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>


<script>
</script>
