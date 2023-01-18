<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Relatório de estoque</h3>
                    <div class="box-tools">
                        <a href="#" onclick="window.open('<?= site_url('reports/exportarrelatorioestoque/' . $id) ?>')" class="tip btn btn-success btn-xs" title="Exportar Relatório" data-original-title="Exportar Relatório"><i class="fa fa-file-excel-o"></i></a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">

                        <table id="relatorioEstoque" class="table table-bordered table-striped">
                            <thead>

                                <tr>
                                    <?php foreach ($arrProdutos['cabecalho'] as $desc) : ?>
                                        <?php if (is_array($desc)) : ?>
                                            <?php foreach ($desc as $desc2) : ?>
                                                <th><?= $desc2 ?></th>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <th><?= $desc ?></th>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>                         
                                <?php if ($arrProdutos['produtos']) : ?>
                                    <?php foreach ($arrProdutos['produtos'] as $pname => $arrProduto) : ?>
                                        <tr>
                                            <td><?= $arrProduto['codigo'] ?></td>
                                            <td><?= $arrProduto['name'] ?></td>
                                            <td><?= $arrProduto['valor'] ?></td>

                                            <?php foreach ($arrProduto['arrEstoqueLojas']['lojas'] as $codLoja => $qtd) : ?>

                                                <td><?= $qtd ?></td>

                                            <?php endforeach; ?>

                                            <td><?= $arrProduto['arrEstoqueLojas']['total'] ?></td>

                                            <?php foreach ($arrProduto['arrEstoqueDepositos']['depositos'] as $codDeposito => $qtd) : ?>

                                                <td><?= $qtd ?></td>

                                            <?php endforeach; ?>

                                            <td><?= $arrProduto['arrEstoqueDepositos']['total'] ?></td>

                                            <?php foreach ($arrProduto['arrVendasLojas']['lojas'] as $codLoja => $qtd) : ?>

                                                <td><?= $qtd ?></td>

                                            <?php endforeach; ?>

                                            <td><?= $arrProduto['arrVendasLojas']['total'] ?></td>
                                            <td><?= $arrProduto['cohort'] ?></td>
                                            <td><?= $arrProduto['percentual'] * 100 ?>%</td>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {

        $('#relatorioEstoque').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': false
        })
    });
</script>