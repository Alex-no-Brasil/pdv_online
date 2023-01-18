<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>

<style type="text/css">
    th, td {
        text-align: center;
    }

</style>


<!--Modal imagem PADRÃO-->
<div class="modal fade bd-example-modal-lg" id="mdlNovo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Novo preço</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-4">
                        <label>Código do produto</label>
                        <input type="text" id="cod_produto" class="form-control">
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Preço novo</label>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <input type="text" id="preco_novo" class="dinheiro form-control" required placeholder="99,99">
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="cod_alteracao">
                <button id="salva_alteracao" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SALVAR</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlNovo()">
                        <i class="fa fa-plus"></i> Adicionar
                    </button>
                    <br><br>
                    <div class="table-responsive">                        
                        <table id="table_alteracao_preco" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Data</th>
                                    <th>Código</th>
                                    <th>Categoria</th>
                                    <th>Preço Velho</th>
                                    <th>Preço Novo</th>
                                    <th>Confirma</th>

                                    <?php foreach ($lojas as $cod => $d) : ?>
                                        <th> <?= $cod ?> </th>
                                    <?php endforeach; ?>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alteracoes as $altera) : ?>
                                    <tr>
                                        <td>
                                            <?= $this->tec->hrsd($altera->data_criada) ?>
                                        </td>
                                        <td>
                                            <?= $altera->cod_produto ?>
                                        </td>
                                        <td>
                                            <?= $altera->categoria ?>
                                        </td>
                                        <td>
                                            R$ <?= $altera->preco_anterior ?>
                                        </td>
                                        <td>
                                            R$ <?= $altera->preco_novo ?>
                                        </td>
                                        <td>
                                            <?php if (empty($altera->data_aprovacao)) : ?>
                                                <a href="#" class="btn btn-sm btn-success" onclick="aprovado_preco(<?= $altera->id ?>)">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            <?php else : ?>
                                                <span class="label label-success">Confirmado</span>
                                            <?php endif; ?>
                                        </td>

                                        <?php foreach ($altera->lojas as $loja) : ?>
                                            <td>
                                                <?php if (empty($loja->data_confirma)) : ?>
                                                    <span class="label label-warning">Pendente</span>
                                                <?php else : ?>
                                                    <span class="label label-success">Confirmado</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>

                                        <td>
                                            <?php if (empty($altera->data_aprovacao)) : ?>
                                                <div class="btn-group actions">
                                                    <a href="#" class="tip btn btn-warning btn-xs" onclick="edita_alteracao(<?= $altera->id . ",'$altera->cod_produto',$altera->preco_novo" ?>)">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="tip btn btn-danger btn-xs" onclick="delete_alteracao(<?= $altera->id ?>)">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </div>                                   
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--for money mask-->
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js">
</script> 

<script type="text/javascript">

    $(document).ready(function () {
        $('#table_alteracao_preco').DataTable({
            "aaSorting": [
                [0, "desc"]
            ],
            "iDisplayLength": 50,
            "aoColumnDefs": [{
                    "aTargets": [0],
                    "mRender": function (data, type, full) {

                        if (type === 'sort') {
                            var parts = data.split('/');
                            return parts[2] + parts[1] + parts[0];
                        }

                        return data;
                    }
                }
            ]
        });
    });

    function openMdlNovo() {
        $('#cod_produto').val('');
        $('#preco_novo').val('');
        $('#cod_alteracao').val('');

        $("#mdlNovo").modal('show');
    }

    function edita_alteracao(cod_alteracao, cod_produto, preco_novo) {
        $('#cod_produto').val(cod_produto);
        $('#preco_novo').val(preco_novo);
        $('#cod_alteracao').val(cod_alteracao);

        $("#mdlNovo").modal('show');
    }

    function delete_alteracao(id) {

        if (!window.confirm('Tem certeza que deseja excluir?')) {
            return;
        }

        var post = {
            id: id,
            '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
        };

        $.post('alteracao_preco_delete', post, function (resp) {
            location.reload();
        });
    }

    function salva_alteracao(post) {

        post['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';

        $.post('alteracao_preco_salvar', post, function (resp) {

            if (resp === 'Ok') {
                location.reload();
            } else {
                alert(resp);
            }
        });
    }

    $('#salva_alteracao').click(function () {

        var cod_produto = $('#cod_produto').val();

        var preco_novo = $('#preco_novo').val();

        if (cod_produto && preco_novo) {
            salva_alteracao({
                id: $('#cod_alteracao').val(),
                cod_produto: cod_produto,
                preco_novo: preco_novo
            });
        }
    });

    function aprovado_preco(id) {

        if (window.confirm('Tem certeza que deseja confirmar?')) {

            $.get('alteracao_preco_confirma/' + id, function () {
                location.reload();
            });
        }
    }

    //money mask
    $('.dinheiro').mask('#.##0,00', {reverse: true});
</script>