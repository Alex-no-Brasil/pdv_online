<script>
    var objDEPOSITOS;
    getDepositos();

    function getDepositos() {
        if (navigator.onLine) {

            $.ajax({

                url: '<?= site_url('depositos/getDepositos') ?>',
                method: 'POST',
                async: true,

            }).done(function(json) {

                if (json != 'false') {

                    var obj = $.parseJSON(json);

                    objLOJAS = obj

                    $.each(obj, function(idx, arr) {

                        $('#cod_deposito_destino').append($('<option>', {
                            value: arr.cod,
                            text: arr.cod + ' - ' + arr.tipo + ' ' + arr.nome
                        }));


                    });



                }

            });

        }
    }
</script>

<div class="modal fade bd-example-modal-lg" id="mdlEntradaEstoque">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Entrada de Estoque</h4>
            </div>
            <?= form_open_multipart("depositos/entradaestoque", ['id' => 'formEntradaEstoque', 'class' => 'validation']); ?>
            <div class="modal-body">


                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inserir Produto para transferência</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input id="pesquisaInserirProdutoEntrada" type="text" class="form-control pull-right" placeholder="Código ou Nome do produto">

                                <div class="input-group-btn">
                                    <button id="btnPesquisaInserirProdutoEntrada" type="button" class="btn btn-default"><i id="iconPesquisaInserirProdutoEntrada" class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" id="resultadoPesquisaInserirProdutoEntrada">
                        <p><i>Faça a busca pelo produto acima</i></p>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtos Inseridos</h3>

                    </div>
                    <div class="box-body">
                        <p id="produtosNInseridosEntrada"><i>Nenhum produto inserido</i></p>
                        <div id="dvProdutosEntrada">

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Data</label>
                    <input id="data" type="date" name="data" value="<?=date('Y-m-d')?>">
                </div>

                <div class="form-group">
                    <label for="status">Depósito</label>
                    <select name="cod_deposito_destino" id="cod_deposito_destino" data-placeholder="Selecione o Depósito" class="form-control input-tip select2 select2-hidden-accessible" style="width:100%;" tabindex="-1" onchange="validarEntrada()" aria-hidden="true">
                        <option value="" selected="selected"></option>

                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button id="btnConfirmarEntrada" type="submit" class="btn btn-success btn-flat disabled"><i class="far fa-trash-alt"></i>&nbsp; CONFIRMAR</button>
            </div>
            <?= form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var idE = 1;

    function validarEntrada() {

        var valido = true;

        if ($('.productEntrar').length) {

            $('.productEntrar').each(function() {

                var idx = $(this).data('id');                
                if ($('#qtd_entrar_' + idx).val() == "" || parseFloat($('#qtd_entrar_' + idx).val()) <= 0) {
                    valido = false;

                }

            });

            $('#produtosNInseridosEntrada').hide();

        } else {
            $('#produtosNInseridosEntrada').show();
            valido = false;
        }

        if (!$('#cod_deposito_destino').val()) {

            valido = false;
        }

        if (!valido) {
            $('#btnConfirmarEntrada').addClass('disabled');
        } else {
            $('#btnConfirmarEntrada').removeClass('disabled');

        }

    }

    function remover_entrada(idx) {

        $('#dventradaproduto_' + idx).remove();
        validarEntrada();
    }



    function buscaProdutoByCodONome_entrada() {

        $.ajax({

            url: '<?= site_url('products/buscaProdutoByCodONome') ?>',
            method: 'POST',
            async: true,
            data: {

                pesquisa: $('#pesquisaInserirProdutoEntrada').val()

            },
            beforeSend: function() {

                $('#iconPesquisaInserirProdutoEntrada').removeClass('fa-search');
                $('#iconPesquisaInserirProdutoEntrada').addClass('fa-spinner fa-spin');
            }

        }).done(function(json) {

            if (json != []) {

                var obj = $.parseJSON(json);

                if (obj.dados) {

                    html = '<table class="table table-bordered">';
                    html += '<tbody>';
                    html += '<tr>';
                    html += '<th>Código</th>';
                    html += '<th>Nome</th>';
                    html += '<th>QTD ENTRAR</th>';
                    html += '<th>Inserir</th>';
                    html += '</tr>';

                    $.each(obj.dados, function(idx, arr) {

                        html += '<tr>';
                        html += '<td>' + arr.code + '</td>';
                        html += '<td>' + arr.name + '</td>';
                        html += '<td><input type="number" id="qtd_entrada_' + arr.code + '"></td>';
                        html += '<td><a href="javascript:void(0);" title="" class="tip btn btn-success btn-xs" onclick="insereProdutoPesquisado_entrada(' + arr.id + ',\'' + arr.code + '\',\'' + arr.name + '\',\'' + arr.quantity + '\', document.getElementById(\'qtd_entrada_' + arr.code + '\').value)" data-original-title="Transferir Estoque"><i class="fa fa-long-arrow-right"></i></a></td>';
                        html += '</tr>';

                    });

                    html += '</tbody>';
                    html += '</table>';

                    $('#resultadoPesquisaInserirProdutoEntrada').html(html);

                } else {

                    $('#resultadoPesquisaInserirProdutoEntrada').html('<p><i>Nenhum produto encontrado!</i></p>');
                }
            }

            $('#iconPesquisaInserirProdutoEntrada').removeClass('fa-spinner fa-spin');
            $('#iconPesquisaInserirProdutoEntrada').addClass('fa-search');

        });
    }

    function insereProdutoPesquisado_entrada(idProduto, codProduto, nomeProduto, qtdAtual, qtdTransferir) {

        getMdlSolicitarEntrada(idProduto, codProduto, nomeProduto, qtdAtual, qtdTransferir);
        $('#resultadoPesquisaInserirProdutoEntrada').html('<p><i>Faça a busca pelo produto acima</i></p>');
        validarEntrada();
        var idx = idE - 1;

    }

    function getMdlSolicitarEntrada(idProduto, codProduto, nomeProduto, qtdAtual, qtdTransferir) {

        $("#ajaxCall").show();

        if (idProduto) {
            var codRepetido = false;
            var idRepetido = false;

            $('.productEntrar').each(function() {


                if ($(this).data('cod-produto') == codProduto) {

                    codRepetido = true;
                    idRepetido = this.id;

                }

            });


            if (!codRepetido) {

                var html = getHtmlInserirItemEntrada(idE, idProduto, codProduto, nomeProduto, qtdAtual, qtdTransferir);

            } else {

                var produtoRepetido = $('#' + idRepetido);
                $('#' + idRepetido).remove();
                html = produtoRepetido;
                produtoRepetido.removeClass('box-danger');
                produtoRepetido.removeClass('box-success');
                produtoRepetido.addClass('box-warning');
            }

            $('#dvProdutosEntrada').prepend(html);
            idE++;
        }

        $("#ajaxCall").hide();

    }

    function getHtmlInserirItemEntrada(idx, idProduto, codProduto, nomeProduto, qtdAtual, qtdTransferir) {

        var html = '<div data-cod-produto="' + codProduto + '" data-id="' + idx + '" class="productEntrar box box-success" id="dventradaproduto_' + idx + '">';
        html += '<div class="box-header with-border">';
        html += '<div class="box-tools pull-right">';

        html += '<a href="javascript:void(0);"  onclick="remover_entrada(' + idx + ')" class="tip btn btn-danger btn-xs" data-original-title="Deletar Produto"><i class="fa fa-trash-o"></i></a>';
        html += '</div>';

        html += '</div>';

        html += '<div class="box-body">';
        html += '<input type="hidden" id="id_produto_' + idx + '">';
        html += '<div class="col-xs-4"><label>Código</label><input id="cod_produto_' + idx + '" name="arrProdutos[' + idx + '][cod_produto]" type="text" class="form-control" value="' + codProduto + '" readonly></div>';
        html += '<div class="col-xs-4"><label>Nome</label><input id="nome_produto_' + idx + '" type="text" class="form-control" value="' + nomeProduto + '" readonly></div>';
        html += '<div class="col-xs-4"><label>QTD ENTRAR</label><input onchange="validarEntrada()" data-qtd="" id="qtd_entrar_' + idx + '" data-qtd-atual="' + qtdAtual + '" data-id="' + idx + '" name="arrProdutos[' + idx + '][qtd_entrar]" type="number" class="form-control" value="' + qtdTransferir + '" required="required" minlength="1"><span id="erro_' + idx + '" class="help-block" hidden></div></div>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    $('#btnPesquisaInserirProdutoEntrada').click(function() {
        buscaProdutoByCodONome_entrada();

    });
    
</script>