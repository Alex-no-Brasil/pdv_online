<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        function image(n) {
            if (n !== null) {
                return '<div style="text-align: center; margin: 0 5px;"><a href="<?= base_url(); ?>uploads/' + n + '" class="open-image">\n\
    <img src="<?= base_url(); ?>uploads/thumbs/' + n + '" alt="" style="width:32px;max-width:32px">\n\
</a>\n\
</div>';
            }
            return '';
        }

        function method(n) {
            return (n == 0) ? '<span class="label label-primary"><?= lang('inclusive'); ?></span>' : '<span class="label label-warning"><?= lang('exclusive'); ?></span>';
        }
        $('#fileData').dataTable({
            'sScrollY': (window.innerHeight - 300) + 'px',/*AQUIIIIII*/
            "aLengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, '<?= lang('all'); ?>']
            ],
            "aaSorting": [
                [1, "asc"]
            ],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/get_products') ?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
            "aoColumns": [{
                "mRender": image,
                "bSortable": false
            }, {
                sClass: "text-center"
            }, {
                sClass: "text-center"
            }, {
                sClass: "text-center"
            }, {
                sClass: "text-center"
            }, {
                sClass: "text-center"
            }, {
                sClass: "text-center"
            }, {
                sClass: "text-center"
            }, {
                "bSortable": false,
                "bSearchable": false
            }]
        });
        //{"data":"tax_method","render":method},
        $('#fileData').on('click', '.image', function() {
            var a_href = $(this).attr('href');
            var code = $(this).attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src', a_href);
            $('#picModal').modal();
            return false;
        });
        $('#fileData').on('click', '.barcode', function() {
            var a_href = $(this).attr('href');
            var code = $(this).attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src', a_href);
            $('#picModal').modal();
            return false;
        });
        $('#fileData').on('click', '.open-image', function() {
            var a_href = $(this).attr('href');
            var code = $(this).closest('tr').find('.image').attr('id');
            $('#myModalLabel').text(code);
            $('#product_image').attr('src', a_href);
            $('#picModal').modal();
            return false;
        });


    });
</script>
<style type="text/css">
    .table td:first-child {
        padding: 1px;
    }

    .table td:nth-child(6),
    .table td:nth-child(7),
    .table td:nth-child(8) {
        text-align: center;
    }

    .table td:nth-child(9)<?= $Admin ? ', .table td:nth-child(10)' : ''; ?> {
        text-align: right;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="fileData" class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                            <thead>
                                 <tr class="active">
                                    <th style="width: 80px"><?= lang("image"); ?></th>
                                    <th style="width: 80px"><?= lang("code"); ?></th>
                                    <th><?= lang("name"); ?></th>
                                    <th>EAN</th>
                                    <th style="width: 60px"><?= lang("category"); ?></th>
                                    <th>Modelo</th>
                                    <th><?= lang("quantity"); ?></th>
                                    <th style="width: 50px; text-align: right"><?= lang("price"); ?></th>
                                    <th><?= lang("actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade" id="picModal" tabindex="-1" role="dialog" aria-labelledby="picModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                                    <h4 class="modal-title" id="myModalLabel">title</h4>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="product_image" src="" alt="" style="max-width: 500px"/>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="mdlEstoqueDepositos">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Estoques nos depósitos - PRODUTO: <b id="codProd"></b></h4>
                                </div>

                                <div class="modal-body">
                                    <form id='formEdtEstoque' action='<?= site_url('depositos/edtestoque') ?>' method='POST'>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Depósito</th>
                                                    <th>Estoque</th>
                                                </tr>
                                            </tbody>
                                            <tbody id="tbodyEstoqueDepositos">
                                            </tbody>
                                        </table>
                                    </form>

                                </div>

                                <div class="modal-footer">
                                    <a href="#" onclick="edtEstoque()" title="Editar" class="btn btn-warning" data-original-title="Editar Estoque">Editar Estoque</a>
                                </div>

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <script>
                        function edtEstoque() {

                            //formEdtEstoque
                            $('#formEdtEstoque input').each(function() {

                                var qtdAtualEstoque = parseInt($(this).data('estoque-atual'));
                                var novaQtdEstoque = parseInt(this.value);

                                if (qtdAtualEstoque == novaQtdEstoque) {

                                    $(this).removeAttr('name');
                                }

                            });

                            $('#formEdtEstoque').submit();


                        }

                        function mdlTransferirEstoque(idProduto, codProduto, nomeProduto, qtdAtual) {


                            $('#idProdutoTransferir').val(idProduto);
                            $('#codProduto').html(codProduto);
                            $('#nomeProduto').html(nomeProduto);
                            $('#qtdAtual').html(qtdAtual);

                            $("#mdlTransferirEstoque").modal('show');


                        }


                        function getEstoqueProduto(cod_produto, nome) {

                            $('#codProd').html(cod_produto + ' - ' + nome);

                            $.ajax({

                                url: '<?= site_url('depositos/get_estoque_depositos') ?>',
                                method: 'POST',
                                async: true,
                                data: {
                                    <?= $this->security->get_csrf_token_name() ?>: "<?= $this->security->get_csrf_hash() ?>",
                                    cod_produto: cod_produto

                                }
                            }).done(function(json) {

                                if (json != 'false') {

                                    var arrResposta = $.parseJSON(json);
                                    var html = "";

                                    $.each(arrResposta, function(idx, arr) {


                                        var estoque = 0;

                                        if (typeof arr.produto !== 'undefined') {

                                            estoque = arr.produto.qtd;
                                        }

                                        var codEstoque = arr.cod.replace(/\s/g, '++');
                                        html += '<tr>';
                                        html += '<td>' + arr.cod + ' - ' + arr.nome + '</td>';
                                        html += '<td><input type="number" name="estoque-' + codEstoque + '-' + cod_produto + '" id="estoque-' + codEstoque + '-' + cod_produto + '" data-estoque-atual="' + estoque + '" value="' + estoque + '" step="1"></td>';
                                        html += '</tr>';
                                    })

                                    $('#tbodyEstoqueDepositos').html(html);
                                    $('#mdlEstoqueDepositos').modal('show');

                                }

                            });

                        }
                    </script>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>