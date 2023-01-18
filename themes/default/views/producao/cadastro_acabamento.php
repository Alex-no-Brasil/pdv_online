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
        <!-- /.modal-content -->
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
                        <table id="table_acabamento" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Acabamento</th>
                                    <th>REF</th>
                                    <th>Telefone</th>
                                    <th>Cidade</th>
                                    <th>Bairro</th>
                                    <th>Endereço</th>
                                    <th>Qtd. Corte</th>
                                    <th>Entregue</th>
                                    <th>Falta</th>
                                    <th>Atraso</th>
                                    <th>Média</th>
                                    <th>Nível</th>
                                    <th style="width: 55px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($acabamentos as $acabamento): ?>
                                    <tr>

                                        <td><?= $acabamento->nome ?></td>
                                        <td><?= $acabamento->id ?></td>
                                        <td><?= $acabamento->telefone ?></td>
                                        <td><?= $acabamento->cidade . " " . $acabamento->uf ?></td>
                                        <td><?= $acabamento->bairro ?></td>
                                        <td><?= $acabamento->endereco . ", " . $acabamento->numero . " " . $acabamento->complemento ?></td>
                                        <td><?= $acabamento->qtd_cortes ?></td>
                                        <td><?= $acabamento->qtd_entregas ?></td>
                                        <td><?= $acabamento->qtd_cortes - $acabamento->qtd_entregas ?></td>
                                        <td><?= $acabamento->qtd_atrasos ?></td>
                                        <td><?= $acabamento->media ?></td>
                                        <td><?= $acabamento->nivel ?></td>
                                        <td>
                                            <a href="#" class="btn btn-warning" onclick="openMdlNovo(<?= $acabamento->id ?>)">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger" onclick="excluir(<?= $acabamento->id ?>)">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
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



<script>
    $(document).ready(function () {
        $('#table_acabamento').DataTable({
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
            "iDisplayLength": 2
        });
    });

    function openMdlNovo(id) {
        //$("#mdlCriar").modal('show');

        var url = 'modal_cadastro_acabamento';

        if (id > 0) {
            url += '/' + id;
        }

        $.ajax({
            type: 'GET',
            //Caminho do arquivo do seu modal
            url: url,
            success: function (data) {
                $('#mdlCriar .modal-content').html(data);
                $('#mdlCriar').modal('show');
            }
        });

    }
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

            $.post('acabamento_excluir', post, function (resp) {

                if (resp === 'Ok') {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
        }
    </script>
<?php endif; ?>

</script>

