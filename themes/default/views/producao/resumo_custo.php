<style>
    th, td {
        text-align: center;
    }
</style>
<!--Modal de novo-->
<div class="modal fade bd-example-modal-lg" id="mdlCriar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="openMdlNovo()">
                        <i class="fa fa-plus"></i> Adicionar
                    </button>
                    <br><br>
                    <div class="table-responsive">                        
                        <table id="table_custo" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Código</th>
                                    <th>Oficina</th>
                                    <th>Cód. Produto</th>
                                    <th>Foto</th>
                                    <th>Corte</th>
                                    <th>Qtd/Peças</th>
                                    <th>Valor Tecido</th>
                                    <th>Metragem</th>
                                    <th>Total Tecido</th>
                                    <th>Valor Oficina</th>
                                    <th>Acessório</th>
                                    <th>Corte</th>
                                    <th>Modelagem</th>
                                    <th>Acabamento</th>
                                    <th>Custo</th>
                                    <th style="width: 50px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro) : ?>
                                    <tr>
                                        <td>
                                            <?= $cadastro->piloto_corte_id ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->oficina_nome ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->cod_produto ?>
                                        </td>
                                        <td>
                                            <img src="<?= $cadastro->arq_mostruario ?>" height="80">
                                        </td>
                                        <td>
                                            <?= $cadastro->qtd_cortes ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->qtd_pecas ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->tecido_preco ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->tecido_metro ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->tecido_total ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->valor_oficina ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->acessorio_total ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->valor_corte ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->valor_modelagem ?>
                                        </td>
                                        <td>
                                            R$ <?= $cadastro->valor_acabamento ?>
                                        </td>
                                        <td>
                                            R$ <?=
                                            $cadastro->tecido_total + $cadastro->valor_oficina + $cadastro->acessorio_total+ $cadastro->valor_corte + 
                                            $cadastro->valor_modelagem + $cadastro->valor_acabamento
                                            ?>
                                        </td>
                                        <td width="80px">
                                            <a href="#" class="tip btn btn-warning btn-xs" onclick="openMdlNovo(<?= $cadastro->id ?>)">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <?php if ($Admin) : ?>
                                                <a href="#" onclick="excluir(<?= $cadastro->id ?>)" class="btn btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
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

<script>
    $(document).ready(function () {
        $('#table_custo').DataTable({
            'sScrollY': (window.innerHeight - 300) + 'px',
            "oLanguage": {
                "sLengthMenu": '<select class="select2">\n\
                    <option>10</option>\n\
                    <option selected>25</option>\n\
                    <option>50</option>\n\
                    <option>100</option>\n\
                    <option>200</option>\n\
                    <option>500</option>\n\
                </select>'
            },
            "iDisplayLength": 25
        });
    });

    function openMdlNovo(id) {
        var url = 'modal_resumo_custo';

        if (id > 0) {
            url += '/' + id;
        }

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $('#mdlCriar .modal-content').html(data);
                $('#mdlCriar').modal('show');
            }
        });
    }
    //money mask
    $('.dinheiro').mask('#.##0,00', {reverse: true});   
</script>

<?php if ($Admin) : ?>
    <script>
        function excluir(id) {
            if (!window.confirm("Tem certeza que deseja excluir?")) {
                return;
            }

            var post = {
                id: id,
                '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
            };

            $.post('resumo_custo_excluir', post, function (resp) {

                if (resp === 'Ok') {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
        }
    </script>
<?php endif; ?>